<?php

class m120413_153102_remove_vision_blue_field extends CDbMigration
{
	public function up()
	{
		$this->dropColumn('et_ophtroperationnote_cataract','vision_blue');
	}

	public function down()
	{
		$this->addColumn('et_ophtroperationnote_cataract','vision_blue',"tinyint(1) unsigned NOT NULL DEFAULT '1'");
	}
}
