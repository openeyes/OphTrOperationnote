<?php

class m120917_142345_add_both_to_element_table_eye_for_procedurelist_element extends CDbMigration
{
	public function up()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementProcedureList'));

		$this->update('element_type_eye',array('display_order'=>3),'element_type_id='.$element_type->id.' and eye_id=1');
		$this->insert('element_type_eye',array('element_type_id'=>$element_type->id,'eye_id'=>3,'display_order'=>2,'created_user_id'=>1,'last_modified_user_id'=>1));
	}

	public function down()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementProcedureList'));

		$this->delete('element_type_eye','element_type_id='.$element_type->id.' and eye_id=3');
		$this->update('element_type_eye',array('display_order'=>2),'element_type_id='.$element_type->id.' and eye_id=1');
	}
}
