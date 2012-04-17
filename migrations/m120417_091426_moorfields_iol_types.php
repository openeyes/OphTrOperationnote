<?php

class m120417_091426_moorfields_iol_types extends CDbMigration
{
	public function up()
	{
		$this->delete('et_ophtroperationnote_cataract_iol_type');

		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>1,'name'=>'SA60AT','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>2,'name'=>'SN60WF','display_order'=>2));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>3,'name'=>'MA60MA','display_order'=>3));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>4,'name'=>'MA60AC','display_order'=>4));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>5,'name'=>'CZ70BD','display_order'=>5));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>6,'name'=>'MTA3UO','display_order'=>6));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>7,'name'=>'MTA4UO','display_order'=>7));
	}

	public function down()
	{
		$this->delete('et_ophtroperationnote_cataract_iol_type');

		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>1,'name'=>'Type 1','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>2,'name'=>'Type 2','display_order'=>2));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>3,'name'=>'Type 3','display_order'=>3));
	}
}
