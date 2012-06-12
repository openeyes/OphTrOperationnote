<?php

class m120612_103357_add_report_field_to_buckle_and_cataract_elements extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_buckle','report','varchar(4096) COLLATE utf8_bin NOT NULL');
		$this->addColumn('et_ophtroperationnote_cataract','report2','varchar(4096) COLLATE utf8_bin NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophtroperationnote_cataract','report2');
		$this->dropColumn('et_ophtroperationnote_buckle','report');
	}
}
