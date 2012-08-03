<?php

class DefaultController extends BaseEventTypeController {
	public function actionCreate() {
		parent::actionCreate();
	}

	public function actionUpdate($id) {
		parent::actionUpdate($id);
	}

	public function actionView($id) {
		parent::actionView($id);
	}

	public function actionPrint($id) {
		OECClientScript::registerCssFile($this->cssPath.'/print.js');
		return parent::actionPrint($id);
	}

	public function getDefaultElements($action, $event_type_id=false, $event=false) {
		$elements = parent::getDefaultElements($action, $event_type_id, $event);

		// If we're loading the create form and there are procedures pulled from the booking which map to elements
		// then we need to include them in the form
		if ($action == 'create' && empty($_POST)) {
			$proclist = new ElementProcedureList;
			$extra_elements = array();

			$new_elements = array(array_shift($elements));

			foreach ($proclist->selected_procedures as $procedure) {
				$criteria = new CDbCriteria;
				$criteria->compare('procedure_id',$procedure->id);
				$criteria->order = 'display_order asc';

				foreach (ProcedureListOperationElement::model()->findAll($criteria) as $element) {
					$element = new $element->element_type->class_name;

					if (!in_array(get_class($element),$extra_elements)) {
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
		$procedure_list = ElementProcedureList::model()->find(
				'event_id = :event_id',
				array(':event_id' => $this->event->id)
		);
		if($procedure_list) {
			$procedure_classes = array();
			foreach($procedure_list->procedure_assignments as $procedure_assignment) {
				$procedure_classes[] = ProcedureListOperationElement::model()->find('procedure_id = ?', array($procedure_assignment->proc_id))->element_type->class_name;
			}
			
			// Resort procedure elements
			// This code assumes that the elements are grouped into three distinct blocks, with the procedures in the middle
			$sorted_elements = array();
			$index = 0;
			$section = 'top';
			foreach($elements as $element) {
				if(in_array(get_class($element), $procedure_classes)) {
					$section = 'procedure';
					$index = 1000 + array_search(get_class($element), $procedure_classes);
				} else if($section == 'procedure') {
					$section = 'bottom';
					$index = 2000;
				} else {
					$index++;
				}
				Yii::log($index);
				$sorted_elements[$index] = $element;
			}
			ksort($sorted_elements);
			$elements = $sorted_elements;
		}

		return $elements;
	}

	public function actionLoadElementByProcedure() {
		if (!$proc = Procedure::model()->findByPk((integer)@$_GET['procedure_id'])) {
			throw new SystemException('Procedure not found: '.@$_GET['procedure_id']);
		}

		$form = new BaseEventTypeCActiveForm;

		foreach ($this->getProcedureSpecificElements($proc->id) as $element) {
			$element = new $element->element_type->class_name;

			$this->renderPartial(
				'create' . '_' . $element->create_view,
				array('element' => $element, 'data' => array(), 'form' => $form, 'ondemand' => true),
				false, true
			);
		}
	}

	public function actionGetElementsToDelete() {
		if (!$proc = Procedure::model()->findByPk((integer)@$_POST['procedure_id'])) {
			throw new SystemException('Procedure not found: '.@$_POST['procedure_id']);
		}

		$procedures = @$_POST['remaining_procedures'] ? explode(',',$_POST['remaining_procedures']) : array();

		$elements = array();

		foreach ($this->getProcedureSpecificElements($proc->id) as $element) {
			if (empty($procedures) || !ProcedureListOperationElement::model()->find('procedure_id in ('.implode(',',$procedures).') and element_type_id = '.$element->element_type->id)) {
				$elements[] = $element->element_type->class_name;
			}
		}

		die(json_encode($elements));
	}

	public function getProcedureSpecificElements($procedure_id) {
		$criteria = new CDbCriteria;
		$criteria->compare('procedure_id',$procedure_id);
		$criteria->order = 'display_order asc';

		return ProcedureListOperationElement::model()->findAll($criteria);
	}
	
	public function getAllProcedureElements($action){
		$elements = $this->getDefaultElements($action);
		$current_procedure_elements = array();
		
		foreach($elements as $element){
			$element_type = ElementType::model()->find('class_name = ?', array(get_class($element)));
			$procedure_elements = ProcedureListOperationElement::model()->find('element_type_id = ?', array($element_type->id));
			if($procedure_elements){
				$current_procedure_elements[] = $element;
			}
		}
		
		return $current_procedure_elements;
	}
	
	public function renderAllProcedureElements($action, $form=false, $data=false) {
		$elements = $this->getAllProcedureElements($action);
		$count = count($elements);
		$i = 0;
		$last = false;
		foreach ($elements as $element) {
			if($count == ($i + 1)){
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
}
