<?php

class m120517_095948_fix_procedurelist_table_foreign_key extends CDbMigration
{
	public function up()
	{
		$this->dropForeignKey('et_ophtroperationnote_plpa_operation_fk','et_ophtroperationnote_procedurelist_procedure_assignment');
		$this->addForeignKey('et_ophtroperationnote_plpa_proclist_fk','et_ophtroperationnote_procedurelist_procedure_assignment','procedurelist_id','et_ophtroperationnote_procedurelist','id');
	}

	public function down()
	{
		$this->dropForeignKey('et_ophtroperationnote_plpa_proclist_fk','et_ophtroperationnote_procedurelist_procedure_assignment');
		$this->addForeignKey('et_ophtroperationnote_plpa_operation_fk','et_ophtroperationnote_procedurelist_procedure_assignment','procedurelist_id','element_operation','id');
	}
}
