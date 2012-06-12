<?php

class m120531_134618_renumber_opnote_element_display_orders extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		for ($i=1;$i<=6;$i++) {
			$this->update('element_type',array('display_order'=>($i*10)),"display_order=$i and event_type_id=".$event_type['id']);
		}
	}

	public function down()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		for ($i=1;$i<=6;$i++) {
			$this->update('element_type',array('display_order'=>$i),"display_order=".($i*10)." and event_type_id=".$event_type['id']);
		}
	}
}
