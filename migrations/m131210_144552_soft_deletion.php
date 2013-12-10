<?php

class m131210_144552_soft_deletion extends CDbMigration
{
	public function up()
	{
		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_agent','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_agent_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complication','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complication_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complications','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complications_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_buckle_drainage_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_buckle_drainage_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_complication','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_complication_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_complications','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_complications_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_incision_site','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_incision_site_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_incision_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_incision_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_iol_position','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_iol_position_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_iol_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_iol_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_operative_device','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_cataract_operative_device_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_percentage','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_percentage_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_volume','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_volume_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gauge','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gauge_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_postop_drugs_drug','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_postop_drugs_drug_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_postop_site_subspecialty_drug','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_postop_site_subspecialty_drug_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_preparation_intraocular_solution','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_preparation_intraocular_solution_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_preparation_skin_preparation','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_preparation_skin_preparation_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_procedure_element','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_procedure_element_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_procedurelist_procedure_assignment','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_procedurelist_procedure_assignment_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_site_subspecialty_postop_instructions','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_site_subspecialty_postop_instructions_version','deleted','tinyint(1) unsigned not null');
	}

	public function down()
	{
		$this->dropColumn('ophtroperationnote_anaesthetic_anaesthetic_agent','deleted');
		$this->dropColumn('ophtroperationnote_anaesthetic_anaesthetic_agent_version','deleted');
		$this->dropColumn('ophtroperationnote_anaesthetic_anaesthetic_complication','deleted');
		$this->dropColumn('ophtroperationnote_anaesthetic_anaesthetic_complication_version','deleted');
		$this->dropColumn('ophtroperationnote_anaesthetic_anaesthetic_complications','deleted');
		$this->dropColumn('ophtroperationnote_anaesthetic_anaesthetic_complications_version','deleted');
		$this->dropColumn('ophtroperationnote_buckle_drainage_type','deleted');
		$this->dropColumn('ophtroperationnote_buckle_drainage_type_version','deleted');
		$this->dropColumn('ophtroperationnote_cataract_complication','deleted');
		$this->dropColumn('ophtroperationnote_cataract_complication_version','deleted');
		$this->dropColumn('ophtroperationnote_cataract_complications','deleted');
		$this->dropColumn('ophtroperationnote_cataract_complications_version','deleted');
		$this->dropColumn('ophtroperationnote_cataract_incision_site','deleted');
		$this->dropColumn('ophtroperationnote_cataract_incision_site_version','deleted');
		$this->dropColumn('ophtroperationnote_cataract_incision_type','deleted');
		$this->dropColumn('ophtroperationnote_cataract_incision_type_version','deleted');
		$this->dropColumn('ophtroperationnote_cataract_iol_position','deleted');
		$this->dropColumn('ophtroperationnote_cataract_iol_position_version','deleted');
		$this->dropColumn('ophtroperationnote_cataract_iol_type','deleted');
		$this->dropColumn('ophtroperationnote_cataract_iol_type_version','deleted');
		$this->dropColumn('ophtroperationnote_cataract_operative_device','deleted');
		$this->dropColumn('ophtroperationnote_cataract_operative_device_version','deleted');
		$this->dropColumn('ophtroperationnote_gas_percentage','deleted');
		$this->dropColumn('ophtroperationnote_gas_percentage_version','deleted');
		$this->dropColumn('ophtroperationnote_gas_type','deleted');
		$this->dropColumn('ophtroperationnote_gas_type_version','deleted');
		$this->dropColumn('ophtroperationnote_gas_volume','deleted');
		$this->dropColumn('ophtroperationnote_gas_volume_version','deleted');
		$this->dropColumn('ophtroperationnote_gauge','deleted');
		$this->dropColumn('ophtroperationnote_gauge_version','deleted');
		$this->dropColumn('ophtroperationnote_postop_drugs_drug','deleted');
		$this->dropColumn('ophtroperationnote_postop_drugs_drug_version','deleted');
		$this->dropColumn('ophtroperationnote_postop_site_subspecialty_drug','deleted');
		$this->dropColumn('ophtroperationnote_postop_site_subspecialty_drug_version','deleted');
		$this->dropColumn('ophtroperationnote_preparation_intraocular_solution','deleted');
		$this->dropColumn('ophtroperationnote_preparation_intraocular_solution_version','deleted');
		$this->dropColumn('ophtroperationnote_preparation_skin_preparation','deleted');
		$this->dropColumn('ophtroperationnote_preparation_skin_preparation_version','deleted');
		$this->dropColumn('ophtroperationnote_procedure_element','deleted');
		$this->dropColumn('ophtroperationnote_procedure_element_version','deleted');
		$this->dropColumn('ophtroperationnote_procedurelist_procedure_assignment','deleted');
		$this->dropColumn('ophtroperationnote_procedurelist_procedure_assignment_version','deleted');
		$this->dropColumn('ophtroperationnote_site_subspecialty_postop_instructions','deleted');
		$this->dropColumn('ophtroperationnote_site_subspecialty_postop_instructions_version','deleted');
	}
}
