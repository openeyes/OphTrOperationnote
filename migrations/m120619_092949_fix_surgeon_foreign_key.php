<?php

class m120619_092949_fix_surgeon_foreign_key extends CDbMigration
{
	public function up()
	{
		$this->dropForeignKey('et_ophtroperationnote_sur_surgeon_id_fk','et_ophtroperationnote_surgeon');
		$this->addForeignKey('et_ophtroperationnote_sur_surgeon_id_fk','et_ophtroperationnote_surgeon','surgeon_id','user','id');
	}

	public function down()
	{
		$this->dropForeignKey('et_ophtroperationnote_sur_surgeon_id_fk','et_ophtroperationnote_surgeon');
		$this->addForeignKey('et_ophtroperationnote_sur_surgeon_id_fk','et_ophtroperationnote_surgeon','surgeon_id','consultant','id');
	}
}
