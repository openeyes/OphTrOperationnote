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
	/* @var Element_OphTrOperationbooking_Operation operation that this note is for when creating */
	protected $booking_operation;
	/* @var boolean - indicates if this note is for an unbooked procedure or not when creating */
	protected $unbooked = false;

	protected function beforeAction($action)
	{
		$this->assetPath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.'.$this->getModule()->name.'.assets'), false, -1, YII_DEBUG);
		Yii::app()->clientScript->registerScriptFile($this->assetPath.'/js/eyedraw.js');

		return parent::beforeAction($action);
	}

	/**
	 * Set flash message for patient allergies
	 */
	protected function showAllergyWarning()
	{
		if ($this->patient->no_allergies_date) {
			Yii::app()->user->setFlash('info.prescription_allergy', $this->patient->getAllergiesString());
		}
		else {
			Yii::app()->user->setFlash('warning.prescription_allergy', $this->patient->getAllergiesString());
		}
	}

	protected function getEventElements()
	{
		if ($this->event) {
			return $this->event->getElements();
			//TODO: check for missing elements for procedures
		}
		else {
			$elements = $this->event_type->getDefaultElements();
			if ($this->booking_operation) {
				// need to add procedure elements for the booking operation
				$generic_index = 0;

				$api = Yii::app()->moduleAPI->get('OphTrOperationbooking');
				$extra_elements = array();
				$new_elements = array(array_shift($elements));

				foreach ($api->getProceduresForOperation($this->booking_operation->event_id) as $proc) {
					$criteria = new CDbCriteria;
					$criteria->compare('procedure_id',$proc->id);
					$criteria->order = 'display_order asc';

					$procedure_elements = OphTrOperationnote_ProcedureListOperationElement::model()->findAll($criteria);

					foreach ($procedure_elements as $element) {
						$kls = $element->element_type->class_name;

						if (!in_array($kls,$extra_elements)) {
							$extra_elements[] = $kls;
							$new_elements[] = new $kls;
						}
					}

					if (count($procedure_elements) == 0) {
						// no specific element for procedure, use generic
						$element = new Element_OphTrOperationnote_GenericProcedure;
						$element->proc_id = $proc->id;
						$element->element_index = $generic_index++;
						$extra_elements[] = "Element_OphTrOperationnote_GenericProcedure";
						$new_elements[] = $element;
					}
				}
				return array_merge($new_elements, $elements);
			}
			else {
				return $elements;
			}
		}
	}

	protected function setElementDefaultOptions($element, $action)
	{
		if ($action == 'create' && $this->booking_operation
			&& get_class($element) == 'Element_OphTrOperationnote_ProcedureList') {

			$procedures = array();

			$api = Yii::app()->moduleAPI->get('OphTrOperationbooking');
			foreach ($api->getProceduresForOperation($this->booking_operation->event_id) as $proc) {
				$procedures[] = $proc;
			}
			$element->procedures = $procedures;
			$element->eye = $api->getEyeForOperation($this->booking_operation->event_id);
		}
	}


	/**
	 * Edit actions common initialisation
	 */
	protected function initEdit()
	{
		$this->showAllergyWarning();
		$this->jsVars['eyedraw_iol_classes'] = Yii::app()->params['eyedraw_iol_classes'];
		$this->moduleStateCssClass = 'edit';
	}

	/**
	 * Set up the controller properties for booking relationship
	 *
	 * @throws Exception
	 */
	protected function initActionCreate()

	{
		parent::initActionCreate();

		if (isset($_GET['booking_event_id'])) {
			if (!$api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
				throw new Exception('invalid request for booking event');
			}
			if (!$this->booking_operation = $api->getOperationForEvent($_GET['booking_event_id'])) {
				throw new Exception('booking event not found');
			}
		}
		elseif (isset($_GET['unbooked'])) {
			$this->unbooked = true;
		}

		$this->initEdit();
	}

	/**
	 * Call the core edit action initialisation
	 *
	 * (non-phpdoc)
	 * @see parent::initActionUpdate()
	 */
	protected function initActionUpdate()
	{
		parent::initActionUpdate();
		$this->initEdit();
	}

	/**
	 * Handle the selection of a booking for creating an op note
	 *
	 * (non-phpdoc)
	 * @see parent::actionCreate()
	 */
	public function actionCreate()
	{
		$errors = array();

		if (!empty($_POST)) {
			if (preg_match('/^booking([0-9]+)$/',@$_POST['SelectBooking'],$m)) {
				$this->redirect(array('/OphTrOperationnote/Default/create?patient_id='.$this->patient->id.'&booking_event_id='.$m[1]));
			} elseif (@$_POST['SelectBooking'] == 'emergency') {
				$this->redirect(array('/OphTrOperationnote/Default/create?patient_id='.$this->patient->id.'&unbooked=1'));
			}

			$errors = array('Operation' => array('Please select a booked operation'));
		}

		if ($this->booking_operation || $this->unbooked) {
			parent::actionCreate();
		} else {
			// set up form for selecting a booking for the Op note
			$bookings = array();

			if ($api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
				$bookings = $api->getOpenBookingsForEpisode($this->episode->id);
			}

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

			$this->render('select_event',array(
				'errors' => $errors,
				'bookings' => $bookings,
			));
		}
	}

	public function actionView($id)
	{
		//TODO: stick this in jsvars?
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
	 * suppress default behaviour
	 *
	 * @return array
	 */
	public function getOptionalElements()
	{
		return array();
	}

	/**
	 *
	 */
	public function getElements()
	{
		$elements = parent::getElements();
		// sort the procedure elements based on the selection order in the procedurelist element
		$proc_list = null;

		$elements_by_class = array();

		foreach ($elements as $element) {
			$kls = get_class($element);
			error_log($kls);
			if ($kls == "Element_OphTrOperationnote_ProcedureList") {
				$proc_list = $element;
			}
			if (isset($elements_by_class[$kls])) {
				$elements_by_class[$kls][] = $element;
			}
			else {
				$elements_by_class[get_class($element)] = array($element);
			}
		}

		if ($proc_list === null) {
			return $elements;
		}

		// construct list of procedure element types in the right order
		$procedure_classes = array();
		foreach ($proc_list->procedures as $procedure) {
			error_log($procedure->term);
			$criteria = new CDbCriteria;
			$criteria->compare('procedure_id',$procedure->id);
			$criteria->order = 'display_order asc';

			if ($proc_els = OphTrOperationnote_ProcedureListOperationElement::model()->findAll($criteria)) {
				foreach ($proc_els as $proc_el) {
					$kls = $proc_el->element_type->class_name;
					$procedure_classes[] = shift($elements_by_class[$kls]);
				}
			}
			else {
				$keep = array();
				if (isset($elements_by_class['Element_OphTrOperationnote_GenericProcedure'])) {
					foreach ($elements_by_class['Element_OphTrOperationnote_GenericProcedure'] as $el) {
						if ($el->proc_id == $procedure->id) {
							$procedure_classes[] = $el;
						}
						else {
							$keep[] = $el;
						}
					}
					$elements_by_class['Element_OphTrOperationnote_GenericProcedure'] = $keep;
				}
			}
		}
		$sorted = array();
		$procs_found = false;
		foreach ($elements as $el) {
			if (count($elements_by_class[get_class($el)])) {
				$sorted[] = $el;
			}
			elseif (!$procs_found) {
				$sorted = array_merge($sorted, $procedure_classes);
				$procs_found = true;
			}
		}
		return $sorted;
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
	/*
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
		/*
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
	*/

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

			// FIXME: define a property on the element to indicate that specific eye is required
			if (in_array($class_name,array('Element_OphTrOperationnote_Cataract','Element_OphTrOperationnote_Vitrectomy','Element_OphTrOperationnote_Buckle'))) {
				if (!in_array(@$_GET['eye'],array(Eye::LEFT,Eye::RIGHT))) {
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
