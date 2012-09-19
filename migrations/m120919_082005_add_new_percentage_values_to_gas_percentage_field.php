<?php

class m120919_082005_add_new_percentage_values_to_gas_percentage_field extends CDbMigration
{
	public function up()
	{
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>2),'id=1');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>3),'id=2');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>4),'id=3');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>6),'id=4');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>7),'id=5');
		$this->insert('et_ophtroperationnote_gas_percentage',array('value'=>12,'display_order'=>1));
		$this->insert('et_ophtroperationnote_gas_percentage',array('value'=>25,'display_order'=>5));
	}

	public function down()
	{
		$this->delete('et_ophtroperationnote_gas_percentage','value=12');
		$this->delete('et_ophtroperationnote_gas_percentage','value=25');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>1),'id=1');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>2),'id=2');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>3),'id=3');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>4),'id=4');
		$this->update('et_ophtroperationnote_gas_percentage',array('display_order'=>5),'id=5');
	}
}
