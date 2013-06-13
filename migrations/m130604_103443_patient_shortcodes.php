<?php

class m130604_103443_patient_shortcodes extends CDbMigration
{
	public function up()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));

		$event_type->registerShortcode('opr','getLetterProcedures','Operations carried out');
		$event_type->registerShortcode('ops','getLetterProceduresSNOMED','Operations carried out with SNOMED terms');
	}

	public function down()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));

		$this->delete('patient_shortcode','event_type_id=:etid',array(':etid'=>$event_type->id));
	}
}
