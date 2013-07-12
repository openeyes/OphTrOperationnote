<?php

class m121030_093559_add_missing_procedure_element_mappings extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Posterior synechiolysis', 'class_name' => 'ElementPosteriorSynechiolysis', 'event_type_id' => $event_type['id'], 'display_order' => 20, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPosteriorSynechiolysis'))->queryRow();

		if (!$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Posterior synechiolysis'))->queryRow()) {
			$this->insert('proc',array(
					'term' => 'Posterior synechiolysis',
					'short_format' => 'Post syn lysis',
					'default_duration' => '10',
					'snomed_code' => '44958007',
					'snomed_term' => 'Lysis of posterior adhesions of iris',
			));
			$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Posterior synechiolysis'))->queryRow();
		}

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));

		$this->createTable('et_ophtroperationnote_posterior_synechiolysis', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_post_sync_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_post_sync_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_post_sync_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_post_sync_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('element_type', array('name' => 'Anterior synechiolysis', 'class_name' => 'ElementAnteriorSynechiolysis', 'event_type_id' => $event_type['id'], 'display_order' => 20, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorSynechiolysis'))->queryRow();

		if (!$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Anterior synechiolysis'))->queryRow()) {
			$this->insert('proc',array(
					'term' => 'Anterior synechiolysis',
					'short_format' => 'Ant syn lysis',
					'default_duration' => '10',
					'snomed_code' => '55931003',
					'snomed_term' => 'Lysis of anterior adhesions of iris',
			));
			$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Anterior synechiolysis'))->queryRow();
		}

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));

		$this->createTable('et_ophtroperationnote_anterior_synechiolysis', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ant_sync_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ant_sync_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ant_sync_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ant_sync_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('element_type', array('name' => 'Repair of eyelid laceration, full-thickness involving lid margin', 'class_name' => 'ElementEyelidLacerationFullThickness', 'event_type_id' => $event_type['id'], 'display_order' => 20, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEyelidLacerationFullThickness'))->queryRow();

		if (!$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Repair of eyelid laceration, full-thickness involving lid margin'))->queryRow()) {
			$this->insert('proc',array(
					'term' => 'Repair of eyelid laceration, full-thickness involving lid margin',
					'short_format' => 'Lid laceration full',
					'default_duration' => '30',
					'snomed_code' => '361162007',
					'snomed_term' => 'Repair of eyelid laceration, full-thickness involving lid margin',
			));
			$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Repair of eyelid laceration, full-thickness involving lid margin'))->queryRow();
		}

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));

		$this->createTable('et_ophtroperationnote_eyelid_laceration_full_thickness', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_eyeld_lacer_ft_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_eyeld_lacer_ft_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_eyeld_lacer_ft_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_eyeld_lacer_ft_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('element_type', array('name' => 'Repair of eyelid laceration, partial-thickness involving lid margin', 'class_name' => 'ElementEyelidLacerationPartialThickness', 'event_type_id' => $event_type['id'], 'display_order' => 20, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEyelidLacerationPartialThickness'))->queryRow();

		if (!$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Repair of eyelid laceration, partial-thickness involving lid margin'))->queryRow()) {
			$this->insert('proc',array(
					'term' => 'Repair of eyelid laceration, partial-thickness involving lid margin',
					'short_format' => 'Lid laceration partial',
					'default_duration' => '30',
					'snomed_code' => '361157006',
					'snomed_term' => 'Repair of eyelid laceration, partial-thickness involving lid margin',
			));
			$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Repair of eyelid laceration, partial-thickness involving lid margin'))->queryRow();
		}

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));

		$this->createTable('et_ophtroperationnote_eyelid_laceration_partial_thickness', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_eyeld_lacer_pt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_eyeld_lacer_pt_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_eyeld_lacer_pt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_eyeld_lacer_pt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('element_type', array('name' => 'Biopsy of buccal mucosa', 'class_name' => 'ElementBiopsyOfBuccalMucosa', 'event_type_id' => $event_type['id'], 'display_order' => 20, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfBuccalMucosa'))->queryRow();

		if (!$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Biopsy of buccal mucosa'))->queryRow()) {
			$this->insert('proc',array(
					'term' => 'Biopsy of buccal mucosa',
					'short_format' => 'Buccal biopsy',
					'default_duration' => '20',
					'snomed_code' => '6818001',
					'snomed_term' => 'Excision of buccal mucosa',
			));
			$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Biopsy of buccal mucosa'))->queryRow();
		}

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));

		$this->createTable('et_ophtroperationnote_biopsy_buccal_mucosa', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_biopbucmuc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_biopbucmuc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_biopbucmuc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_biopbucmuc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_posterior_synechiolysis');

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Posterior synechiolysis'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id='.$proc['id']);
		$this->delete('element_type',"class_name='ElementPosteriorSynechiolysis'");

		$this->dropTable('et_ophtroperationnote_anterior_synechiolysis');

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Anterior synechiolysis'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id='.$proc['id']);
		$this->delete('element_type',"class_name='ElementAnteriorSynechiolysis'");

		$this->dropTable('et_ophtroperationnote_eyelid_laceration_full_thickness');

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Repair of eyelid laceration, full-thickness involving lid margin'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id='.$proc['id']);
		$this->delete('element_type',"class_name='ElementEyelidLacerationFullThickness'");

		$this->dropTable('et_ophtroperationnote_eyelid_laceration_partial_thickness');

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Repair of eyelid laceration, partial-thickness involving lid margin'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id='.$proc['id']);
		$this->delete('element_type',"class_name='ElementEyelidLacerationPartialThickness'");

		$this->dropTable('et_ophtroperationnote_biopsy_buccal_mucosa');

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term',array(':term' => 'Biopsy of buccal mucosa'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id='.$proc['id']);
		$this->delete('element_type',"class_name='ElementBiopsyOfBuccalMucosa'");
	}
}
