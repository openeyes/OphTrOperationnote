<?php

class m120919_065814_make_tamponade_volume_field_nullable extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('et_ophtroperationnote_tamponade','gas_volume_id',"int(10) unsigned NULL DEFAULT '1'");
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_tamponade','gas_volume_id',"int(10) unsigned NOT NULL DEFAULT '1'");
	}
}
