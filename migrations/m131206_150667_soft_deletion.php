<?php

class m131206_150667_soft_deletion extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_al_trabeculoplasty','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_al_trabeculoplasty_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_anaesthetic','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_anaesthetic_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_buckle','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_buckle_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_cataract','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_cataract_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_comments','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_comments_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_cycloablation','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_cycloablation_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_fl_photocoagulation','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_fl_photocoagulation_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_genericprocedure','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_genericprocedure_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_chor','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_chor_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_demarcation','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_demarcation_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_gonio','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_gonio_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_hyal','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_hyal_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_irid','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_irid_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_vitr','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_laser_vitr_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_macular_grid','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_macular_grid_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_membrane_peel','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_membrane_peel_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_personnel','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_personnel_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_postop_drugs','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_postop_drugs_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_preparation','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_preparation_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_procedurelist','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_procedurelist_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_surgeon','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_surgeon_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_suture_lys','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_suture_lys_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_tamponade','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_tamponade_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_vitrectomy','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophtroperationnote_vitrectomy_version','deleted','tinyint(1) unsigned not null');
	}

	public function down()
	{
		$this->dropColumn('et_ophtroperationnote_al_trabeculoplasty','deleted');
		$this->dropColumn('et_ophtroperationnote_al_trabeculoplasty_version','deleted');
		$this->dropColumn('et_ophtroperationnote_anaesthetic','deleted');
		$this->dropColumn('et_ophtroperationnote_anaesthetic_version','deleted');
		$this->dropColumn('et_ophtroperationnote_buckle','deleted');
		$this->dropColumn('et_ophtroperationnote_buckle_version','deleted');
		$this->dropColumn('et_ophtroperationnote_cataract','deleted');
		$this->dropColumn('et_ophtroperationnote_cataract_version','deleted');
		$this->dropColumn('et_ophtroperationnote_comments','deleted');
		$this->dropColumn('et_ophtroperationnote_comments_version','deleted');
		$this->dropColumn('et_ophtroperationnote_cycloablation','deleted');
		$this->dropColumn('et_ophtroperationnote_cycloablation_version','deleted');
		$this->dropColumn('et_ophtroperationnote_fl_photocoagulation','deleted');
		$this->dropColumn('et_ophtroperationnote_fl_photocoagulation_version','deleted');
		$this->dropColumn('et_ophtroperationnote_genericprocedure','deleted');
		$this->dropColumn('et_ophtroperationnote_genericprocedure_version','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_chor','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_chor_version','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_demarcation','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_demarcation_version','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_gonio','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_gonio_version','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_hyal','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_hyal_version','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_irid','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_irid_version','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_vitr','deleted');
		$this->dropColumn('et_ophtroperationnote_laser_vitr_version','deleted');
		$this->dropColumn('et_ophtroperationnote_macular_grid','deleted');
		$this->dropColumn('et_ophtroperationnote_macular_grid_version','deleted');
		$this->dropColumn('et_ophtroperationnote_membrane_peel','deleted');
		$this->dropColumn('et_ophtroperationnote_membrane_peel_version','deleted');
		$this->dropColumn('et_ophtroperationnote_personnel','deleted');
		$this->dropColumn('et_ophtroperationnote_personnel_version','deleted');
		$this->dropColumn('et_ophtroperationnote_postop_drugs','deleted');
		$this->dropColumn('et_ophtroperationnote_postop_drugs_version','deleted');
		$this->dropColumn('et_ophtroperationnote_preparation','deleted');
		$this->dropColumn('et_ophtroperationnote_preparation_version','deleted');
		$this->dropColumn('et_ophtroperationnote_procedurelist','deleted');
		$this->dropColumn('et_ophtroperationnote_procedurelist_version','deleted');
		$this->dropColumn('et_ophtroperationnote_surgeon','deleted');
		$this->dropColumn('et_ophtroperationnote_surgeon_version','deleted');
		$this->dropColumn('et_ophtroperationnote_suture_lys','deleted');
		$this->dropColumn('et_ophtroperationnote_suture_lys_version','deleted');
		$this->dropColumn('et_ophtroperationnote_tamponade','deleted');
		$this->dropColumn('et_ophtroperationnote_tamponade_version','deleted');
		$this->dropColumn('et_ophtroperationnote_vitrectomy','deleted');
		$this->dropColumn('et_ophtroperationnote_vitrectomy_version','deleted');
	}
}
