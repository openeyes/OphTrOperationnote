<?php

class m130116_100158_fluorescein_procedure_element extends CDbMigration
{
	public function up()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Fluorescein angiography','class_name'=>'ElementFluorescein','display_order'=>20));
		$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementFluorescein'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Fluorescein angiography','172581008'));
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc->id,'element_type_id'=>$element_type->id));
	}

	public function down()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementFluorescein'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Fluorescein angiography','172581008'));
		$this->delete('et_ophtroperationnote_procedure_element',"procedure_id = $proc->id and element_type_id = $element_type->id");
		$this->delete('element_type',"id=$element_type->id");
	}
}
