<?php

class m120706_133551_increase_length_of_cataract_eyedraw_field extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','eyedraw','text COLLATE utf8_bin NOT NULL');
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','eyedraw','varchar(4096) COLLATE utf8_bin NOT NULL');
	}
}
