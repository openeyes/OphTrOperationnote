<?php

class m120816_094302_change_fife_differences_to_use_settings_rather_than_a_config_value extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		foreach (array('ElementAnaesthetic','ElementPersonnel','ElementPreparation') as $class_name) {
			$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('class_name=:class_name and event_type_id=:event_type_id', array(':class_name'=>$class_name,':event_type_id'=>$event_type['id']))->queryRow();
			$this->insert('setting_metadata',array('element_type_id'=>$element_type['id'],'display_order'=>1,'field_type_id'=>1,'key'=>'fife','name'=>'Fife','default_value'=>0));
		}
	}

	public function down()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		foreach (array('ElementAnaesthetic','ElementPersonnel','ElementPreparation') as $class_name) {
			$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('class_name=:class_name and event_type_id=:event_type_id', array(':class_name'=>$class_name,':event_type_id'=>$event_type['id']))->queryRow();
			$this->delete('setting_metadata',"element_type_id=".$element_type['id']." and `key`='fife'");
		}
	}
}
