<?php

class m120809_134618_renumber_more_opnote_element_display_orders extends CDbMigration
{
	public function up()
	{
		$event_type_id = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryScalar();
		$this->update('element_type', array('display_order' => new CDbExpression('display_order * 10')),"display_order < 10 and event_type_id = ".$event_type_id);
	}

	public function down()
	{
	}

}
