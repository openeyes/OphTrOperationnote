<?php

class m120413_152759_cataract_iol_power_field extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_cataract','iol_power','varchar(5) COLLATE utf8_bin NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophtroperationnote_cataract','iol_power');
	}
}
