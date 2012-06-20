<?php

class m120620_100817_adjust_cataract_default_details_text extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','report',"varchar(4096) COLLATE utf8_bin NOT NULL DEFAULT 'Continuous Circular Capsulorrhexis
Hydrodissection
Phakoemulsification of lens nucleus
Aspiration of soft lens matter
Viscoelastic removed'");
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_cataract','report',"varchar(4096) COLLATE utf8_bin NOT NULL DEFAULT 'Continuous Circular CapsulorrhexisHydrodissection
Phakoemulsification of lens nucleus
Aspiration of soft lens matter'");
	}
}
