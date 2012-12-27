<?php

class m121227_095138_soft_deletion_of_postop_drugs extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_postop_drug','deleted','tinyint(1) unsigned NOT NULL DEFAULT 0');
	}

	public function down()
	{
		$this->dropColumn('et_ophtroperationnote_postop_drug','deleted');
	}
}
