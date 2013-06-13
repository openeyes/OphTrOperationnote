<?php

class m130218_094538_new_proc_elements_oe2661 extends CDbMigration
{
	public function up()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));

		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Removal of eyelash','class_name'=>'ElementRemovalOfEyelash','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Removal of foreign body from conjunctiva','class_name'=>'ElementRemovalOfForeignBodyFromConjunctiva','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Excision of lesion of cornea','class_name'=>'ElementExcisionOfLesionOfCornea','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Adjustment to corneal suture','class_name'=>'ElementAdjustmentToCornealSuture','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Removal of releasable suture following glaucoma surgery','class_name'=>'ElementRemovalOfReleasableSuture','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Reformation of anterior chamber','class_name'=>'ElementReformationOfAnteriorChamber','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Topical local anaesthetic to eye','class_name'=>'ElementTopicalLocalAnaesthetic','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Conjunctival swab','class_name'=>'ElementConjunctivalSwab','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Indocyanine green angiography','class_name'=>'ElementICGAngiogram','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'B scan ultrasound of eye','class_name'=>'ElementBScanUltrasound','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Scanning laser ophthalmoscopy','class_name'=>'ElementScanningLaserOphthalmoscopy','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Optical coherence tomography','class_name'=>'ElementOpticalCoherenceTomography','display_order'=>20));
		$this->insert('element_type',array('event_type_id'=>$event_type->id,'name'=>'Insertion of sustained release device into posterior segment of eye','class_name'=>'ElementInsertionSlowRelease','display_order'=>20));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementRemovalOfEyelash'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Removal of eyelash','398072007'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementRemovalOfForeignBodyFromConjunctiva'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Removal of foreign body from conjunctiva','78362007'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementExcisionOfLesionOfCornea'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Excision of lesion of cornea','75588007'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementAdjustmentToCornealSuture'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Adjustment to corneal suture','172421008'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementBandageContactLens'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Insertion of bandage contact lens','428497007'));
		if (!ProcedureListOperationElement::model()->find('element_type_id=? and procedure_id=?',array($element->id,$proc->id))) {
			$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));
		}

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementRemovalOfReleasableSuture'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Removal of releasable suture following glaucoma surgery','426877004'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementReformationOfAnteriorChamber'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Reformation of anterior chamber','172517004'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementTopicalLocalAnaesthetic'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Topical local anaesthetic to eye','231346001'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementConjunctivalSwab'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Conjunctival swab','312855001'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementICGAngiogram'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Indocyanine green angiography','252823001'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementBScanUltrasound'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('B scan ultrasound of eye','241452002'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementScanningLaserOphthalmoscopy'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Scanning laser ophthalmoscopy','252846004'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementOpticalCoherenceTomography'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Optical coherence tomography','392010000'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementInsertionSlowRelease'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Insertion of sustained release device into posterior segment of eye','428618008'));
		$this->insert('et_ophtroperationnote_procedure_element',array('element_type_id'=>$element->id,'procedure_id'=>$proc->id));

		$this->createTable('et_ophtroperationnote_removal_of_eyelash', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_roe_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_roe_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_roe_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_roe_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_roe_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_roe_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_removal_of_fb_conjunctiva', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_rofbc_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_rofbc_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_rofbc_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_rofbc_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_rofbc_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_rofbc_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_excision_of_corneal_lesion', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_eocl_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_eocl_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_eocl_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_eocl_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_eocl_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_eocl_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_adjust_corneal_suture', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_acs_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_acs_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_acs_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_acs_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_acs_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_acs_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_removal_of_releasable_suture', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_rors_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_rors_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_rors_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_rors_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_rors_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_rors_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_reformation_ac2', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_rac2_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_rac2_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_rac2_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_rac2_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_rac2_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_rac2_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_topical_anaesthetic', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_topa_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_topa_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_topa_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_topa_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_topa_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_topa_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_conjunctival_swab', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_conswa_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_conswa_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_conswa_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_conswa_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_conswa_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_conswa_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_indocyanine_ga', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_indoga_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_indoga_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_indoga_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_indoga_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_indoga_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_indoga_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_b_scan_ultrasound', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_bscan_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_bscan_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_bscan_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_bscan_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_bscan_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_bscan_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_laser_ophthalmoscopy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_lasoph_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_lasoph_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_lasoph_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_lasoph_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_lasoph_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_lasoph_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_optical_coherence_tomography', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_optcoh_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_optcoh_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_optcoh_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_optcoh_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_optcoh_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_optcoh_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('et_ophtroperationnote_insertion_slow_release', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_insr_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_insr_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_insr_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_insr_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_insr_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_insr_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_removal_of_eyelash');
		$this->dropTable('et_ophtroperationnote_removal_of_fb_conjunctiva');
		$this->dropTable('et_ophtroperationnote_excision_of_corneal_lesion');
		$this->dropTable('et_ophtroperationnote_adjust_corneal_suture');
		$this->dropTable('et_ophtroperationnote_removal_of_releasable_suture');
		$this->dropTable('et_ophtroperationnote_reformation_ac2');
		$this->dropTable('et_ophtroperationnote_topical_anaesthetic');
		$this->dropTable('et_ophtroperationnote_conjunctival_swab');
		$this->dropTable('et_ophtroperationnote_indocyanine_ga');
		$this->dropTable('et_ophtroperationnote_b_scan_ultrasound');
		$this->dropTable('et_ophtroperationnote_laser_ophthalmoscopy');
		$this->dropTable('et_ophtroperationnote_optical_coherence_tomography');
		$this->dropTable('et_ophtroperationnote_insertion_slow_release');

		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementRemovalOfEyelash'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Removal of eyelash','398072007'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementRemovalOfForeignBodyFromConjunctiva'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Removal of foreign body from conjunctiva','78362007'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementExcisionOfLesionOfCornea'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Excision of lesion of cornea','75588007'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementAdjustmentToCornealSuture'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Adjustment to corneal suture','172421008'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementBandageContactLens'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Insertion of bandage contact lens','428497007'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementRemovalOfReleasableSuture'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Removal of releasable suture following glaucoma surgery','426877004'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementReformationOfAnteriorChamber'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Reformation of anterior chamber','172517004'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementTopicalLocalAnaesthetic'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Topical local anaesthetic to eye','231346001'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementConjunctivalSwab'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Conjunctival swab','312855001'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementICGAngiogram'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Indocyanine green angiography','252823001'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementBScanUltrasound'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('B scan ultrasound of eye','241452002'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementScanningLaserOphthalmoscopy'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Scanning laser ophthalmoscopy','252846004'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementOpticalCoherenceTomography'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Optical coherence tomography','392010000'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$element = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementInsertionSlowRelease'));
		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Insertion of sustained release device into posterior segment of eye','428618008'));
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id=$element->id");

		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementRemovalOfEyelash'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementRemovalOfForeignBodyFromConjunctiva'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementExcisionOfLesionOfCornea'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementAdjustmentToCornealSuture'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementInsertionOfBandageContactLens'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementRemovalOfReleasableSuture'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementReformationOfAnteriorChamber'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementTopicalLocalAnaesthetic'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementConjunctivalSwab'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementICGAngiogram'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementBScanUltrasound'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementScanningLaserOphthalmoscopy'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementOpticalCoherenceTomography'");
		$this->delete('element_type',"event_type_id=$event_type->id and class_name='ElementInsertionSlowRelease'");
	}
}
