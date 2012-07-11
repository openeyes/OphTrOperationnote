<?php

class m120711_141118_default_cataract_field_values extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','incision_site_id','int(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophtroperationnote_cataract','length',"varchar(5) COLLATE utf8_bin NOT NULL DEFAULT '2.8'");
		$this->alterColumn('et_ophtroperationnote_cataract','meridian',"varchar(5) COLLATE utf8_bin NOT NULL DEFAULT '180'");
		$this->alterColumn('et_ophtroperationnote_cataract','incision_type_id','int(10) unsigned NOT NULL DEFAULT 1');
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','incision_site_id','int(10) unsigned NOT NULL DEFAULT 0');
		$this->alterColumn('et_ophtroperationnote_cataract','length',"varchar(5) COLLATE utf8_bin NOT NULL");
		$this->alterColumn('et_ophtroperationnote_cataract','meridian',"varchar(5) COLLATE utf8_bin NOT NULL");
		$this->alterColumn('et_ophtroperationnote_cataract','incision_type_id','int(10) unsigned NOT NULL DEFAULT 0');
	}
}
