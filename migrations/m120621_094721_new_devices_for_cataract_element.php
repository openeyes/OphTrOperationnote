<?php

class m120621_094721_new_devices_for_cataract_element extends CDbMigration
{
	public function up()
	{
		$this->insert('operative_device',array('name'=>'Miochol'));
		$this->insert('operative_device',array('name'=>'Ocucoat'));
	}

	public function down()
	{
		$this->delete('operative_device',"name='Miochol'");
		$this->delete('operative_device',"name='Ocucoat'");
	}
}
