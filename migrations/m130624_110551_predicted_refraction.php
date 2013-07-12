<?php

class m130624_110551_predicted_refraction extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_cataract','predicted_refraction','decimal(4,2) NOT NULL DEFAULT 0');
	}

	public function down()
	{
		$this->dropColumn('et_ophtroperationnote_cataract','predicted_refraction');
	}
}
