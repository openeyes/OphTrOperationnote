<?php

class m130116_102441_fix_fluorescein_default extends CDbMigration
{
	public function up()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementFluorescein'));
		$this->update('element_type',array('default'=>0),'id='.$element_type->id);
	}

	public function down()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
		$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementFluorescein'));
		$this->update('element_type',array('default'=>1),'id='.$element_type->id);
	}
}
