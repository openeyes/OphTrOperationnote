<?php

class m130318_113609_display_order_field_on_postop_drugs extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_postop_drug','display_order','int(10) unsigned NOT NULL DEFAULT 0');
	}

	public function down()
	{
		$this->dropColumn('et_ophtroperationnote_postop_drug','display_order');
	}
}
