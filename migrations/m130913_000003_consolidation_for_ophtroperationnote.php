<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class m130913_000003_consolidation_for_ophtroperationnote extends OEMigration
{
	private  $element_types ;

	public function setData(){
		$this->element_types = array(
			'Element_OphTrOperationnote_ProcedureList' => array('name' => 'Procedure list'),
			'Element_OphTrOperationnote_Vitrectomy' => array('name' => 'Vitrectomy'),
			'Element_OphTrOperationnote_MembranePeel' => array('name' => 'Membrane peel'),
			'Element_OphTrOperationnote_Tamponade' => array('name' => 'Tamponade'),
			'Element_OphTrOperationnote_Buckle' => array('name' => 'Buckle'),
			'Element_OphTrOperationnote_Cataract' => array('name' => 'Cataract'),
			'Element_OphTrOperationnote_Anaesthetic' => array('name' => 'Anaesthetic'),
			'Element_OphTrOperationnote_Surgeon' => array('name' => 'Surgeon'),
			'Element_OphTrOperationnote_PostOpDrugs' => array('name' => 'Per-operative drugs'),
			'Element_OphTrOperationnote_Comments' => array('name' => 'Comments'),
			'Element_OphTrOperationnote_Personnel' => array('name' => 'Personnel'),
			'Element_OphTrOperationnote_Preparation' => array('name' => 'Preparation'),
			'Element_OphTrOperationnote_GenericProcedure' => array('name' => 'Generic procedure')
		);
	}

	public function up()
	{
		$this->setData();
		//disable foreign keys check
		$this->execute("SET foreign_key_checks = 0");

		Yii::app()->cache->flush();

		$event_type_id = $this->insertOEEventType( 'Operation note', 'OphTrOperationnote', 'Tr');
		$this->insertOEElementType($this->element_types , $event_type_id);

		$this->execute("CREATE TABLE `et_ophtroperationnote_anaesthetic` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `anaesthetic_type_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `anaesthetist_id` int(10) unsigned NOT NULL DEFAULT '4',
			  `anaesthetic_delivery_id` int(10) unsigned NOT NULL DEFAULT '5',
			  `anaesthetic_comment` varchar(1024)  DEFAULT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `anaesthetic_witness_id` int(10) unsigned DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `et_ophtroperationnote_ana_type_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_ana_type_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_ana_anaesthetic_type_id_fk` (`anaesthetic_type_id`),
			  KEY `et_ophtroperationnote_ana_anaesthetist_id_fk` (`anaesthetist_id`),
			  KEY `et_ophtroperationnote_ana_anaesthetic_delivery_id_fk` (`anaesthetic_delivery_id`),
			  KEY `et_ophtroperationnote_ana_anaesthetic_witness_id_fk` (`anaesthetic_witness_id`),
			  KEY `et_ophtroperationnote_anaesthetic_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_anaesthetic_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ana_anaesthetic_delivery_id_fk` FOREIGN KEY (`anaesthetic_delivery_id`) REFERENCES `anaesthetic_delivery` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ana_anaesthetic_type_id_fk` FOREIGN KEY (`anaesthetic_type_id`) REFERENCES `anaesthetic_type` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ana_anaesthetic_witness_id_fk` FOREIGN KEY (`anaesthetic_witness_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ana_anaesthetist_id_fk` FOREIGN KEY (`anaesthetist_id`) REFERENCES `anaesthetist` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ana_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ana_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_buckle` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `drainage_type_id` int(10) unsigned NOT NULL,
			  `drain_haem` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `deep_suture` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `eyedraw` varchar(4096)  NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `report` varchar(4096)  NOT NULL,
			  `comments` varchar(1024)  NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `et_ophtroperationnote_bu_drainage_type_id_fk` (`drainage_type_id`),
			  KEY `et_ophtroperationnote_bu_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_bu_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_buckle_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_buckle_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_bu_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_bu_drainage_type_id_fk` FOREIGN KEY (`drainage_type_id`) REFERENCES `ophtroperationnote_buckle_drainage_type` (`id`),
			  CONSTRAINT `et_ophtroperationnote_bu_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_cataract` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `incision_site_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `length` varchar(5)  NOT NULL DEFAULT '2.8',
			  `meridian` varchar(5)  NOT NULL DEFAULT '180',
			  `incision_type_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `eyedraw` text  NOT NULL,
			  `report` varchar(4096)  NOT NULL DEFAULT 'Continuous Circular Capsulorrhexis\nHydrodissection\nPhakoemulsification of lens nucleus\nAspiration of soft lens matter\nViscoelastic removed',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `iol_position_id` int(10) unsigned DEFAULT '1',
			  `complication_notes` varchar(4096)  DEFAULT NULL,
			  `eyedraw2` varchar(4096)  NOT NULL,
			  `iol_power` varchar(5)  NOT NULL,
			  `iol_type_id` int(10) unsigned DEFAULT NULL,
			  `report2` varchar(4096)  NOT NULL,
			  `predicted_refraction` decimal(4,2) NOT NULL DEFAULT '0.00',
			  PRIMARY KEY (`id`),
			  KEY `et_ophtroperationnote_ca_incision_site_id_fk` (`incision_site_id`),
			  KEY `et_ophtroperationnote_ca_incision_type_id_fk` (`incision_type_id`),
			  KEY `et_ophtroperationnote_ca_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_ca_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_cataract_iol_position_fk` (`iol_position_id`),
			  KEY `et_ophtroperationnote_cataract_iol_type_id_fk` (`iol_type_id`),
			  KEY `et_ophtroperationnote_cataract_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_cataract_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_cataract_iol_position_fk` FOREIGN KEY (`iol_position_id`) REFERENCES `ophtroperationnote_cataract_iol_position` (`id`),
			  CONSTRAINT `et_ophtroperationnote_cataract_iol_type_id_fk` FOREIGN KEY (`iol_type_id`) REFERENCES `ophtroperationnote_cataract_iol_type` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ca_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ca_incision_site_id_fk` FOREIGN KEY (`incision_site_id`) REFERENCES `ophtroperationnote_cataract_incision_site` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ca_incision_type_id_fk` FOREIGN KEY (`incision_type_id`) REFERENCES `ophtroperationnote_cataract_incision_type` (`id`),
			  CONSTRAINT `et_ophtroperationnote_ca_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_comments` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `comments` varchar(4096)  NOT NULL,
			  `postop_instructions` varchar(4096)  NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `et_ophtroperationnote_comments_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_comments_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_comments_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_comments_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_comments_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_comments_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_genericprocedure` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `proc_id` int(10) unsigned NOT NULL,
			  `comments` varchar(4096)  NOT NULL,
			  `element_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `et_ophtroperationnote_genericprocedure_event_id_fk` (`event_id`),
			  KEY `et_ophtroperationnote_genericprocedure_proc_id_fk` (`proc_id`),
			  KEY `et_ophtroperationnote_genericprocedure_cui_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_genericprocedure_lmui_fk` (`last_modified_user_id`),
			  CONSTRAINT `et_ophtroperationnote_genericprocedure_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_genericprocedure_proc_id_fk` FOREIGN KEY (`proc_id`) REFERENCES `proc` (`id`),
			  CONSTRAINT `et_ophtroperationnote_genericprocedure_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_genericprocedure_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_membrane_peel` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `membrane_blue` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `brilliant_blue` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `other_dye` varchar(255)  NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `comments` varchar(1024)  NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `et_ophtroperationnote_mp_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_mp_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_membrane_peel_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_membrane_peel_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_mp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_mp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_personnel` (
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
			  KEY `et_ophtroperationnote_p_event_id_fk` (`event_id`),
			  KEY `et_ophtroperationnote_p_scrub_nurse_id_fk` (`scrub_nurse_id`),
			  KEY `et_ophtroperationnote_p_floor_nurse_id_fk` (`floor_nurse_id`),
			  KEY `et_ophtroperationnote_p_accompanying_nurse_id_fk` (`accompanying_nurse_id`),
			  KEY `et_ophtroperationnote_p_operating_department_practitioner_id_fk` (`operating_department_practitioner_id`),
			  KEY `et_ophtroperationnote_p_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_p_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `et_ophtroperationnote_p_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_p_scrub_nurse_id_fk` FOREIGN KEY (`scrub_nurse_id`) REFERENCES `contact` (`id`),
			  CONSTRAINT `et_ophtroperationnote_p_floor_nurse_id_fk` FOREIGN KEY (`floor_nurse_id`) REFERENCES `contact` (`id`),
			  CONSTRAINT `et_ophtroperationnote_p_accompanying_nurse_id_fk` FOREIGN KEY (`accompanying_nurse_id`) REFERENCES `contact` (`id`),
			  CONSTRAINT `et_ophtroperationnote_p_operating_department_practitioner_id_fk` FOREIGN KEY (`operating_department_practitioner_id`) REFERENCES `contact` (`id`),
			  CONSTRAINT `et_ophtroperationnote_p_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_p_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_postop_drugs` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `et_ophtroperationnote_postop_drugs_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_postop_drugs_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_postop_drugs_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_postop_drugs_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_postop_drugs_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_postop_drugs_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_preparation` (
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
			  KEY `et_ophtroperationnote_preparation_event_id_fk` (`event_id`),
			  KEY `et_ophtroperationnote_preparation_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_preparation_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_preparation_skin_preparation_id_fk` (`skin_preparation_id`),
			  KEY `et_ophtroperationnote_preparation_intraocular_solution_id_fk` (`intraocular_solution_id`),
			  CONSTRAINT `et_ophtroperationnote_preparation_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_preparation_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_preparation_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_preparation_skin_preparation_id_fk` FOREIGN KEY (`skin_preparation_id`) REFERENCES `ophtroperationnote_preparation_skin_preparation` (`id`),
			  CONSTRAINT `et_ophtroperationnote_preparation_intraocular_solution_id_fk` FOREIGN KEY (`intraocular_solution_id`) REFERENCES `ophtroperationnote_preparation_intraocular_solution` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_procedurelist` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
			  `eye_id` int(10) unsigned NOT NULL,
			  `booking_event_id` int(10) unsigned DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `event_id` (`event_id`),
			  KEY `et_ophtroperationnote_procedurelist_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_procedurelist_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_procedurelist_eye_id_fk` (`eye_id`),
			  KEY `et_ophtroperationnote_procedurelist_bei_fk` (`booking_event_id`),
			  KEY `et_ophtroperationnote_procedurelist_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_procedurelist_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_procedurelist_bei_fk` FOREIGN KEY (`booking_event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_procedurelist_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_procedurelist_eye_id_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`),
			  CONSTRAINT `et_ophtroperationnote_procedurelist_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_surgeon` (
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
			  KEY `et_ophtroperationnote_sur_type_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_sur_type_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_sur_surgeon_id_fk` (`surgeon_id`),
			  KEY `et_ophtroperationnote_surgeon_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_surgeon_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_sur_surgeon_id_fk` FOREIGN KEY (`surgeon_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_sur_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_sur_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_tamponade` (
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
			  KEY `et_ophtroperationnote_tp_gas_type_id_fk` (`gas_type_id`),
			  KEY `et_ophtroperationnote_tp_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_tp_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophtroperationnote_tamponade_pc_id` (`gas_percentage_id`),
			  KEY `et_ophtroperationnote_tamponade_gv_id` (`gas_volume_id`),
			  KEY `et_ophtroperationnote_tamponade_eid_fk` (`event_id`),
			  CONSTRAINT `et_ophtroperationnote_tamponade_eid_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_tamponade_gv_id` FOREIGN KEY (`gas_volume_id`) REFERENCES `ophtroperationnote_gas_volume` (`id`),
			  CONSTRAINT `et_ophtroperationnote_tamponade_pc_id` FOREIGN KEY (`gas_percentage_id`) REFERENCES `ophtroperationnote_gas_percentage` (`id`),
			  CONSTRAINT `et_ophtroperationnote_tp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_tp_gas_type_id_fk` FOREIGN KEY (`gas_type_id`) REFERENCES `ophtroperationnote_gas_type` (`id`),
			  CONSTRAINT `et_ophtroperationnote_tp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophtroperationnote_vitrectomy` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `event_id` int(10) unsigned NOT NULL,
			  `gauge_id` int(10) unsigned NOT NULL,
			  `pvd_induced` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `eyedraw` text  NOT NULL,
			  `comments` varchar(1024)  NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `et_ophtroperationnote_vitrectomy_event_id` (`event_id`),
			  KEY `et_ophtroperationnote_vitrectomy_gauge_id` (`gauge_id`),
			  KEY `et_ophtroperationnote_vit_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophtroperationnote_vit_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `et_ophtroperationnote_vitrectomy_event_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophtroperationnote_vitrectomy_gauge_fk` FOREIGN KEY (`gauge_id`) REFERENCES `ophtroperationnote_gauge` (`id`),
			  CONSTRAINT `et_ophtroperationnote_vit_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophtroperationnote_vit_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_anaesthetic_anaesthetic_agent` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `anaesthetic_agent_id` int(10) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `et_ophtroperationnote_anaesthetic_id` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_paa_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_paa_created_user_id_fk` (`created_user_id`),
			  KEY `ophtroperationnote_paa_anaesthetic_agent_id_fk` (`anaesthetic_agent_id`),
			  KEY `ophtroperationnote_paa_anaesthetic_id_fk` (`et_ophtroperationnote_anaesthetic_id`),
			  CONSTRAINT `ophtroperationnote_paa_anaesthetic_id_fk` FOREIGN KEY (`et_ophtroperationnote_anaesthetic_id`) REFERENCES `et_ophtroperationnote_anaesthetic` (`id`),
			  CONSTRAINT `ophtroperationnote_paa_anaesthetic_agent_id_fk` FOREIGN KEY (`anaesthetic_agent_id`) REFERENCES `anaesthetic_agent` (`id`),
			  CONSTRAINT `ophtroperationnote_paa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_paa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_anaesthetic_anaesthetic_complication` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `et_ophtroperationnote_anaesthetic_id` int(10) unsigned NOT NULL,
			  `anaesthetic_complication_id` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_pac_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_pac_created_user_id_fk` (`created_user_id`),
			  KEY `ophtroperationnote_anaesthetic_ac_anaesthetic_id_fk` (`et_ophtroperationnote_anaesthetic_id`),
			  KEY `ophtroperationnote_anaesthetic_aca_complication_id_fk` (`anaesthetic_complication_id`),
			  CONSTRAINT `ophtroperationnote_anaesthetic_aca_complication_id_fk` FOREIGN KEY (`anaesthetic_complication_id`) REFERENCES `ophtroperationnote_anaesthetic_anaesthetic_complications` (`id`),
			  CONSTRAINT `ophtroperationnote_anaesthetic_ac_anaesthetic_id_fk` FOREIGN KEY (`et_ophtroperationnote_anaesthetic_id`) REFERENCES `et_ophtroperationnote_anaesthetic` (`id`),
			  CONSTRAINT `ophtroperationnote_pac_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_pac_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_anaesthetic_anaesthetic_complications` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(64)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_anaesthetic_ac_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_anaesthetic_ac_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_anaesthetic_ac_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_anaesthetic_ac_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_buckle_drainage_type` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(16)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_bdt_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_bdt_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_bdt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_bdt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_cataract_complication` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `cataract_id` int(10) unsigned NOT NULL,
			  `complication_id` int(10) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_cc2_cataract_id_fk` (`cataract_id`),
			  KEY `ophtroperationnote_cc2_complication_id_fk` (`complication_id`),
			  KEY `ophtroperationnote_cc2_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_cc2_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_cc2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_cc2_cataract_id_fk` FOREIGN KEY (`cataract_id`) REFERENCES `et_ophtroperationnote_cataract` (`id`),
			  CONSTRAINT `ophtroperationnote_cc2_complication_id_fk` FOREIGN KEY (`complication_id`) REFERENCES `ophtroperationnote_cataract_complications` (`id`),
			  CONSTRAINT `ophtroperationnote_cc2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_cataract_complications` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(64)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_cc_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_cc_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_cc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_cc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_cataract_incision_site` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(16)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_cis_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_cis_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_cis_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_cis_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_cataract_incision_type` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(16)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_cit_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_cit_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_cit_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_cit_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_cataract_iol_position` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(32)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_cip_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_cip_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_cip_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_cip_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_cataract_iol_type` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(64)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `private` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_cot_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_cot_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_cot_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_cot_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_cataract_operative_device` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `cataract_id` int(10) unsigned NOT NULL,
			  `operative_device_id` int(10) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_ccd_cataract_id_fk` (`cataract_id`),
			  KEY `ophtroperationnote_ccd_operative_device_id_fk` (`operative_device_id`),
			  KEY `ophtroperationnote_ccd_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_ccd_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_ccd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_ccd_cataract_id_fk` FOREIGN KEY (`cataract_id`) REFERENCES `et_ophtroperationnote_cataract` (`id`),
			  CONSTRAINT `ophtroperationnote_ccd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_ccd_operative_device_id_fk` FOREIGN KEY (`operative_device_id`) REFERENCES `operative_device` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_gas_percentage` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `value` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_gas_pc_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_gas_pc_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_gas_pc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_gas_pc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_gas_type` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(5)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_gas_type_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_gas_type_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_gas_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_gas_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_gas_volume` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `value` varchar(3)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_gas_vol_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_gas_vol_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_gas_vol_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_gas_vol_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_gauge` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `value` varchar(5)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_gauge_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_gauge_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_gauge_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_gauge_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_postop_drug` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(255)  NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `display_order` int(10) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_postop_drug_l_m_u_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_postop_drug_c_u_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_postop_drug_c_u_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_postop_drug_l_m_u_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_postop_drugs_drug` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `ophtroperationnote_postop_drugs_id` int(10) unsigned NOT NULL,
			  `drug_id` int(10) unsigned NOT NULL,
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_pdd_created_user_id_fk` (`created_user_id`),
			  KEY `ophtroperationnote_pdd_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_pdd_drug_id_fk` (`drug_id`),
			  KEY `ophtroperationnote_pdd_drugs_id_fk` (`ophtroperationnote_postop_drugs_id`),
			  CONSTRAINT `ophtroperationnote_pdd_drugs_id_fk` FOREIGN KEY (`ophtroperationnote_postop_drugs_id`) REFERENCES `et_ophtroperationnote_postop_drugs` (`id`),
			  CONSTRAINT `ophtroperationnote_pdd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_pdd_drug_id_fk` FOREIGN KEY (`drug_id`) REFERENCES `ophtroperationnote_postop_drug` (`id`),
			  CONSTRAINT `ophtroperationnote_pdd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_postop_site_subspecialty_drug` (
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
			  KEY `ophtroperationnote_postop_site_subspecialty_drug_l_m_u_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_postop_site_subspecialty_drug_c_u_id_fk` (`created_user_id`),
			  KEY `ophtroperationnote_postop_site_subspecialty_drug_site_id_fk` (`site_id`),
			  KEY `ophtroperationnote_postop_site_subspecialty_drug_s_id_fk` (`subspecialty_id`),
			  KEY `ophtroperationnote_postop_site_subspecialty_drug_drug_id_fk` (`drug_id`),
			  CONSTRAINT `ophtroperationnote_postop_site_subspecialty_drug_drug_id_fk` FOREIGN KEY (`drug_id`) REFERENCES `ophtroperationnote_postop_drug` (`id`),
			  CONSTRAINT `ophtroperationnote_postop_site_subspecialty_drug_c_u_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_postop_site_subspecialty_drug_l_m_u_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_postop_site_subspecialty_drug_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
			  CONSTRAINT `ophtroperationnote_postop_site_subspecialty_drug_s_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_preparation_intraocular_solution` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(128)  DEFAULT NULL,
			  `display_order` tinyint(3) unsigned DEFAULT '0',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_pis_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_pis_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_pis_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_pis_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_preparation_skin_preparation` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(128)  DEFAULT NULL,
			  `display_order` tinyint(3) unsigned DEFAULT '0',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_psp_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_psp_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_psp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_psp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_procedure_element` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `procedure_id` int(10) unsigned NOT NULL,
			  `element_type_id` int(10) unsigned NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_pe_procedure_fk` (`procedure_id`),
			  KEY `ophtroperationnote_pe_element_type_fk` (`element_type_id`),
			  KEY `ophtroperationnote_pe_created_user_id_fk` (`created_user_id`),
			  KEY `ophtroperationnote_pe_last_modified_user_id_fk` (`last_modified_user_id`),
			  CONSTRAINT `ophtroperationnote_pe_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_pe_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_pe_element_type_fk` FOREIGN KEY (`element_type_id`) REFERENCES `element_type` (`id`),
			  CONSTRAINT `ophtroperationnote_pe_procedure_fk` FOREIGN KEY (`procedure_id`) REFERENCES `proc` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_procedurelist_procedure_assignment` (
			  `procedurelist_id` int(10) unsigned NOT NULL,
			  `proc_id` int(10) unsigned NOT NULL,
			  `display_order` tinyint(3) unsigned DEFAULT '0',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY (`id`),
			  KEY `procedurelist_id` (`procedurelist_id`),
			  KEY `procedure_id` (`proc_id`),
			  KEY `ophtroperationnote_plpa_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_plpa_created_user_id_fk` (`created_user_id`),
			  KEY `procedurelist_procid_key` (`procedurelist_id`,`proc_id`),
			  CONSTRAINT `et_ophtroperationnote_plpa_proclist_fk` FOREIGN KEY (`procedurelist_id`) REFERENCES `et_ophtroperationnote_procedurelist` (`id`),
			  CONSTRAINT `ophtroperationnote_plpa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_plpa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_procedurelist_procedure_assignment_ibfk_1` FOREIGN KEY (`proc_id`) REFERENCES `proc` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophtroperationnote_site_subspecialty_postop_instructions` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `site_id` int(10) unsigned NOT NULL,
			  `subspecialty_id` int(10) unsigned NOT NULL,
			  `content` varchar(1024)  NOT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophtroperationnote_sspi_site_id_fk` (`site_id`),
			  KEY `ophtroperationnote_sspi_subspecialty_id_fk` (`subspecialty_id`),
			  KEY `ophtroperationnote_sspi_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `ophtroperationnote_sspi_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `ophtroperationnote_sspi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_sspi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophtroperationnote_sspi_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
			  CONSTRAINT `ophtroperationnote_sspi_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$migrations_path = dirname(__FILE__);
		$this->initialiseData($migrations_path);

		//enable foreign keys check
		$this->execute("SET foreign_key_checks = 1");

	}

	public function down()
	{
		$this->setData();

		$this->execute("SET foreign_key_checks = 0");

		$tables = array(
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
			'ophtroperationnote_anaesthetic_anaesthetic_complications',
			'ophtroperationnote_buckle_drainage_type',
			'ophtroperationnote_cataract_complication',
			'ophtroperationnote_cataract_complications',
			'ophtroperationnote_cataract_incision_site',
			'ophtroperationnote_cataract_incision_type',
			'ophtroperationnote_cataract_iol_position',
			'ophtroperationnote_cataract_iol_type',
			'ophtroperationnote_cataract_operative_device',
			'ophtroperationnote_gas_percentage',
			'ophtroperationnote_gas_type',
			'ophtroperationnote_gas_volume',
			'ophtroperationnote_gauge',
			'ophtroperationnote_postop_drug',
			'ophtroperationnote_postop_drugs_drug',
			'ophtroperationnote_postop_site_subspecialty_drug',
			'ophtroperationnote_preparation_intraocular_solution',
			'ophtroperationnote_preparation_skin_preparation',
			'ophtroperationnote_procedure_element',
			'ophtroperationnote_procedurelist_procedure_assignment',
			'ophtroperationnote_site_subspecialty_postop_instructions'
		);

		foreach ($tables as $table) {
			$this->dropTable($table);
		}

		$event_type_id = $this->dbConnection->createCommand()
			->select('id')
			->from('event_type')
			->where('class_name=:class_name', array(':class_name' => 'OphTrOperationnote'))
			->queryScalar();

		//delete anaesthetic <-> element_type relations tables entries
		$elementTypeId = $this->getIdOfElementTypeByClassName('Element_OphTrOperationnote_ProcedureList');

		/*$this->deleteOEFromMultikeyTable('patient_shortcode', $this->patients_shortcodes($event_type_id) );
		$this->deleteOEFromMultikeyTable('operative_device', $this->operativeDeviceArray );
		$this->deleteOEFromMultikeyTable('proc', $this->procArray );
		$this->deleteOEFromMultikeyTable('setting_metadata', $this->settingMetadataArray );
		$this->deleteOEFromMultikeyTable('element_type_anaesthetic_type', $this->anaestheticTypes($elementTypeId ) );

		$anaestheticAnaestheticAgentsDeleteArray = array();
		foreach($this->anaestheticAgents as  $anaestheticAgent_id){
			$anaestheticAnaestheticAgentsDeleteArray [] = array('anaesthetic_agent_id'=> $anaestheticAgent_id, 'element_type_id'=>$elementTypeId );
		}
		$this->deleteOEFromMultikeyTable('element_type_anaesthetic_agent', $anaestheticAnaestheticAgentsDeleteArray );

		$anaestheticAnaestheticComplicationsDeleteArray = array();
		foreach($this->anaestheticComplications as  $anaestheticComplication_id){
			$anaestheticAnaestheticComplicationsDeleteArray [] = array('anaesthetic_complication_id'=> $anaestheticComplication_id, 'element_type_id'=>$elementTypeId );
		}
		$this->deleteOEFromMultikeyTable('element_type_anaesthetic_complication', $anaestheticAnaestheticComplicationsDeleteArray );

		$anaestheticAnaestheticDeliveryDeleteArray = array();
		foreach($this->anaestheticDelivery as  $anaestheticDelivery_id){
			$anaestheticAnaestheticDeliveryDeleteArray[] = array('anaesthetic_delivery_id'=> $anaestheticDelivery_id, 'element_type_id'=>$elementTypeId );
		}
		$this->deleteOEFromMultikeyTable('element_type_anaesthetic_delivery', $anaestheticAnaestheticDeliveryDeleteArray);

		//delete the element_type_anaesthetist entries
		$anaestheticAnaesthetistDeleteArray = array();
		foreach($this->anaestheticAnaesthetist as  $anaestheticAnaesthetist_id){
			$anaestheticAnaesthetistDeleteArray[] = array('anaesthetist_id'=> $anaestheticAnaesthetist_id, 'element_type_id'=>$elementTypeId );
		}
		$this->deleteOEFromMultikeyTable('element_type_anaesthetist', $anaestheticAnaesthetistDeleteArray);

		// Delete the element types eye
		$eyIdDeleteArray = array();
		foreach($this->eye_ids as  $eye_id){
			$eyIdDeleteArray[] = array('eye_id'=> $eye_id, 'element_type_id'=>$elementTypeId );
		}
		$this->deleteOEFromMultikeyTable('element_type_eye', $eyIdDeleteArray);*/

		// Delete the element types for this event type
		$this->delete('element_type', 'event_type_id = ' . $event_type_id);

		// Delete the event type
		$this->delete('event_type', 'id = ' . $event_type_id);

		$this->execute("SET foreign_key_checks = 1");
	}
}
