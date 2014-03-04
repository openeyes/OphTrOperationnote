<?php

class m131204_161221_table_versioning extends CDbMigration
{
	public function up()
	{
		$this->execute("
CREATE TABLE `et_ophtroperationnote_anaesthetic_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`anaesthetic_type_id` int(10) unsigned NOT NULL DEFAULT '1',
	`anaesthetist_id` int(10) unsigned NOT NULL DEFAULT '4',
	`anaesthetic_delivery_id` int(10) unsigned NOT NULL DEFAULT '5',
	`anaesthetic_comment` varchar(1024) DEFAULT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`anaesthetic_witness_id` int(10) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_ana_type_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_ana_type_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_ana_anaesthetic_type_id_fk` (`anaesthetic_type_id`),
	KEY `acv_et_ophtroperationnote_ana_anaesthetist_id_fk` (`anaesthetist_id`),
	KEY `acv_et_ophtroperationnote_ana_anaesthetic_delivery_id_fk` (`anaesthetic_delivery_id`),
	KEY `acv_et_ophtroperationnote_ana_anaesthetic_witness_id_fk` (`anaesthetic_witness_id`),
	KEY `acv_et_ophtroperationnote_anaesthetic_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_anaesthetic_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ana_anaesthetic_delivery_id_fk` FOREIGN KEY (`anaesthetic_delivery_id`) REFERENCES `anaesthetic_delivery` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ana_anaesthetic_type_id_fk` FOREIGN KEY (`anaesthetic_type_id`) REFERENCES `anaesthetic_type` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ana_anaesthetic_witness_id_fk` FOREIGN KEY (`anaesthetic_witness_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ana_anaesthetist_id_fk` FOREIGN KEY (`anaesthetist_id`) REFERENCES `anaesthetist` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ana_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ana_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_anaesthetic_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_anaesthetic_version');

		$this->createIndex('et_ophtroperationnote_anaesthetic_aid_fk','et_ophtroperationnote_anaesthetic_version','id');

		$this->addColumn('et_ophtroperationnote_anaesthetic_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_anaesthetic_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_anaesthetic_version','version_id');
		$this->alterColumn('et_ophtroperationnote_anaesthetic_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_buckle_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`drainage_type_id` int(10) unsigned NOT NULL,
	`drain_haem` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`deep_suture` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`eyedraw` varchar(4096) NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`report` varchar(4096) NOT NULL,
	`comments` varchar(1024) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_bu_drainage_type_id_fk` (`drainage_type_id`),
	KEY `acv_et_ophtroperationnote_bu_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_bu_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_buckle_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_buckle_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_bu_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_bu_drainage_type_id_fk` FOREIGN KEY (`drainage_type_id`) REFERENCES `ophtroperationnote_buckle_drainage_type` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_bu_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_buckle_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_buckle_version');

		$this->createIndex('et_ophtroperationnote_buckle_aid_fk','et_ophtroperationnote_buckle_version','id');

		$this->addColumn('et_ophtroperationnote_buckle_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_buckle_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_buckle_version','version_id');
		$this->alterColumn('et_ophtroperationnote_buckle_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_cataract_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`incision_site_id` int(10) unsigned NOT NULL DEFAULT '1',
	`length` varchar(5) NOT NULL DEFAULT '2.8',
	`meridian` varchar(5) NOT NULL DEFAULT '180',
	`incision_type_id` int(10) unsigned NOT NULL DEFAULT '1',
	`eyedraw` text NOT NULL,
	`report` varchar(4096) NOT NULL DEFAULT 'Continuous Circular Capsulorrhexis\nHydrodissection\nPhakoemulsification of lens nucleus\nAspiration of soft lens matter\nViscoelastic removed',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`iol_position_id` int(10) unsigned DEFAULT '1',
	`complication_notes` varchar(4096) DEFAULT NULL,
	`eyedraw2` varchar(4096) NOT NULL,
	`iol_power` varchar(5) NOT NULL,
	`iol_type_id` int(10) unsigned DEFAULT NULL,
	`report2` varchar(4096) NOT NULL,
	`predicted_refraction` decimal(4,2) NOT NULL DEFAULT '0.00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_ca_incision_site_id_fk` (`incision_site_id`),
	KEY `acv_et_ophtroperationnote_ca_incision_type_id_fk` (`incision_type_id`),
	KEY `acv_et_ophtroperationnote_ca_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_ca_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_cataract_iol_position_fk` (`iol_position_id`),
	KEY `acv_et_ophtroperationnote_cataract_iol_type_id_fk` (`iol_type_id`),
	KEY `acv_et_ophtroperationnote_cataract_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_cataract_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_cataract_iol_position_fk` FOREIGN KEY (`iol_position_id`) REFERENCES `ophtroperationnote_cataract_iol_position` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_cataract_iol_type_id_fk` FOREIGN KEY (`iol_type_id`) REFERENCES `ophtroperationnote_cataract_iol_type` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ca_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ca_incision_site_id_fk` FOREIGN KEY (`incision_site_id`) REFERENCES `ophtroperationnote_cataract_incision_site` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ca_incision_type_id_fk` FOREIGN KEY (`incision_type_id`) REFERENCES `ophtroperationnote_cataract_incision_type` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_ca_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_cataract_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_cataract_version');

		$this->createIndex('et_ophtroperationnote_cataract_aid_fk','et_ophtroperationnote_cataract_version','id');

		$this->addColumn('et_ophtroperationnote_cataract_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_cataract_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_cataract_version','version_id');
		$this->alterColumn('et_ophtroperationnote_cataract_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_comments_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`comments` varchar(4096) NOT NULL,
	`postop_instructions` varchar(4096) NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_comments_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_comments_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_comments_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_comments_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_comments_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_comments_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_comments_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_comments_version');

		$this->createIndex('et_ophtroperationnote_comments_aid_fk','et_ophtroperationnote_comments_version','id');

		$this->addColumn('et_ophtroperationnote_comments_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_comments_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_comments_version','version_id');
		$this->alterColumn('et_ophtroperationnote_comments_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_genericprocedure_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`proc_id` int(10) unsigned NOT NULL,
	`comments` varchar(4096) NOT NULL,
	`element_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_genericprocedure_event_id_fk` (`event_id`),
	KEY `acv_et_ophtroperationnote_genericprocedure_proc_id_fk` (`proc_id`),
	KEY `acv_et_ophtroperationnote_genericprocedure_cui_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_genericprocedure_lmui_fk` (`last_modified_user_id`),
	CONSTRAINT `acv_et_ophtroperationnote_genericprocedure_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_genericprocedure_proc_id_fk` FOREIGN KEY (`proc_id`) REFERENCES `proc` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_genericprocedure_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_genericprocedure_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_genericprocedure_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_genericprocedure_version');

		$this->createIndex('et_ophtroperationnote_genericprocedure_aid_fk','et_ophtroperationnote_genericprocedure_version','id');

		$this->addColumn('et_ophtroperationnote_genericprocedure_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_genericprocedure_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_genericprocedure_version','version_id');
		$this->alterColumn('et_ophtroperationnote_genericprocedure_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_membrane_peel_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`membrane_blue` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`brilliant_blue` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`other_dye` varchar(255) NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`comments` varchar(1024) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_mp_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_mp_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_membrane_peel_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_membrane_peel_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_mp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_mp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_membrane_peel_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_membrane_peel_version');

		$this->createIndex('et_ophtroperationnote_membrane_peel_aid_fk','et_ophtroperationnote_membrane_peel_version','id');

		$this->addColumn('et_ophtroperationnote_membrane_peel_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_membrane_peel_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_membrane_peel_version','version_id');
		$this->alterColumn('et_ophtroperationnote_membrane_peel_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_personnel_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`scrub_nurse_id` int(10) unsigned NOT NULL,
	`floor_nurse_id` int(10) unsigned NOT NULL,
	`accompanying_nurse_id` int(10) unsigned NOT NULL,
	`operating_department_practitioner_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_p_event_id_fk` (`event_id`),
	KEY `acv_et_ophtroperationnote_p_scrub_nurse_id_fk` (`scrub_nurse_id`),
	KEY `acv_et_ophtroperationnote_p_floor_nurse_id_fk` (`floor_nurse_id`),
	KEY `acv_et_ophtroperationnote_p_accompanying_nurse_id_fk` (`accompanying_nurse_id`),
	KEY `acv_phtroperationnote_p_operating_department_practitioner_id_fk` (`operating_department_practitioner_id`),
	KEY `acv_et_ophtroperationnote_p_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_p_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_et_ophtroperationnote_p_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_p_scrub_nurse_id_fk` FOREIGN KEY (`scrub_nurse_id`) REFERENCES `contact` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_p_floor_nurse_id_fk` FOREIGN KEY (`floor_nurse_id`) REFERENCES `contact` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_p_accompanying_nurse_id_fk` FOREIGN KEY (`accompanying_nurse_id`) REFERENCES `contact` (`id`),
	CONSTRAINT `acv_phtroperationnote_p_operating_department_practitioner_id_fk` FOREIGN KEY (`operating_department_practitioner_id`) REFERENCES `contact` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_p_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_p_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_personnel_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_personnel_version');

		$this->createIndex('et_ophtroperationnote_personnel_aid_fk','et_ophtroperationnote_personnel_version','id');

		$this->addColumn('et_ophtroperationnote_personnel_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_personnel_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_personnel_version','version_id');
		$this->alterColumn('et_ophtroperationnote_personnel_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_postop_drugs_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_postop_drugs_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_postop_drugs_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_postop_drugs_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_postop_drugs_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_postop_drugs_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_postop_drugs_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_postop_drugs_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_postop_drugs_version');

		$this->createIndex('et_ophtroperationnote_postop_drugs_aid_fk','et_ophtroperationnote_postop_drugs_version','id');

		$this->addColumn('et_ophtroperationnote_postop_drugs_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_postop_drugs_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_postop_drugs_version','version_id');
		$this->alterColumn('et_ophtroperationnote_postop_drugs_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_preparation_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`spo2` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`oxygen` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`pulse` smallint(2) unsigned NOT NULL DEFAULT '0',
	`skin_preparation_id` int(10) unsigned NOT NULL,
	`intraocular_solution_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_preparation_event_id_fk` (`event_id`),
	KEY `acv_et_ophtroperationnote_preparation_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_preparation_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_preparation_skin_preparation_id_fk` (`skin_preparation_id`),
	KEY `acv_et_ophtroperationnote_preparation_intraocular_solution_id_fk` (`intraocular_solution_id`),
	CONSTRAINT `acv_et_ophtroperationnote_preparation_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_preparation_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_preparation_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_preparation_skin_preparation_id_fk` FOREIGN KEY (`skin_preparation_id`) REFERENCES `ophtroperationnote_preparation_skin_preparation` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_preparation_intraocular_solution_id_fk` FOREIGN KEY (`intraocular_solution_id`) REFERENCES `ophtroperationnote_preparation_intraocular_solution` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_preparation_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_preparation_version');

		$this->createIndex('et_ophtroperationnote_preparation_aid_fk','et_ophtroperationnote_preparation_version','id');

		$this->addColumn('et_ophtroperationnote_preparation_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_preparation_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_preparation_version','version_id');
		$this->alterColumn('et_ophtroperationnote_preparation_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_procedurelist_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
	`eye_id` int(10) unsigned NOT NULL,
	`booking_event_id` int(10) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_procedurelist_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_procedurelist_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_procedurelist_eye_id_fk` (`eye_id`),
	KEY `acv_et_ophtroperationnote_procedurelist_bei_fk` (`booking_event_id`),
	KEY `acv_et_ophtroperationnote_procedurelist_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_procedurelist_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_procedurelist_bei_fk` FOREIGN KEY (`booking_event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_procedurelist_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_procedurelist_eye_id_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_procedurelist_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_procedurelist_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_procedurelist_version');

		$this->createIndex('et_ophtroperationnote_procedurelist_aid_fk','et_ophtroperationnote_procedurelist_version','id');

		$this->addColumn('et_ophtroperationnote_procedurelist_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_procedurelist_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_procedurelist_version','version_id');
		$this->alterColumn('et_ophtroperationnote_procedurelist_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_surgeon_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`surgeon_id` int(10) unsigned NOT NULL,
	`assistant_id` int(10) unsigned DEFAULT NULL,
	`supervising_surgeon_id` int(10) unsigned DEFAULT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_sur_type_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_sur_type_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_sur_surgeon_id_fk` (`surgeon_id`),
	KEY `acv_et_ophtroperationnote_surgeon_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_surgeon_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_sur_surgeon_id_fk` FOREIGN KEY (`surgeon_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_sur_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_sur_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_surgeon_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_surgeon_version');

		$this->createIndex('et_ophtroperationnote_surgeon_aid_fk','et_ophtroperationnote_surgeon_version','id');

		$this->addColumn('et_ophtroperationnote_surgeon_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_surgeon_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_surgeon_version','version_id');
		$this->alterColumn('et_ophtroperationnote_surgeon_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_tamponade_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`gas_type_id` int(10) unsigned NOT NULL,
	`gas_percentage_id` int(10) unsigned NOT NULL DEFAULT '0',
	`gas_volume_id` int(10) unsigned DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_tp_gas_type_id_fk` (`gas_type_id`),
	KEY `acv_et_ophtroperationnote_tp_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_tp_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophtroperationnote_tamponade_pc_id` (`gas_percentage_id`),
	KEY `acv_et_ophtroperationnote_tamponade_gv_id` (`gas_volume_id`),
	KEY `acv_et_ophtroperationnote_tamponade_eid_fk` (`event_id`),
	CONSTRAINT `acv_et_ophtroperationnote_tamponade_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_tamponade_gv_id` FOREIGN KEY (`gas_volume_id`) REFERENCES `ophtroperationnote_gas_volume` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_tp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_tp_gas_type_id_fk` FOREIGN KEY (`gas_type_id`) REFERENCES `ophtroperationnote_gas_type` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_tp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_tamponade_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_tamponade_version');

		$this->createIndex('et_ophtroperationnote_tamponade_aid_fk','et_ophtroperationnote_tamponade_version','id');

		$this->addColumn('et_ophtroperationnote_tamponade_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_tamponade_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_tamponade_version','version_id');
		$this->alterColumn('et_ophtroperationnote_tamponade_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophtroperationnote_vitrectomy_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`gauge_id` int(10) unsigned NOT NULL,
	`pvd_induced` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`eyedraw` text NOT NULL,
	`comments` varchar(1024) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophtroperationnote_vitrectomy_event_id` (`event_id`),
	KEY `acv_et_ophtroperationnote_vitrectomy_gauge_id` (`gauge_id`),
	KEY `acv_et_ophtroperationnote_vit_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophtroperationnote_vit_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_et_ophtroperationnote_vitrectomy_event_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_vitrectomy_gauge_fk` FOREIGN KEY (`gauge_id`) REFERENCES `ophtroperationnote_gauge` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_vit_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophtroperationnote_vit_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('et_ophtroperationnote_vitrectomy_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophtroperationnote_vitrectomy_version');

		$this->createIndex('et_ophtroperationnote_vitrectomy_aid_fk','et_ophtroperationnote_vitrectomy_version','id');

		$this->addColumn('et_ophtroperationnote_vitrectomy_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophtroperationnote_vitrectomy_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophtroperationnote_vitrectomy_version','version_id');
		$this->alterColumn('et_ophtroperationnote_vitrectomy_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_anaesthetic_anaesthetic_agent_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`anaesthetic_agent_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`et_ophtroperationnote_anaesthetic_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_paa_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_paa_created_user_id_fk` (`created_user_id`),
	KEY `acv_ophtroperationnote_paa_anaesthetic_agent_id_fk` (`anaesthetic_agent_id`),
	KEY `acv_ophtroperationnote_paa_anaesthetic_id_fk` (`et_ophtroperationnote_anaesthetic_id`),
	CONSTRAINT `acv_ophtroperationnote_paa_anaesthetic_agent_id_fk` FOREIGN KEY (`anaesthetic_agent_id`) REFERENCES `anaesthetic_agent` (`id`),
	CONSTRAINT `acv_ophtroperationnote_paa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_paa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_anaesthetic_anaesthetic_agent_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_anaesthetic_anaesthetic_agent_version');

		$this->createIndex('ophtroperationnote_anaesthetic_anaesthetic_agent_aid_fk','ophtroperationnote_anaesthetic_anaesthetic_agent_version','id');

		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_agent_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_agent_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_anaesthetic_anaesthetic_agent_version','version_id');
		$this->alterColumn('ophtroperationnote_anaesthetic_anaesthetic_agent_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_anaesthetic_anaesthetic_complication_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`et_ophtroperationnote_anaesthetic_id` int(10) unsigned NOT NULL,
	`anaesthetic_complication_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_pac_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_pac_created_user_id_fk` (`created_user_id`),
	KEY `acv_ophtroperationnote_anaesthetic_ac_anaesthetic_id_fk` (`et_ophtroperationnote_anaesthetic_id`),
	KEY `acv_ophtroperationnote_anaesthetic_aca_complication_id_fk` (`anaesthetic_complication_id`),
	CONSTRAINT `acv_ophtroperationnote_anaesthetic_aca_complication_id_fk` FOREIGN KEY (`anaesthetic_complication_id`) REFERENCES `ophtroperationnote_anaesthetic_anaesthetic_complications` (`id`),
	CONSTRAINT `acv_ophtroperationnote_pac_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_pac_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_anaesthetic_anaesthetic_complication_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_anaesthetic_anaesthetic_complication_version');

		$this->createIndex('ophtroperationnote_anaesthetic_anaesthetic_complication_aid_fk','ophtroperationnote_anaesthetic_anaesthetic_complication_version','id');

		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complication_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complication_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_anaesthetic_anaesthetic_complication_version','version_id');
		$this->alterColumn('ophtroperationnote_anaesthetic_anaesthetic_complication_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_anaesthetic_anaesthetic_complications_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_anaesthetic_ac_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_anaesthetic_ac_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_anaesthetic_ac_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_anaesthetic_ac_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_anaesthetic_anaesthetic_complications_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_anaesthetic_anaesthetic_complications_version');

		$this->createIndex('ophtroperationnote_anaesthetic_anaesthetic_complications_aid_fk','ophtroperationnote_anaesthetic_anaesthetic_complications_version','id');
		$this->addForeignKey('ophtroperationnote_anaesthetic_anaesthetic_complications_aid_fk','ophtroperationnote_anaesthetic_anaesthetic_complications_version','id','ophtroperationnote_anaesthetic_anaesthetic_complications','id');

		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complications_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complications_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_anaesthetic_anaesthetic_complications_version','version_id');
		$this->alterColumn('ophtroperationnote_anaesthetic_anaesthetic_complications_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_buckle_drainage_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(16) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_bdt_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_bdt_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_bdt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_bdt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_buckle_drainage_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_buckle_drainage_type_version');

		$this->createIndex('ophtroperationnote_buckle_drainage_type_aid_fk','ophtroperationnote_buckle_drainage_type_version','id');
		$this->addForeignKey('ophtroperationnote_buckle_drainage_type_aid_fk','ophtroperationnote_buckle_drainage_type_version','id','ophtroperationnote_buckle_drainage_type','id');

		$this->addColumn('ophtroperationnote_buckle_drainage_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_buckle_drainage_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_buckle_drainage_type_version','version_id');
		$this->alterColumn('ophtroperationnote_buckle_drainage_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_cataract_complication_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`cataract_id` int(10) unsigned NOT NULL,
	`complication_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_cc2_cataract_id_fk` (`cataract_id`),
	KEY `acv_ophtroperationnote_cc2_complication_id_fk` (`complication_id`),
	KEY `acv_ophtroperationnote_cc2_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_cc2_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_cc2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_cc2_complication_id_fk` FOREIGN KEY (`complication_id`) REFERENCES `ophtroperationnote_cataract_complications` (`id`),
	CONSTRAINT `acv_ophtroperationnote_cc2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_cataract_complication_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_cataract_complication_version');

		$this->createIndex('ophtroperationnote_cataract_complication_aid_fk','ophtroperationnote_cataract_complication_version','id');

		$this->addColumn('ophtroperationnote_cataract_complication_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_cataract_complication_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_cataract_complication_version','version_id');
		$this->alterColumn('ophtroperationnote_cataract_complication_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_cataract_complications_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_cc_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_cc_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_cc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_cc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_cataract_complications_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_cataract_complications_version');

		$this->createIndex('ophtroperationnote_cataract_complications_aid_fk','ophtroperationnote_cataract_complications_version','id');
		$this->addForeignKey('ophtroperationnote_cataract_complications_aid_fk','ophtroperationnote_cataract_complications_version','id','ophtroperationnote_cataract_complications','id');

		$this->addColumn('ophtroperationnote_cataract_complications_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_cataract_complications_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_cataract_complications_version','version_id');
		$this->alterColumn('ophtroperationnote_cataract_complications_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_cataract_incision_site_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(16) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_cis_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_cis_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_cis_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_cis_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_cataract_incision_site_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_cataract_incision_site_version');

		$this->createIndex('ophtroperationnote_cataract_incision_site_aid_fk','ophtroperationnote_cataract_incision_site_version','id');
		$this->addForeignKey('ophtroperationnote_cataract_incision_site_aid_fk','ophtroperationnote_cataract_incision_site_version','id','ophtroperationnote_cataract_incision_site','id');

		$this->addColumn('ophtroperationnote_cataract_incision_site_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_cataract_incision_site_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_cataract_incision_site_version','version_id');
		$this->alterColumn('ophtroperationnote_cataract_incision_site_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_cataract_incision_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(16) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_cit_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_cit_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_cit_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_cit_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_cataract_incision_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_cataract_incision_type_version');

		$this->createIndex('ophtroperationnote_cataract_incision_type_aid_fk','ophtroperationnote_cataract_incision_type_version','id');
		$this->addForeignKey('ophtroperationnote_cataract_incision_type_aid_fk','ophtroperationnote_cataract_incision_type_version','id','ophtroperationnote_cataract_incision_type','id');

		$this->addColumn('ophtroperationnote_cataract_incision_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_cataract_incision_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_cataract_incision_type_version','version_id');
		$this->alterColumn('ophtroperationnote_cataract_incision_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_cataract_iol_position_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(32) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_cip_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_cip_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_cip_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_cip_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_cataract_iol_position_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_cataract_iol_position_version');

		$this->createIndex('ophtroperationnote_cataract_iol_position_aid_fk','ophtroperationnote_cataract_iol_position_version','id');
		$this->addForeignKey('ophtroperationnote_cataract_iol_position_aid_fk','ophtroperationnote_cataract_iol_position_version','id','ophtroperationnote_cataract_iol_position','id');

		$this->addColumn('ophtroperationnote_cataract_iol_position_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_cataract_iol_position_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_cataract_iol_position_version','version_id');
		$this->alterColumn('ophtroperationnote_cataract_iol_position_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_cataract_iol_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`private` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_cot_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_cot_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_cot_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_cot_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_cataract_iol_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_cataract_iol_type_version');

		$this->createIndex('ophtroperationnote_cataract_iol_type_aid_fk','ophtroperationnote_cataract_iol_type_version','id');
		$this->addForeignKey('ophtroperationnote_cataract_iol_type_aid_fk','ophtroperationnote_cataract_iol_type_version','id','ophtroperationnote_cataract_iol_type','id');

		$this->addColumn('ophtroperationnote_cataract_iol_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_cataract_iol_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_cataract_iol_type_version','version_id');
		$this->alterColumn('ophtroperationnote_cataract_iol_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_cataract_operative_device_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`cataract_id` int(10) unsigned NOT NULL,
	`operative_device_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_ccd_cataract_id_fk` (`cataract_id`),
	KEY `acv_ophtroperationnote_ccd_operative_device_id_fk` (`operative_device_id`),
	KEY `acv_ophtroperationnote_ccd_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_ccd_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_ccd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_ccd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_ccd_operative_device_id_fk` FOREIGN KEY (`operative_device_id`) REFERENCES `operative_device` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_cataract_operative_device_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_cataract_operative_device_version');

		$this->createIndex('ophtroperationnote_cataract_operative_device_aid_fk','ophtroperationnote_cataract_operative_device_version','id');

		$this->addColumn('ophtroperationnote_cataract_operative_device_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_cataract_operative_device_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_cataract_operative_device_version','version_id');
		$this->alterColumn('ophtroperationnote_cataract_operative_device_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_gas_percentage_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`value` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_gas_pc_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_gas_pc_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_gas_pc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_gas_pc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_gas_percentage_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_gas_percentage_version');

		$this->createIndex('ophtroperationnote_gas_percentage_aid_fk','ophtroperationnote_gas_percentage_version','id');

		$this->addColumn('ophtroperationnote_gas_percentage_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_gas_percentage_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_gas_percentage_version','version_id');
		$this->alterColumn('ophtroperationnote_gas_percentage_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_gas_type_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(5) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_gas_type_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_gas_type_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_gas_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_gas_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_gas_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_gas_type_version');

		$this->createIndex('ophtroperationnote_gas_type_aid_fk','ophtroperationnote_gas_type_version','id');
		$this->addForeignKey('ophtroperationnote_gas_type_aid_fk','ophtroperationnote_gas_type_version','id','ophtroperationnote_gas_type','id');

		$this->addColumn('ophtroperationnote_gas_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_gas_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_gas_type_version','version_id');
		$this->alterColumn('ophtroperationnote_gas_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_gas_volume_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`value` varchar(3) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_gas_vol_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_gas_vol_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_gas_vol_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_gas_vol_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_gas_volume_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_gas_volume_version');

		$this->createIndex('ophtroperationnote_gas_volume_aid_fk','ophtroperationnote_gas_volume_version','id');
		$this->addForeignKey('ophtroperationnote_gas_volume_aid_fk','ophtroperationnote_gas_volume_version','id','ophtroperationnote_gas_volume','id');

		$this->addColumn('ophtroperationnote_gas_volume_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_gas_volume_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_gas_volume_version','version_id');
		$this->alterColumn('ophtroperationnote_gas_volume_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_gauge_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`value` varchar(5) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_gauge_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_gauge_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_gauge_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_gauge_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_gauge_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_gauge_version');

		$this->createIndex('ophtroperationnote_gauge_aid_fk','ophtroperationnote_gauge_version','id');
		$this->addForeignKey('ophtroperationnote_gauge_aid_fk','ophtroperationnote_gauge_version','id','ophtroperationnote_gauge','id');

		$this->addColumn('ophtroperationnote_gauge_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_gauge_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_gauge_version','version_id');
		$this->alterColumn('ophtroperationnote_gauge_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_postop_drug_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_postop_drug_l_m_u_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_postop_drug_c_u_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_postop_drug_c_u_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_postop_drug_l_m_u_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_postop_drug_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_postop_drug_version');

		$this->createIndex('ophtroperationnote_postop_drug_aid_fk','ophtroperationnote_postop_drug_version','id');
		$this->addForeignKey('ophtroperationnote_postop_drug_aid_fk','ophtroperationnote_postop_drug_version','id','ophtroperationnote_postop_drug','id');

		$this->addColumn('ophtroperationnote_postop_drug_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_postop_drug_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_postop_drug_version','version_id');
		$this->alterColumn('ophtroperationnote_postop_drug_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_postop_drugs_drug_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`ophtroperationnote_postop_drugs_id` int(10) unsigned NOT NULL,
	`drug_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_pdd_created_user_id_fk` (`created_user_id`),
	KEY `acv_ophtroperationnote_pdd_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_pdd_drug_id_fk` (`drug_id`),
	KEY `acv_ophtroperationnote_pdd_drugs_id_fk` (`ophtroperationnote_postop_drugs_id`),
	CONSTRAINT `acv_ophtroperationnote_pdd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_pdd_drug_id_fk` FOREIGN KEY (`drug_id`) REFERENCES `ophtroperationnote_postop_drug` (`id`),
	CONSTRAINT `acv_ophtroperationnote_pdd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_postop_drugs_drug_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_postop_drugs_drug_version');

		$this->createIndex('ophtroperationnote_postop_drugs_drug_aid_fk','ophtroperationnote_postop_drugs_drug_version','id');

		$this->addColumn('ophtroperationnote_postop_drugs_drug_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_postop_drugs_drug_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_postop_drugs_drug_version','version_id');
		$this->alterColumn('ophtroperationnote_postop_drugs_drug_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_postop_site_subspecialty_drug_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`site_id` int(10) unsigned NOT NULL,
	`subspecialty_id` int(10) unsigned NOT NULL,
	`drug_id` int(10) unsigned NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL,
	`default` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_postop_site_subspecialty_drug_l_m_u_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_postop_site_subspecialty_drug_c_u_id_fk` (`created_user_id`),
	KEY `acv_ophtroperationnote_postop_site_subspecialty_drug_site_id_fk` (`site_id`),
	KEY `acv_ophtroperationnote_postop_site_subspecialty_drug_s_id_fk` (`subspecialty_id`),
	KEY `acv_ophtroperationnote_postop_site_subspecialty_drug_drug_id_fk` (`drug_id`),
	CONSTRAINT `acv_ophtroperationnote_postop_site_subspecialty_drug_drug_id_fk` FOREIGN KEY (`drug_id`) REFERENCES `ophtroperationnote_postop_drug` (`id`),
	CONSTRAINT `acv_ophtroperationnote_postop_site_subspecialty_drug_c_u_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_postop_site_subspecialty_drug_l_m_u_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_postop_site_subspecialty_drug_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
	CONSTRAINT `acv_ophtroperationnote_postop_site_subspecialty_drug_s_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_postop_site_subspecialty_drug_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_postop_site_subspecialty_drug_version');

		$this->createIndex('ophtroperationnote_postop_site_subspecialty_drug_aid_fk','ophtroperationnote_postop_site_subspecialty_drug_version','id');

		$this->addColumn('ophtroperationnote_postop_site_subspecialty_drug_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_postop_site_subspecialty_drug_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_postop_site_subspecialty_drug_version','version_id');
		$this->alterColumn('ophtroperationnote_postop_site_subspecialty_drug_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_preparation_intraocular_solution_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(128) DEFAULT NULL,
	`display_order` tinyint(3) unsigned DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_pis_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_pis_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_pis_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_pis_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_preparation_intraocular_solution_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_preparation_intraocular_solution_version');

		$this->createIndex('ophtroperationnote_preparation_intraocular_solution_aid_fk','ophtroperationnote_preparation_intraocular_solution_version','id');
		$this->addForeignKey('ophtroperationnote_preparation_intraocular_solution_aid_fk','ophtroperationnote_preparation_intraocular_solution_version','id','ophtroperationnote_preparation_intraocular_solution','id');

		$this->addColumn('ophtroperationnote_preparation_intraocular_solution_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_preparation_intraocular_solution_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_preparation_intraocular_solution_version','version_id');
		$this->alterColumn('ophtroperationnote_preparation_intraocular_solution_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_preparation_skin_preparation_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(128) DEFAULT NULL,
	`display_order` tinyint(3) unsigned DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_psp_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_psp_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_psp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_psp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_preparation_skin_preparation_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_preparation_skin_preparation_version');

		$this->createIndex('ophtroperationnote_preparation_skin_preparation_aid_fk','ophtroperationnote_preparation_skin_preparation_version','id');
		$this->addForeignKey('ophtroperationnote_preparation_skin_preparation_aid_fk','ophtroperationnote_preparation_skin_preparation_version','id','ophtroperationnote_preparation_skin_preparation','id');

		$this->addColumn('ophtroperationnote_preparation_skin_preparation_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_preparation_skin_preparation_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_preparation_skin_preparation_version','version_id');
		$this->alterColumn('ophtroperationnote_preparation_skin_preparation_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_procedure_element_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`procedure_id` int(10) unsigned NOT NULL,
	`element_type_id` int(10) unsigned NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_pe_procedure_fk` (`procedure_id`),
	KEY `acv_ophtroperationnote_pe_element_type_fk` (`element_type_id`),
	KEY `acv_ophtroperationnote_pe_created_user_id_fk` (`created_user_id`),
	KEY `acv_ophtroperationnote_pe_last_modified_user_id_fk` (`last_modified_user_id`),
	CONSTRAINT `acv_ophtroperationnote_pe_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_pe_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_pe_element_type_fk` FOREIGN KEY (`element_type_id`) REFERENCES `element_type` (`id`),
	CONSTRAINT `acv_ophtroperationnote_pe_procedure_fk` FOREIGN KEY (`procedure_id`) REFERENCES `proc` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_procedure_element_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_procedure_element_version');

		$this->createIndex('ophtroperationnote_procedure_element_aid_fk','ophtroperationnote_procedure_element_version','id');

		$this->addColumn('ophtroperationnote_procedure_element_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_procedure_element_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_procedure_element_version','version_id');
		$this->alterColumn('ophtroperationnote_procedure_element_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_procedurelist_procedure_assignment_version` (
	`procedurelist_id` int(10) unsigned NOT NULL,
	`proc_id` int(10) unsigned NOT NULL,
	`display_order` tinyint(3) unsigned DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`),
	KEY `acv_procedurelist_id` (`procedurelist_id`),
	KEY `acv_procedure_id` (`proc_id`),
	KEY `acv_ophtroperationnote_plpa_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_plpa_created_user_id_fk` (`created_user_id`),
	KEY `acv_procedurelist_procid_key` (`procedurelist_id`,`proc_id`),
	CONSTRAINT `acv_ophtroperationnote_plpa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_plpa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_procedurelist_procedure_assignment_ibfk_1` FOREIGN KEY (`proc_id`) REFERENCES `proc` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_procedurelist_procedure_assignment_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_procedurelist_procedure_assignment_version');

		$this->createIndex('ophtroperationnote_procedurelist_procedure_assignment_aid_fk','ophtroperationnote_procedurelist_procedure_assignment_version','id');

		$this->addColumn('ophtroperationnote_procedurelist_procedure_assignment_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_procedurelist_procedure_assignment_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_procedurelist_procedure_assignment_version','version_id');
		$this->alterColumn('ophtroperationnote_procedurelist_procedure_assignment_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophtroperationnote_site_subspecialty_postop_instructions_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`site_id` int(10) unsigned NOT NULL,
	`subspecialty_id` int(10) unsigned NOT NULL,
	`content` varchar(1024) NOT NULL,
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophtroperationnote_sspi_site_id_fk` (`site_id`),
	KEY `acv_ophtroperationnote_sspi_subspecialty_id_fk` (`subspecialty_id`),
	KEY `acv_ophtroperationnote_sspi_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_ophtroperationnote_sspi_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_ophtroperationnote_sspi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_sspi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophtroperationnote_sspi_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
	CONSTRAINT `acv_ophtroperationnote_sspi_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->alterColumn('ophtroperationnote_site_subspecialty_postop_instructions_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophtroperationnote_site_subspecialty_postop_instructions_version');

		$this->createIndex('ophtroperationnote_site_subspecialty_postop_instructions_aid_fk','ophtroperationnote_site_subspecialty_postop_instructions_version','id');

		$this->addColumn('ophtroperationnote_site_subspecialty_postop_instructions_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophtroperationnote_site_subspecialty_postop_instructions_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophtroperationnote_site_subspecialty_postop_instructions_version','version_id');
		$this->alterColumn('ophtroperationnote_site_subspecialty_postop_instructions_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complications','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_anaesthetic_anaesthetic_complications_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_buckle_drainage_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_buckle_drainage_type_version','deleted','tinyint(1) unsigned not null');
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
		$this->addColumn('ophtroperationnote_gas_type','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_type_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_volume','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gas_volume_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gauge','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_gauge_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_preparation_intraocular_solution','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_preparation_intraocular_solution_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_preparation_skin_preparation','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophtroperationnote_preparation_skin_preparation_version','deleted','tinyint(1) unsigned not null');
	}

	public function down()
	{
		$this->dropColumn('ophtroperationnote_anaesthetic_anaesthetic_complications','deleted');
		$this->dropColumn('ophtroperationnote_buckle_drainage_type','deleted');
		$this->dropColumn('ophtroperationnote_cataract_complications','deleted');
		$this->dropColumn('ophtroperationnote_cataract_incision_site','deleted');
		$this->dropColumn('ophtroperationnote_cataract_incision_type','deleted');
		$this->dropColumn('ophtroperationnote_cataract_iol_position','deleted');
		$this->dropColumn('ophtroperationnote_cataract_iol_type','deleted');
		$this->dropColumn('ophtroperationnote_gas_type','deleted');
		$this->dropColumn('ophtroperationnote_gas_volume','deleted');
		$this->dropColumn('ophtroperationnote_gauge','deleted');
		$this->dropColumn('ophtroperationnote_preparation_intraocular_solution','deleted');
		$this->dropColumn('ophtroperationnote_preparation_skin_preparation','deleted');

		$this->dropTable('et_ophtroperationnote_anaesthetic_version');
		$this->dropTable('et_ophtroperationnote_buckle_version');
		$this->dropTable('et_ophtroperationnote_cataract_version');
		$this->dropTable('et_ophtroperationnote_comments_version');
		$this->dropTable('et_ophtroperationnote_genericprocedure_version');
		$this->dropTable('et_ophtroperationnote_membrane_peel_version');
		$this->dropTable('et_ophtroperationnote_personnel_version');
		$this->dropTable('et_ophtroperationnote_postop_drugs_version');
		$this->dropTable('et_ophtroperationnote_preparation_version');
		$this->dropTable('et_ophtroperationnote_procedurelist_version');
		$this->dropTable('et_ophtroperationnote_surgeon_version');
		$this->dropTable('et_ophtroperationnote_tamponade_version');
		$this->dropTable('et_ophtroperationnote_vitrectomy_version');
		$this->dropTable('ophtroperationnote_anaesthetic_anaesthetic_agent_version');
		$this->dropTable('ophtroperationnote_anaesthetic_anaesthetic_complication_version');
		$this->dropTable('ophtroperationnote_anaesthetic_anaesthetic_complications_version');
		$this->dropTable('ophtroperationnote_buckle_drainage_type_version');
		$this->dropTable('ophtroperationnote_cataract_complication_version');
		$this->dropTable('ophtroperationnote_cataract_complications_version');
		$this->dropTable('ophtroperationnote_cataract_incision_site_version');
		$this->dropTable('ophtroperationnote_cataract_incision_type_version');
		$this->dropTable('ophtroperationnote_cataract_iol_position_version');
		$this->dropTable('ophtroperationnote_cataract_iol_type_version');
		$this->dropTable('ophtroperationnote_cataract_operative_device_version');
		$this->dropTable('ophtroperationnote_gas_percentage_version');
		$this->dropTable('ophtroperationnote_gas_type_version');
		$this->dropTable('ophtroperationnote_gas_volume_version');
		$this->dropTable('ophtroperationnote_gauge_version');
		$this->dropTable('ophtroperationnote_postop_drug_version');
		$this->dropTable('ophtroperationnote_postop_drugs_drug_version');
		$this->dropTable('ophtroperationnote_postop_site_subspecialty_drug_version');
		$this->dropTable('ophtroperationnote_preparation_intraocular_solution_version');
		$this->dropTable('ophtroperationnote_preparation_skin_preparation_version');
		$this->dropTable('ophtroperationnote_procedure_element_version');
		$this->dropTable('ophtroperationnote_procedurelist_procedure_assignment_version');
		$this->dropTable('ophtroperationnote_site_subspecialty_postop_instructions_version');
	}
}
