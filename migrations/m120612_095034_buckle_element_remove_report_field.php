<?php

class m120612_095034_buckle_element_remove_report_field extends CDbMigration
{
	public function up()
	{
		$this->dropColumn('et_ophtroperationnote_buckle','report');
	}

	public function down()
	{
		$this->addColumn('et_ophtroperationnote_buckle','report','varchar(4096) COLLATE utf8_bin NOT NULL');
	}
}
