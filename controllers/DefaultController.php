<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class DefaultController extends BaseEventTypeController
{
	protected function beforeAction($action)
	{
		$this->assetPath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.'.$this->getModule()->name.'.assets'), false, -1, YII_DEBUG);
		Yii::app()->clientScript->registerScriptFile($this->assetPath.'/js/eyedraw.js');

		return parent::beforeAction($action);
	}

	/**
	 * set flash message for patient allergies
	 *
	 * @param Patient $patient
	 */
	protected function showAllergyWarning($patient)
	{
		if ($patient->no_allergies_date) {
			Yii::app()->user->setFlash('info.prescription_allergy', $patient->getAllergiesString());
		}
		else {
			Yii::app()->user->setFlash('warning.prescription_allergy', $patient->getAllergiesString());
		}
	}


	public function actionCreate()
	{
		$errors = array();

		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception("Patient not found: ".@$_GET['patient_id']);
		}

		$this->showAllergyWarning($this->patient);

		if (!empty($_POST)) {
			if (preg_match('/^booking([0-9]+)$/',@$_POST['SelectBooking'],$m)) {
				return $this->redirect(array('/OphTrOperationnote/Default/create?patient_id='.$this->patient->id.'&booking_event_id='.$m[1]));
			} elseif (@$_POST['SelectBooking'] == 'emergency') {
				return $this->redirect(array('/OphTrOperationnote/Default/create?patient_id='.$this->patient->id.'&booking_event_id=emergency'));
			}

			$errors = array('Operation' => array('Please select a booked operation'));
		}

		if (isset($_GET['booking_event_id']) || @$_GET['unbooked']) {
			$this->jsVars['eyedraw_iol_classes'] = Yii::app()->params['eyedraw_iol_classes'];
			parent::actionCreate();
		} else {
			$bookings = array();

			if ($api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
				if ($episode = $this->patient->getEpisodeForCurrentSubspecialty()) {
					$bookings = $api->getOpenBookingsForEpisode($episode->id);
				}
			}

			$this->event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
			$this->title = "Please select booking";
			$this->event_tabs = array(
				array(
					'label' => 'Select a booking',
					'active' => true,
				),
			);
			$cancel_url = ($this->episode) ? '/patient/episode/'.$this->episode->id : '/patient/episodes/'.$this->patient->id;
			$this->event_actions = array(
				EventAction::link('Cancel',
					Yii::app()->createUrl($cancel_url),
					null, array('class' => 'button small warning')
				)
			);
			$this->moduleStateCssClass = 'edit';
			$this->processJsVars();
			$this->render('select_event',array(
				'errors' => $errors,
				'bookings' => $bookings,
			));
		}
	}

	public function actionUpdate($id)
	{
		$this->jsVars['eyedraw_iol_classes'] = Yii::app()->params['eyedraw_iol_classes'];

		if (!$event = Event::model()->findByPk($id)) {
			throw new CHttpException(403, 'Invalid event id.');
		}

		$this->showAllergyWarning($event->episode->patient);

		parent::actionUpdate($id);
	}

	public function actionView($id)
	{
		$cs = Yii::app()->getClientScript();
		$cs->registerScript('scr_opnote_view', "opnote_print_url = '" . Yii::app()->createUrl('OphTrOperationnote/Default/print/'.$id) . "';\nmodule_css_path = '" . $this->assetPath . "/css';", CClientScript::POS_READY);
		parent::actionView($id);
	}

	public function actionDelete($id)
	{
		$proclist = Element_OphTrOperationnote_ProcedureList::model()->find('event_id=?',array($id));

		if (parent::actionDelete($id)) {
			if ($proclist && $proclist->booking_event_id) {
				if ($api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
					$api->setOperationStatus($proclist->booking_event_id, 'Scheduled or Rescheduled');
				}
			}
		}
	}

	/**
	 * extends parent functionality to define elements for procedures
	 *
	 * @param string $action
	 * @param null $event_type_id
	 * @param null $event
	 * @return array|BaseEventTypeElement[]
	 *
	 * @see parent::getDefaultElements($action, $event_type_id, $event)
	 */
	public function getDefaultElements($action, $event_type_id=null, $event=null)
	{
		$elements = parent::getDefaultElements($action, $event_type_id, $event);

		// If we're loading the create form and there are procedures pulled from the booking which map to elements
		// then we need to include them in the form
		if ($action == 'create' && empty($_POST)) {
			$proclist = new Element_OphTrOperationnote_ProcedureList;
			$extra_elements = array();

			$new_elements = array(array_shift($elements));
			$generic_index = 0;

			foreach ($proclist->selected_procedures as $procedure) {
				$criteria = new CDbCriteria;
				$criteria->compare('procedure_id',$procedure->id);
				$criteria->order = 'display_order asc';

				$procedureElements = OphTrOperationnote_ProcedureListOperationElement::model()->findAll($criteria);

				foreach ($procedureElements as $element) {
					$element = new $element->element_type->class_name;

					if (!in_array(get_class($element),$extra_elements)) {
						$extra_elements[] = get_class($element);
						$new_elements[] = $element;
					}
				}

				if (count($procedureElements) == 0) {
					$element = new Element_OphTrOperationnote_GenericProcedure;
					$element->proc_id = $procedure->id;
					$element->element_index = $generic_index++;
					$extra_elements[] = "Element_OphTrOperationnote_GenericProcedure";
					$new_elements[] = $element;
				}
			}

			$elements = array_merge($new_elements, $elements);
		}

		/* If an opnote was saved with a procedure in the procedure list but the associated element wasn't saved, include it here */
		if ($action == 'update' && empty($_POST)) {
			$extra_elements = array();
			$new_elements = array(array_shift($elements));

			foreach (Element_OphTrOperationnote_ProcedureList::model()->find('event_id = :event_id',array(':event_id' => $this->event->id))->selected_procedures as $procedure) {
				$criteria = new CDbCriteria;
				$criteria->compare('procedure_id',$procedure->id);
				$criteria->order = 'display_order asc';

				foreach (OphTrOperationnote_ProcedureListOperationElement::model()->findAll($criteria) as $element) {
					$class = $element->element_type->class_name;
					$element = new $element->element_type->class_name;

					if (!$class::model()->find('event_id=?',array($this->event->id))) {
						$extra_elements[] = get_class($element);
						$new_elements[] = $element;
					}
				}
			}

			$elements = array_merge($new_elements, $elements);
		}

		// Procedure list elements need to be shown in the order they were selected, not the default sort order from the element_type
		// TODO: This probably needs replacing with a some better code

		// Get correct order for procedure elements
		if ($this->event) {
			$procedure_list = Element_OphTrOperationnote_ProcedureList::model()->find(
					'event_id = :event_id',
					array(':event_id' => $this->event->id)
			);
			$procedure_classes = array();
			foreach ($procedure_list->procedure_assignments as $procedure_assignment) {
				if ($pl_element = OphTrOperationnote_ProcedureListOperationElement::model()->find('procedure_id = ?', array($procedure_assignment->proc_id))) {
					$procedure_classes[] = $pl_element->element_type->class_name;
				} else {
					$procedure_classes[] = 'Element_OphTrOperationnote_GenericProcedure';
				}
			}

			// Resort procedure elements
			// This code assumes that the elements are grouped into three distinct blocks, with the procedures in the middle
			$sorted_elements = array();
			$index = 0;
			$section = 'top';
			foreach ($elements as $element) {
				if (in_array(get_class($element), $procedure_classes)) {
					$section = 'procedure';
					$index = 1000 + array_search(get_class($element), $procedure_classes);
				} elseif ($section == 'procedure') {
					$section = 'bottom';
					$index = 2000;
				} else {
					$index++;
				}
				while (isset($sorted_elements[$index])) $index++;
				$sorted_elements[$index] = $element;
			}
			ksort($sorted_elements);
			$elements = $sorted_elements;
		}

		return $elements;
	}

	public function actionLoadElementByProcedure()
	{
		if (!$proc = Procedure::model()->findByPk((integer) @$_GET['procedure_id'])) {
			throw new SystemException('Procedure not found: '.@$_GET['procedure_id']);
		}

		$form = new BaseEventTypeCActiveForm;

		$procedureSpecificElements = $this->getProcedureSpecificElements($proc->id);

		foreach ($procedureSpecificElements as $element) {
			$class_name = $element->element_type->class_name;

			$element = new $class_name;

			if (in_array($class_name,array('Element_OphTrOperationnote_Cataract','Element_OphTrOperationnote_Vitrectomy','Element_OphTrOperationnote_Buckle'))) {
				if (!in_array(@$_GET['eye'],array(1,2))) {
					echo "must-select-eye";
					return;
				}
			}

			$element->setDefaultOptions();

			$this->renderPartial(
				'create' . '_' . $element->create_view,
				array('element' => $element, 'data' => array(), 'form' => $form, 'ondemand' => true),
				false, true
			);
		}



		if (count($procedureSpecificElements) == 0) {
			$element = new Element_OphTrOperationnote_GenericProcedure;
			$element->proc_id = $proc->id;

			$element->setDefaultOptions();

			$this->renderPartial(
				'create' . '_' . $element->create_view,
				array('element' => $element, 'data' => array(), 'form' => $form, 'ondemand' => true),
				false, true
			);
		}
	}

	public function actionGetElementsToDelete()
	{
		if (!$proc = Procedure::model()->findByPk((integer) @$_POST['procedure_id'])) {
			throw new SystemException('Procedure not found: '.@$_POST['procedure_id']);
		}

		$procedures = @$_POST['remaining_procedures'] ? explode(',',$_POST['remaining_procedures']) : array();

		$elements = array();

		foreach ($this->getProcedureSpecificElements($proc->id) as $element) {
			if (empty($procedures) || !OphTrOperationnote_ProcedureListOperationElement::model()->find('procedure_id in ('.implode(',',$procedures).') and element_type_id = '.$element->element_type->id)) {
				$elements[] = $element->element_type->class_name;
			}
		}

		die(json_encode($elements));
	}

	public function getProcedureSpecificElements($procedure_id)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('procedure_id',$procedure_id);
		$criteria->order = 'display_order asc';

		return OphTrOperationnote_ProcedureListOperationElement::model()->findAll($criteria);
	}

	public function getAllProcedureElements($action)
	{
		$elements = $this->getDefaultElements($action);
		$current_procedure_elements = array();

		foreach ($elements as $element) {
			if (get_class($element) == 'Element_OphTrOperationnote_GenericProcedure') {
				$current_procedure_elements[] = $element;
			}
			else {
				$element_type = ElementType::model()->find('class_name = ?', array(get_class($element)));
				$procedure_elements = OphTrOperationnote_ProcedureListOperationElement::model()->find('element_type_id = ?', array($element_type->id));
				if ($procedure_elements) {
					$current_procedure_elements[] = $element;
				}
			}
		}

		return $current_procedure_elements;
	}

	public function renderAllProcedureElements($action, $form=false, $data=false)
	{
		$elements = $this->getAllProcedureElements($action);
		$count = count($elements);
		$i = 0;
		$last = false;
		foreach ($elements as $element) {
			if ($count == ($i + 1)) {
				$last = true;
			}
			$this->renderPartial(
				$action . '_' . $element->{$action.'_view'},
				array('element' => $element, 'data' => $data, 'form' => $form, 'last' => $last),
				false, false
			);
			$i++;
		}
	}

	public function actionVerifyprocedure()
	{
		if (!empty($_GET['name'])) {
			$proc = Procedure::model()->findByAttributes(array('term' => $_GET['name']));
			if ($proc) {
				if ($this->procedure_requires_eye($proc->id)) {
					echo "no";
				} else {
					echo "yes";
				}
			}
		} else {
			$i = 0;
			$result = true;
			$procs = array();
			while (isset($_GET['proc'.$i])) {
				if ($this->procedure_requires_eye($_GET['proc'.$i])) {
					$result = false;
					$procs[] = Procedure::model()->findByPk($_GET['proc'.$i])->term;
				}
				$i++;
			}
			if ($result) {
				echo "yes";
			} else {
				echo implode("\n",$procs);
			}
		}
	}

	// returns true if the passed procedure id requires the selection of 'left' or 'right' eye
	public function procedure_requires_eye($procedure_id)
	{
		foreach (OphTrOperationnote_ProcedureListOperationElement::model()->findAll('procedure_id=?',array($procedure_id)) as $plpa) {
			$element_type = ElementType::model()->findByPk($plpa->element_type_id);

			if (in_array($element_type->class_name,array('Element_OphTrOperationnote_Cataract','Element_OphTrOperationnote_Buckle','Element_OphTrOperationnote_Vitrectomy'))) {
				return true;
			}
		}

		return false;
	}

	public function getSelectedEyeForEyedraw()
	{
		$eye = new Eye;

		if (!empty($_POST['Element_OphTrOperationnote_ProcedureList']['eye_id'])) {
			$eye = Eye::model()->findByPk($_POST['Element_OphTrOperationnote_ProcedureList']['eye_id']);
		} else if ($this->event) {
			$eye = Element_OphTrOperationnote_ProcedureList::model()->find('event_id=?',array($this->event->id))->eye;
		} else if (!empty($_GET['eye'])) {
			$eye = Eye::model()->findByPk($_GET['eye']);
		} else if ($this->action->id == 'create') {
			// Get the procedure list and eye from the most recent booking for the episode of the current user's subspecialty
			if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
				throw new SystemException('Patient not found: '.@$_GET['patient_id']);
			}

			if ($episode = $patient->getEpisodeForCurrentSubspecialty()) {
				if ($api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
					if ($booking = $api->getMostRecentBookingForEpisode($patient, $episode)) {
						$eye = $booking->operation->eye;
					}
				}
			}
		}

		if ($eye->name == 'Both') {
			$eye = Eye::model()->find('name=?',array('Right'));
		}

		return $eye;
	}
}
