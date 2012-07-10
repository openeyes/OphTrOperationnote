<?php

class m120710_062419_new_vr_fields extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_membrane_peel','comments','varchar(1024) COLLATE utf8_bin NOT NULL');
		$this->addColumn('et_ophtroperationnote_buckle','comments','varchar(1024) COLLATE utf8_bin NOT NULL');
		$this->addColumn('et_ophtroperationnote_vitrectomy','eyedraw','text COLLATE utf8_bin NOT NULL');
		$this->addColumn('et_ophtroperationnote_vitrectomy','comments','varchar(1024) COLLATE utf8_bin NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophtroperationnote_membrane_peel','comments');
		$this->dropColumn('et_ophtroperationnote_buckle','comments');
		$this->dropColumn('et_ophtroperationnote_vitrectomy','eyedraw');
		$this->dropColumn('et_ophtroperationnote_vitrectomy','comments');
	}
}
