<?php

class m140207_110327_remove_soft_deletion_from_models_that_dont_need_it extends CDbMigration
{
	public $tables = array(
		'et_ophtroperationnote_anaesthetic',
		'et_ophtroperationnote_buckle',
		'et_ophtroperationnote_cataract',
		'et_ophtroperationnote_comments',
		'et_ophtroperationnote_genericprocedure',
		'et_ophtroperationnote_membrane_peel',
		'et_ophtroperationnote_personnel',
		'et_ophtroperationnote_postop_drugs',
		'et_ophtroperationnote_preparation',
		'et_ophtroperationnote_procedurelist',
		'et_ophtroperationnote_surgeon',
		'et_ophtroperationnote_tamponade',
		'et_ophtroperationnote_vitrectomy',
		'ophtroperationnote_anaesthetic_anaesthetic_agent',
		'ophtroperationnote_anaesthetic_anaesthetic_complication',
		'ophtroperationnote_cataract_complication',
		'ophtroperationnote_cataract_operative_device',
		'ophtroperationnote_gas_percentage',
		'ophtroperationnote_postop_drugs_drug',
		'ophtroperationnote_postop_site_subspecialty_drug',
		'ophtroperationnote_procedure_element',
		'ophtroperationnote_procedurelist_procedure_assignment',
		'ophtroperationnote_site_subspecialty_postop_instructions',
	);

	public function up()
	{
		foreach ($this->tables as $table) {
			$this->dropColumn($table,'deleted');
			$this->dropColumn($table.'_version','deleted');

			$this->dropForeignKey("{$table}_aid_fk",$table."_version");
		}
	}

	public function down()
	{
		foreach ($this->tables as $table) {
			$this->addColumn($table,'deleted','tinyint(1) unsigned not null');
			$this->addColumn($table."_version",'deleted','tinyint(1) unsigned not null');

			$this->addForeignKey("{$table}_aid_fk",$table."_version","id",$table,"id");
		}
	}
}
