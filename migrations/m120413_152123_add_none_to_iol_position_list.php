<?php

class m120413_152123_add_none_to_iol_position_list extends CDbMigration
{
	public function up()
	{
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>2),'id=1');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>3),'id=2');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>4),'id=3');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>5),'id=4');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>6),'id=5');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>7),'id=6');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>8),'id=7');

		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>8,'name'=>'None','display_order'=>1));
	}

	public function down()
	{
		$this->delete('et_ophtroperationnote_cataract_iol_position','id=8');

		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>1),'id=1');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>2),'id=2');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>3),'id=3');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>4),'id=4');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>5),'id=5');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>6),'id=6');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>7),'id=7');
	}
}
