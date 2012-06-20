<?php

class m120620_092825_anaesthetic_given_by_default extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('et_ophtroperationnote_anaesthetic','anaesthetist_id',"int(10) unsigned NOT NULL DEFAULT '4'");
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_anaesthetic','anaesthetist_id',"int(10) unsigned NOT NULL DEFAULT '1'");
	}
}
