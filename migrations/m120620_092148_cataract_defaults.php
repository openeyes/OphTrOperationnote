<?php

class m120620_092148_cataract_defaults extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','iol_position_id','int(10) unsigned NOT NULL DEFAULT 1');
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','iol_position_id','int(10) unsigned NOT NULL');
	}
}
