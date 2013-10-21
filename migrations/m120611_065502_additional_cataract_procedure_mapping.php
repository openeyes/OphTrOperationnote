<?php

class m120611_065502_additional_cataract_procedure_mapping extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:event_type_id',array(':name'=>'Cataract',':event_type_id'=>$event_type['id']))->queryRow();
		/*$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code=:snomed_code',array(':snomed_code'=>'13793006'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));*/
	}

	public function down()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:event_type_id',array(':name'=>'Cataract',':event_type_id'=>$event_type['id']))->queryRow();
		/*$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code=:snomed_code',array(':snomed_code'=>'13793006'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id'].' and procedure_id='.$proc['id']);*/
	}
}
