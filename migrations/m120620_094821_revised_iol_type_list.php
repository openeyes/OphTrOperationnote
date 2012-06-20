<?php

class m120620_094821_revised_iol_type_list extends CDbMigration
{
	public function up()
	{
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'SN60WF'),'id=1');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'MA60AC'),'id=2');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'SA60AT'),'id=3');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'MA60MA'),'id=4');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'CZ70BD'),'id=5');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'MTA3UO'),'id=6');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'MTA4UO'),'id=7');

		$this->addColumn('et_ophtroperationnote_cataract_iol_type','private','tinyint(1) not null default 0');
	}

	public function down()
	{
		$this->dropColumn('et_ophtroperationnote_cataract_iol_type','private');

		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'SA60AT'),'id=1');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'SN60WF'),'id=2');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'MA60MA'),'id=3');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'MA60AC'),'id=4');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'CZ70BD'),'id=5');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'MTA3UO'),'id=6');
		$this->update('et_ophtroperationnote_cataract_iol_type',array('name'=>'MTA4UO'),'id=7');
	}
}
