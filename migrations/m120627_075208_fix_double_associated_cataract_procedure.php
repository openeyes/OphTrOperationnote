<?php

class m120627_075208_fix_double_associated_cataract_procedure extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:class_name', array(':eventTypeId'=>$event_type['id'],':class_name'=>'ElementExtracapsularCataractExtraction'))->queryRow();

		/*$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code=:snomed_code', array(':snomed_code'=>'13793006'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id='.$proc['id'].' and element_type_id='.$element_type['id']);*/
	}

	public function down()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:class_name', array(':eventTypeId'=>$event_type['id'],':class_name'=>'ElementExtracapsularCataractExtraction'))->queryRow();

		/*$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code=:snomed_code', array(':snomed_code'=>'13793006'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id'],'display_order'=>1));*/
	}
}
