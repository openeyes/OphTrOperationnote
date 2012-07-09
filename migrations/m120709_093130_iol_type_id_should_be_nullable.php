<?php

class m120709_093130_iol_type_id_should_be_nullable extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','iol_type_id','int(10) unsigned NULL');
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','iol_type_id','int(10) unsigned NOT NULL');
	}
}
