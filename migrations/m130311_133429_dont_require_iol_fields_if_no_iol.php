<?php

class m130311_133429_dont_require_iol_fields_if_no_iol extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','iol_position_id','int(10) unsigned NULL DEFAULT 1');
		$this->alterColumn('et_ophtroperationnote_cataract','iol_type_id','int(10) unsigned NULL');
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','iol_position_id','int(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophtroperationnote_cataract','iol_type_id','int(10) unsigned NOT NULL');
	}
}
