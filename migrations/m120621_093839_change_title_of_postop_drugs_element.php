<?php

class m120621_093839_change_title_of_postop_drugs_element extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and class_name=:class_name', array(':event_type_id'=>$event_type['id'],':class_name'=>'ElementPostOpDrugs'))->queryRow();

		$this->update('element_type',array('name'=>'Per-operative drugs'),'id='.$element_type['id']);
	}

	public function down()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and class_name=:class_name', array(':event_type_id'=>$event_type['id'],':class_name'=>'ElementPostOpDrugs'))->queryRow();

		$this->update('element_type',array('name'=>'Post-op drugs'),'id='.$element_type['id']);
	}
}
