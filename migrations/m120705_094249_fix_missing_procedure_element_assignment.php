<?php

class m120705_094249_fix_missing_procedure_element_assignment extends CDbMigration
{
	public function up()
	{
		/*$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term=:term',array(':term'=>'Other procedure on eyelid'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('class_name=:class_name',array(':class_name'=>'ElementOperationOnEyelid'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));*/
	}

	public function down()
	{
		/*$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term=:term',array(':term'=>'Other procedure on eyelid'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('class_name=:class_name',array(':class_name'=>'ElementOperationOnEyelid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id='.$proc['id'].' and element_type_id='.$element_type['id']);*/
	}
}
