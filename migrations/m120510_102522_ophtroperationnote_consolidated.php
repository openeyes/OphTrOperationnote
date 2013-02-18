<?php
class m120510_102522_ophtroperationnote_consolidated extends CDbMigration
{
	public function up() {
				// create et_ophtroperationnote_procedurelist
		$this->createTable('et_ophtroperationnote_procedurelist', array(
			'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
			'event_id' => 'int(10) unsigned NOT NULL',
			'surgeon_id' => 'int(10) unsigned',
			'assistant_id' => 'int(10) unsigned',
			'anaesthetic_type' => 'varchar(255)',
			'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
			'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
			'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
			'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `event_id` (`event_id`)'
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_last_modified_user_id_fk','et_ophtroperationnote_procedurelist','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_created_user_id_fk','et_ophtroperationnote_procedurelist','created_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_surgeon_id_fk','et_ophtroperationnote_procedurelist','surgeon_id','consultant','id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_assistant_id_fk','et_ophtroperationnote_procedurelist','assistant_id','contact','id');

		# (many to many relationship with procedures)


		// create an event_type for 'operationnote' if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow()) {
			$group = $this->dbConnection->createCommand()->select('id')->from('event_group')->where('name=:name',array(':name'=>'Treatment events'))->queryRow();
			$this->insert('event_type', array('name' => 'Operation note','event_group_id' => $group['id'], 'class_name' => 'OphTrOperationnote'));
		}

		// select the event_type id for 'operationnote'
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		// create an element_type for 'Procedure list' if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name', array(':name'=>'Procedure list'))->queryRow()) {
			$this->insert('element_type', array('name' => 'Procedure list','class_name' => 'ElementProcedureList', 'event_type_id' => $event_type['id'], 'display_order' => 1));
		}

		// select the element_type_id for 'Procedure list'
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name', array(':name'=>'Procedure list'))->queryRow();
			$to = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'Topical'))->queryRow();
		$lac = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LAC'))->queryRow();
		$la = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LA'))->queryRow();
		$las = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LAS'))->queryRow();
		$ga = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'GA'))->queryRow();

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:event_type_id',array(':name'=>'Procedure list',':event_type_id'=>$event_type['id']))->queryRow();

		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$to['id'],'display_order'=>1));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$la['id'],'display_order'=>2));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$lac['id'],'display_order'=>3));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$las['id'],'display_order'=>4));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$ga['id'],'display_order'=>5));
			$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>5),'anaesthetic_type=4');
		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>4),'anaesthetic_type=3');
		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>3),'anaesthetic_type=2');
		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>2),'anaesthetic_type=1');
		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>1),'anaesthetic_type=0');

		$this->renameColumn('et_ophtroperationnote_procedurelist','anaesthetic_type','anaesthetic_type_id');
		$this->alterColumn('et_ophtroperationnote_procedurelist','anaesthetic_type_id',"int(10) unsigned NOT NULL DEFAULT '1'");
		$this->createIndex('et_ophtroperationnote_procedurelist_anaesthetic_type_id_fk','et_ophtroperationnote_procedurelist','anaesthetic_type_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_anaesthetic_type_id_fk','et_ophtroperationnote_procedurelist','anaesthetic_type_id','anaesthetic_type','id');
			$this->createTable('et_ophtroperationnote_procedurelist_procedure_assignment',
			array('procedurelist_id' => 'int(10) unsigned NOT NULL',
				'proc_id' => 'int(10) unsigned NOT NULL',
				'display_order' => 'tinyint(3) unsigned DEFAULT \'0\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`procedurelist_id`,`proc_id`)',
				'KEY `procedurelist_id` (`procedurelist_id`)',
				'KEY `procedure_id` (`proc_id`)',
				'KEY `et_ophtroperationnote_plpa_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_plpa_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_plpa_operation_fk` FOREIGN KEY (`procedurelist_id`) REFERENCES `et_ophtroperationnote_procedurelist` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_plpa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_plpa_ibfk_1` FOREIGN KEY (`proc_id`) REFERENCES `proc` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_plpa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
			$this->createTable('et_ophtroperationnote_gauge', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'value' => 'varchar(5) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_gauge_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_gauge_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_gauge_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_gauge_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_gauge',array('id'=>1,'value'=>'20g','display_order'=>1));
		$this->insert('et_ophtroperationnote_gauge',array('id'=>2,'value'=>'23g','display_order'=>2));
		$this->insert('et_ophtroperationnote_gauge',array('id'=>3,'value'=>'25g','display_order'=>3));

		$this->createTable('et_ophtroperationnote_vitrectomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'gauge_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'pvd_induced' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_vitrectomy_event_id` (`event_id`)',
				'KEY `et_ophtroperationnote_vitrectomy_gauge_id` (`gauge_id`)',
				'CONSTRAINT `et_ophtroperationnote_vitrectomy_event_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_vitrectomy_gauge_fk` FOREIGN KEY (`gauge_id`) REFERENCES `et_ophtroperationnote_gauge` (`id`)',
				'KEY `et_ophtroperationnote_vit_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_vit_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_vit_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_vit_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$this->insert('element_type', array('name' => 'Vitrectomy', 'class_name' => 'ElementVitrectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));
			$this->createTable('et_ophtroperationnote_procedure_element',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'procedure_id' => 'int(10) unsigned NOT NULL',
				'element_type_id' => 'int(10) unsigned NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_pe_procedure_id` (`procedure_id`)',
				'KEY `et_ophtroperationnote_pe_element_type_id` (`element_type_id`)',
				'CONSTRAINT `et_ophtroperationnote_pe_procedure_fk` FOREIGN KEY (`procedure_id`) REFERENCES `proc` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_pe_element_type_fk` FOREIGN KEY (`element_type_id`) REFERENCES `element_type` (`id`)',
				'KEY `et_ophtroperationnote_pe_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_pe_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_pe_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_pe_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementVitrectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_term=:snomed',array(':snomed'=>'Vitrectomy'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_membrane_peel', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'membrane_blue' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'brilliant_blue' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'other_dye' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
        'KEY `et_ophtroperationnote_mp_last_modified_user_id_fk` (`last_modified_user_id`)',
        'KEY `et_ophtroperationnote_mp_created_user_id_fk` (`created_user_id`)',
        'CONSTRAINT `et_ophtroperationnote_mp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophtroperationnote_mp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Membrane peel', 'class_name' => 'ElementMembranePeel', 'event_type_id' => $event_type['id'], 'display_order' => 3, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMembranePeel'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_term=:snomed',array(':snomed'=>'Epiretinal dissection'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_gas_type', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(5) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_gas_type_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_gas_type_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_gas_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_gas_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_gas_type',array('id'=>1,'name'=>'Air','display_order'=>1));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>2,'name'=>'SF6','display_order'=>2));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>3,'name'=>'C2F6','display_order'=>3));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>4,'name'=>'C3F8','display_order'=>4));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>5,'name'=>'1000cS oil','display_order'=>5));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>6,'name'=>'2000cS oil','display_order'=>6));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>7,'name'=>'5000cS oil','display_order'=>7));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>8,'name'=>'Densiron','display_order'=>8));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>9,'name'=>'Oxane HD','display_order'=>9));
		$this->insert('et_ophtroperationnote_gas_type',array('id'=>10,'name'=>'PFCL','display_order'=>10));

		$this->createTable('et_ophtroperationnote_tamponade', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'gas_type_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'percentage' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'volume' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_tp_gas_type_id_fk` (`gas_type_id`)',
				'KEY `et_ophtroperationnote_tp_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_tp_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_tp_gas_type_id_fk` FOREIGN KEY (`gas_type_id`) REFERENCES `et_ophtroperationnote_gas_type` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_tp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_tp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Tamponade', 'class_name' => 'ElementTamponade', 'event_type_id' => $event_type['id'], 'display_order' => 4, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTamponade'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_term=:snomed',array(':snomed'=>'Injection of gas'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTamponade'))->queryRow();
		
		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_term=:snomed',array(':snomed'=>'Injection of silicone oil into vitreous'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_buckle_drainage_type', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(16) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_bdt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_bdt_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_bdt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_bdt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_buckle_drainage_type',array('id'=>1,'name'=>'None','display_order'=>1));
		$this->insert('et_ophtroperationnote_buckle_drainage_type',array('id'=>2,'name'=>'Suture needle','display_order'=>2));
		$this->insert('et_ophtroperationnote_buckle_drainage_type',array('id'=>3,'name'=>'Laser','display_order'=>3));
		$this->insert('et_ophtroperationnote_buckle_drainage_type',array('id'=>4,'name'=>'Cutdown','display_order'=>4));

		$this->createTable('et_ophtroperationnote_buckle', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'drainage_type_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'drain_haem' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'deep_suture' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'eyedraw' => 'varchar(1024) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_bu_drainage_type_id_fk` (`drainage_type_id`)',
				'KEY `et_ophtroperationnote_bu_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_bu_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_bu_drainage_type_id_fk` FOREIGN KEY (`drainage_type_id`) REFERENCES `et_ophtroperationnote_buckle_drainage_type` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_bu_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_bu_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Buckle', 'class_name' => 'ElementBuckle', 'event_type_id' => $event_type['id'], 'display_order' => 5, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBuckle'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_term=:snomed',array(':snomed'=>'Scleral buckling'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_gas_percentage',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'value' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_gas_pc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_gas_pc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_gas_pc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_gas_pc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_gas_percentage',array('id'=>1,'value'=>14,'display_order'=>1));
		$this->insert('et_ophtroperationnote_gas_percentage',array('id'=>2,'value'=>16,'display_order'=>2));
		$this->insert('et_ophtroperationnote_gas_percentage',array('id'=>3,'value'=>20,'display_order'=>3));
		$this->insert('et_ophtroperationnote_gas_percentage',array('id'=>4,'value'=>30,'display_order'=>4));
		$this->insert('et_ophtroperationnote_gas_percentage',array('id'=>5,'value'=>100,'display_order'=>5));

		$this->createTable('et_ophtroperationnote_gas_volume',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'value' => 'varchar(3) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_gas_vol_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_gas_vol_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_gas_vol_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_gas_vol_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_gas_volume',array('id'=>1,'value'=>'0.1','display_order'=>1));
		$this->insert('et_ophtroperationnote_gas_volume',array('id'=>2,'value'=>'0.2','display_order'=>2));
		$this->insert('et_ophtroperationnote_gas_volume',array('id'=>3,'value'=>'0.3','display_order'=>3));
		$this->insert('et_ophtroperationnote_gas_volume',array('id'=>4,'value'=>'0.4','display_order'=>4));

		$this->renameColumn('et_ophtroperationnote_tamponade','percentage','gas_percentage_id');
		$this->renameColumn('et_ophtroperationnote_tamponade','volume','gas_volume_id');
		$this->alterColumn('et_ophtroperationnote_tamponade','gas_volume_id','int(10) unsigned NOT NULL DEFAULT 1');
		$this->createIndex('et_ophtroperationnote_tamponade_pc_id','et_ophtroperationnote_tamponade','gas_percentage_id');

		$this->addForeignKey('et_ophtroperationnote_tamponade_pc_id','et_ophtroperationnote_tamponade','gas_percentage_id','et_ophtroperationnote_gas_percentage','id');
		$this->createIndex('et_ophtroperationnote_tamponade_gv_id','et_ophtroperationnote_tamponade','gas_volume_id');
		$this->addForeignKey('et_ophtroperationnote_tamponade_gv_id','et_ophtroperationnote_tamponade','gas_volume_id','et_ophtroperationnote_gas_volume','id');
			$this->dropForeignKey('et_ophtroperationnote_procedurelist_assistant_id_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_assistant_id_fk','et_ophtroperationnote_procedurelist');
			$this->alterColumn('et_ophtroperationnote_buckle','eyedraw','varchar(4096) COLLATE utf8_bin NOT NULL');
			$this->addColumn('et_ophtroperationnote_buckle','report','varchar(4096) COLLATE utf8_bin NOT NULL');
			$this->createTable('et_ophtroperationnote_cataract_incision_site', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(16) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_cis_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_cis_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_cis_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cis_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_cataract_incision_site',array('id'=>1,'name'=>'Corneal','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_incision_site',array('id'=>2,'name'=>'Limbal','display_order'=>2));
		$this->insert('et_ophtroperationnote_cataract_incision_site',array('id'=>3,'name'=>'Scleral','display_order'=>3));

		$this->createTable('et_ophtroperationnote_cataract_incision_type', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(16) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_cit_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_cit_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_cit_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cit_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_cataract_incision_type',array('id'=>1,'name'=>'Pocket','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_incision_type',array('id'=>2,'name'=>'Section','display_order'=>2));

		$this->createTable('et_ophtroperationnote_cataract', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'incision_site_id' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'length' => 'varchar(5) COLLATE utf8_bin NOT NULL',
				'meridian' => 'varchar(5) COLLATE utf8_bin NOT NULL',
				'incision_type_id' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'eyedraw' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'report' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'wound_burn' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'iris_trauma' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'zonular_dialysis' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'pc_rupture' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'decentered_iol' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'iol_exchange' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'dropped_nucleus' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'op_cancelled' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'corneal_odema' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'iris_prolapse' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'zonular_rupture' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'vitreous_loss' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'iol_into_vitreous' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'other_iol_problem' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'choroidal_haem' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ca_incision_site_id_fk` (`incision_site_id`)',
				'KEY `et_ophtroperationnote_ca_incision_type_id_fk` (`incision_type_id`)',
				'KEY `et_ophtroperationnote_ca_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ca_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ca_incision_site_id_fk` FOREIGN KEY (`incision_site_id`) REFERENCES `et_ophtroperationnote_cataract_incision_site` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ca_incision_type_id_fk` FOREIGN KEY (`incision_type_id`) REFERENCES `et_ophtroperationnote_cataract_incision_type` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ca_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ca_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cataract', 'class_name' => 'ElementCataract', 'event_type_id' => $event_type['id'], 'display_order' => 6, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCataract'))->queryRow();

		foreach (array('361191005','415089008','225703004','64854001','75170007','231752003','88234006','231751005','373416003','373415004','417709003','414470005','69724002','172542008','308694002') as $snomed_code) {
			$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code=:snomed',array(':snomed'=>$snomed_code))->queryRow();
			$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
		}
			$this->alterColumn('et_ophtroperationnote_vitrectomy','gauge_id','int(10) unsigned NOT NULL');
		$this->alterColumn('et_ophtroperationnote_tamponade','gas_type_id','int(10) unsigned NOT NULL');
		$this->alterColumn('et_ophtroperationnote_buckle','drainage_type_id','int(10) unsigned NOT NULL');
			$this->addColumn('et_ophtroperationnote_procedurelist','eye_id','integer(10) unsigned NOT NULL');

		$this->update('et_ophtroperationnote_procedurelist',array('eye_id'=>1));
		$this->createIndex('et_ophtroperationnote_procedurelist_eye_id_fk','et_ophtroperationnote_procedurelist','eye_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_eye_id_fk','et_ophtroperationnote_procedurelist','eye_id','eye','id');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementProcedureList'))->queryRow();

		$this->insert('element_type_eye',array('element_type_id'=>$element_type['id'],'eye_id'=>2,'display_order'=>1));
		$this->insert('element_type_eye',array('element_type_id'=>$element_type['id'],'eye_id'=>1,'display_order'=>2));
			$this->addColumn('et_ophtroperationnote_cataract','vision_blue','tinyint(1) unsigned NOT NULL DEFAULT 0');

		$this->createTable('et_ophtroperationnote_cataract_iol_position', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(32) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_cip_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_cip_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_cip_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cip_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>1,'name'=>'In the bag','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>2,'name'=>'Partly in the bag','display_order'=>2));
		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>3,'name'=>'In the sulcus','display_order'=>3));
		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>4,'name'=>'Anterior chamber','display_order'=>4));
		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>5,'name'=>'Sutured posterior chamber','display_order'=>5));
		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>6,'name'=>'Iris fixated','display_order'=>6));
		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>7,'name'=>'Other','display_order'=>7));

		$this->addColumn('et_ophtroperationnote_cataract','iol_position_id','integer(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_cataract_iol_position_fk','et_ophtroperationnote_cataract','iol_position_id');
		$this->addForeignKey('et_ophtroperationnote_cataract_iol_position_fk','et_ophtroperationnote_cataract','iol_position_id','et_ophtroperationnote_cataract_iol_position','id');
			$this->addColumn('et_ophtroperationnote_procedurelist','supervising_surgeon_id','integer(10) unsigned NULL');
			$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementProcedureList'))->queryRow();

		$this->addColumn('et_ophtroperationnote_procedurelist','anaesthetist_id','integer(10) unsigned NOT NULL DEFAULT 1');
		$this->createIndex('et_ophtroperationnote_procedurelist_anaesthetist_fk','et_ophtroperationnote_procedurelist','anaesthetist_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_anaesthetist_fk','et_ophtroperationnote_procedurelist','anaesthetist_id','anaesthetist','id');

		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>5,'display_order'=>5));

		$this->addColumn('et_ophtroperationnote_procedurelist','anaesthetic_delivery_id','integer(10) unsigned NOT NULL DEFAULT 1');
		$this->createIndex('et_ophtroperationnote_procedurelist_anaesthetic_delivery_fk','et_ophtroperationnote_procedurelist','anaesthetic_delivery_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_anaesthetic_delivery_fk','et_ophtroperationnote_procedurelist','anaesthetic_delivery_id','anaesthetic_delivery','id');

		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>6,'display_order'=>6));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>7,'display_order'=>7));

		$this->createTable('et_ophtroperationnote_procedurelist_anaesthetic_agent', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'procedurelist_id' => 'int(10) unsigned NOT NULL',
				'anaesthetic_agent_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_paa_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_paa_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_paa_procedurelist_id_fk` (`procedurelist_id`)',
				'KEY `et_ophtroperationnote_paa_anaesthetic_agent_id_fk` (`anaesthetic_agent_id`)',
				'CONSTRAINT `et_ophtroperationnote_paa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_paa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_paa_procedurelist_id_fk` FOREIGN KEY (`procedurelist_id`) REFERENCES `et_ophtroperationnote_procedurelist` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_paa_anaesthetic_agent_id_fk` FOREIGN KEY (`anaesthetic_agent_id`) REFERENCES `anaesthetic_agent` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>6,'display_order'=>6));

		$this->createTable('et_ophtroperationnote_procedurelist_anaesthetic_complication', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'procedurelist_id' => 'int(10) unsigned NOT NULL',
				'anaesthetic_complication_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_pac_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_pac_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_pac_procedurelist_id_fk` (`procedurelist_id`)',
				'KEY `et_ophtroperationnote_pac_anaesthetic_complication_id_fk` (`anaesthetic_complication_id`)',
				'CONSTRAINT `et_ophtroperationnote_pac_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_pac_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_pac_procedurelist_id_fk` FOREIGN KEY (`procedurelist_id`) REFERENCES `et_ophtroperationnote_procedurelist` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_pac_anaesthetic_complication_id_fk` FOREIGN KEY (`anaesthetic_complication_id`) REFERENCES `anaesthetic_complication` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>6,'display_order'=>6));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>7,'display_order'=>7));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>8,'display_order'=>8));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>9,'display_order'=>9));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>10,'display_order'=>10));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>11,'display_order'=>11));

		$this->addColumn('et_ophtroperationnote_procedurelist','anaesthetic_comment','varchar(1024) COLLATE utf8_bin NULL');
			$this->addColumn('et_ophtroperationnote_cataract','complication_notes','varchar(4096) COLLATE utf8_bin NULL');
			$this->alterColumn('et_ophtroperationnote_cataract','vision_blue',"tinyint(1) unsigned NOT NULL DEFAULT '1'");
		$this->alterColumn('et_ophtroperationnote_cataract','report',"varchar(4096) COLLATE utf8_bin NOT NULL DEFAULT 'Continuous Circular Capsulorrhexis
Hydrodissection
Phakoemulsification of lens nucleus
Aspiration of soft lens matter'");
			$this->createTable('et_ophtroperationnote_anaesthetic',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'anaesthetic_type_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'anaesthetist_id' => 'integer(10) unsigned NOT NULL DEFAULT 1',
				'anaesthetic_delivery_id' => 'integer(10) unsigned NOT NULL DEFAULT 1',
				'anaesthetic_comment' => 'varchar(1024) COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ana_type_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ana_type_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_ana_anaesthetic_type_id_fk` (`anaesthetic_type_id`)',
				'KEY `et_ophtroperationnote_ana_anaesthetist_id_fk` (`anaesthetist_id`)',
				'KEY `et_ophtroperationnote_ana_anaesthetic_delivery_id_fk` (`anaesthetic_delivery_id`)',
				'CONSTRAINT `et_ophtroperationnote_ana_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ana_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ana_anaesthetic_type_id_fk` FOREIGN KEY (`anaesthetic_type_id`) REFERENCES `anaesthetic_type` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ana_anaesthetist_id_fk` FOREIGN KEY (`anaesthetist_id`) REFERENCES `anaesthetist` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ana_anaesthetic_delivery_id_fk` FOREIGN KEY (`anaesthetic_delivery_id`) REFERENCES `anaesthetic_delivery` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->dropForeignKey('et_ophtroperationnote_procedurelist_anaesthetist_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_anaesthetist_fk','et_ophtroperationnote_procedurelist');
		$this->dropColumn('et_ophtroperationnote_procedurelist','anaesthetist_id');

		$this->dropForeignKey('et_ophtroperationnote_procedurelist_anaesthetic_delivery_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_anaesthetic_delivery_fk','et_ophtroperationnote_procedurelist');
		$this->dropColumn('et_ophtroperationnote_procedurelist','anaesthetic_delivery_id');

		$this->dropForeignKey('et_ophtroperationnote_procedurelist_anaesthetic_type_id_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_anaesthetic_type_id_fk','et_ophtroperationnote_procedurelist');
		$this->dropColumn('et_ophtroperationnote_procedurelist','anaesthetic_type_id');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Anaesthetic', 'class_name' => 'ElementAnaesthetic', 'event_type_id' => $event_type['id'], 'display_order' => 7, 'default' => 1));
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnaesthetic'))->queryRow();
		$pl_element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementProcedureList'))->queryRow();

		$this->delete('element_type_anaesthetic_type','element_type_id='.$pl_element_type['id']);
		$this->delete('element_type_anaesthetist','element_type_id='.$pl_element_type['id']);
		$this->delete('element_type_anaesthetic_delivery','element_type_id='.$pl_element_type['id']);
		$this->delete('element_type_anaesthetic_agent','element_type_id='.$pl_element_type['id']);
		$this->delete('element_type_anaesthetic_complication','element_type_id='.$pl_element_type['id']);

		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$element_type['id'],'anaesthetist_id'=>5,'display_order'=>5));

		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>6,'display_order'=>6));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$element_type['id'],'anaesthetic_delivery_id'=>7,'display_order'=>7));

		$this->renameTable('et_ophtroperationnote_procedurelist_anaesthetic_agent','et_ophtroperationnote_anaesthetic_anaesthetic_agent');

		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$element_type['id'],'anaesthetic_agent_id'=>6,'display_order'=>6));

		$this->renameTable('et_ophtroperationnote_procedurelist_anaesthetic_complication','et_ophtroperationnote_anaesthetic_anaesthetic_complication');

		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>6,'display_order'=>6));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>7,'display_order'=>7));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>8,'display_order'=>8));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>9,'display_order'=>9));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>10,'display_order'=>10));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$element_type['id'],'anaesthetic_complication_id'=>11,'display_order'=>11));

		$this->dropColumn('et_ophtroperationnote_procedurelist','anaesthetic_comment');

		$to = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'Topical'))->queryRow();
		$lac = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LAC'))->queryRow();
		$la = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LA'))->queryRow();
		$las = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LAS'))->queryRow();
		$ga = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'GA'))->queryRow();

		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$to['id'],'display_order'=>1));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$la['id'],'display_order'=>2));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$lac['id'],'display_order'=>3));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$las['id'],'display_order'=>4));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$element_type['id'],'anaesthetic_type_id'=>$ga['id'],'display_order'=>5));
			$this->createTable('et_ophtroperationnote_surgeon',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'surgeon_id' => 'int(10) unsigned NOT NULL',
				'assistant_id' => 'int(10) unsigned DEFAULT NULL',
				'supervising_surgeon_id' => 'int(10) unsigned DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_sur_type_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_sur_type_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_sur_surgeon_id_fk` (`surgeon_id`)',
				'CONSTRAINT `et_ophtroperationnote_sur_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_sur_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_sur_surgeon_id_fk` FOREIGN KEY (`surgeon_id`) REFERENCES `consultant` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->dropForeignKey('et_ophtroperationnote_procedurelist_surgeon_id_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_surgeon_id_fk','et_ophtroperationnote_procedurelist');
		$this->dropColumn('et_ophtroperationnote_procedurelist','surgeon_id');
		$this->dropColumn('et_ophtroperationnote_procedurelist','assistant_id');
		$this->dropColumn('et_ophtroperationnote_procedurelist','supervising_surgeon_id');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Surgeon', 'class_name' => 'ElementSurgeon', 'event_type_id' => $event_type['id'], 'display_order' => 8, 'default' => 1));
			$this->update('element_type',array('display_order'=>2),"event_type_id = 4 and class_name in ('ElementMembranePeel','ElementTamponade','ElementBuckle','ElementCataract')");
		$this->update('element_type',array('display_order'=>3),"event_type_id = 4 and class_name in ('ElementAnaesthetic','ElementSurgeon')");
			$this->addColumn('et_ophtroperationnote_cataract','eyedraw2','varchar(4096) COLLATE utf8_bin NOT NULL');
			$this->createTable('et_ophtroperationnote_drugs',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_drugs_type_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_drugs_type_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_drugs_type_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_drugs_type_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('et_ophtroperationnote_drugs_drug',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'et_ophtroperationnote_drugs_id' => 'int(10) unsigned NOT NULL',
				'drug_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_dd_drugs_id_fk` (`et_ophtroperationnote_drugs_id`)',
				'KEY `et_ophtroperationnote_dd_drug_id_fk` (`drug_id`)',
				'KEY `et_ophtroperationnote_dd_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_dd_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_dd_drugs_id_fk` FOREIGN KEY (`et_ophtroperationnote_drugs_id`) REFERENCES `et_ophtroperationnote_drugs` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_dd_drug_id_fk` FOREIGN KEY (`drug_id`) REFERENCES `drug` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_dd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_dd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Drugs', 'class_name' => 'ElementDrugs', 'event_type_id' => $event_type['id'], 'display_order' => 8, 'default' => 1));
			$this->createTable('et_ophtroperationnote_cataract_complications',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_cc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_cc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_cc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('et_ophtroperationnote_cataract_complication',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'cataract_id' => 'int(10) unsigned NOT NULL',
				'complication_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_cc2_cataract_id_fk` (`cataract_id`)',
				'KEY `et_ophtroperationnote_cc2_complication_id_fk` (`complication_id`)',
				'KEY `et_ophtroperationnote_cc2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_cc2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_cc2_cataract_id_fk` FOREIGN KEY (`cataract_id`) REFERENCES `et_ophtroperationnote_cataract` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cc2_complication_id_fk` FOREIGN KEY (`complication_id`) REFERENCES `et_ophtroperationnote_cataract_complications` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cc2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cc2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>1,'name'=>'Choroidal haem','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>2,'name'=>'Corneal odema','display_order'=>2));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>3,'name'=>'Decentered IOL','display_order'=>3));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>4,'name'=>'Dropped nucleus','display_order'=>4));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>5,'name'=>'IOL exchange','display_order'=>5));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>6,'name'=>'IOL into vitreous','display_order'=>6));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>7,'name'=>'Iris prolapse','display_order'=>7));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>8,'name'=>'Iris trauma','display_order'=>8));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>9,'name'=>'Op cancelled','display_order'=>9));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>10,'name'=>'Other IOL problem','display_order'=>10));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>11,'name'=>'PC rupture','display_order'=>11));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>12,'name'=>'Vitreous loss','display_order'=>12));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>13,'name'=>'Wound burn','display_order'=>13));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>14,'name'=>'Zonular dialysis','display_order'=>14));
		$this->insert('et_ophtroperationnote_cataract_complications',array('id'=>15,'name'=>'Zonular rupture','display_order'=>15));

		$this->dropColumn('et_ophtroperationnote_cataract','wound_burn');
		$this->dropColumn('et_ophtroperationnote_cataract','iris_trauma');
		$this->dropColumn('et_ophtroperationnote_cataract','zonular_dialysis');
		$this->dropColumn('et_ophtroperationnote_cataract','pc_rupture');
		$this->dropColumn('et_ophtroperationnote_cataract','decentered_iol');
		$this->dropColumn('et_ophtroperationnote_cataract','iol_exchange');
		$this->dropColumn('et_ophtroperationnote_cataract','dropped_nucleus');
		$this->dropColumn('et_ophtroperationnote_cataract','op_cancelled');
		$this->dropColumn('et_ophtroperationnote_cataract','corneal_odema');
		$this->dropColumn('et_ophtroperationnote_cataract','iris_prolapse');
		$this->dropColumn('et_ophtroperationnote_cataract','zonular_rupture');
		$this->dropColumn('et_ophtroperationnote_cataract','vitreous_loss');
		$this->dropColumn('et_ophtroperationnote_cataract','iol_into_vitreous');
		$this->dropColumn('et_ophtroperationnote_cataract','other_iol_problem');
		$this->dropColumn('et_ophtroperationnote_cataract','choroidal_haem');
			$this->alterColumn('et_ophtroperationnote_anaesthetic','anaesthetic_delivery_id',"int(10) unsigned NOT NULL DEFAULT '5'");
			$this->dropForeignKey('et_ophtroperationnote_paa_procedurelist_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_agent');
		$this->dropIndex('et_ophtroperationnote_paa_procedurelist_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_agent');
		$this->dropColumn('et_ophtroperationnote_anaesthetic_anaesthetic_agent','procedurelist_id');

		$this->addColumn('et_ophtroperationnote_anaesthetic_anaesthetic_agent','et_ophtroperationnote_anaesthetic_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_paa_anaesthetic_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_agent','et_ophtroperationnote_anaesthetic_id');
		$this->addForeignKey('et_ophtroperationnote_paa_anaesthetic_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_agent','et_ophtroperationnote_anaesthetic_id','et_ophtroperationnote_anaesthetic','id');
			$this->dropForeignKey('et_ophtroperationnote_pac_anaesthetic_complication_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication');
		$this->dropIndex('et_ophtroperationnote_pac_anaesthetic_complication_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication');
		$this->dropColumn('et_ophtroperationnote_anaesthetic_anaesthetic_complication','anaesthetic_complication_id');

		$this->dropForeignKey('et_ophtroperationnote_pac_procedurelist_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication');
		$this->dropIndex('et_ophtroperationnote_pac_procedurelist_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication');
		$this->dropColumn('et_ophtroperationnote_anaesthetic_anaesthetic_complication','procedurelist_id');

		$this->addColumn('et_ophtroperationnote_anaesthetic_anaesthetic_complication','et_ophtroperationnote_anaesthetic_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_anaesthetic_ac_anaesthetic_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication','et_ophtroperationnote_anaesthetic_id');
		$this->addForeignKey('et_ophtroperationnote_anaesthetic_ac_anaesthetic_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication','et_ophtroperationnote_anaesthetic_id','et_ophtroperationnote_anaesthetic','id');

		$this->createTable('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'last_modified_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'last_modified_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'created_user_id' => "int(10) unsigned NOT NULL DEFAULT '1'",
				'created_date' => "datetime NOT NULL DEFAULT '1900-01-01 00:00:00'",
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_anaesthetic_ac_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_anaesthetic_ac_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_anaesthetic_ac_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_anaesthetic_ac_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Eyelid haemorrage/bruising','display_order'=>1));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Conjunctivital chemosis','display_order'=>2));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Retro bulbar / peribulbar haemorrage','display_order'=>3));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Globe/optic nerve penetration','display_order'=>4));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Inadequate akinesia','display_order'=>5));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Patient pain - Mild','display_order'=>6));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Patient pain - Moderate','display_order'=>7));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Patient pain - Severe','display_order'=>8));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Systemic problems','display_order'=>9));
		$this->insert('et_ophtroperationnote_anaesthetic_anaesthetic_complications',array('name'=>'Operation cancelled due to complication','display_order'=>10));

		$this->addColumn('et_ophtroperationnote_anaesthetic_anaesthetic_complication','anaesthetic_complication_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_anaesthetic_aca_complication_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication','anaesthetic_complication_id');
		$this->addForeignKey('et_ophtroperationnote_anaesthetic_aca_complication_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication','anaesthetic_complication_id','et_ophtroperationnote_anaesthetic_anaesthetic_complications','id');
			$this->createTable('et_ophtroperationnote_dacrocystogram', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_dacrocystogram_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_dacrocystogram_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_dacrocystogram_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_dacrocystogram_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Dacrocystogram', 'class_name' => 'ElementDacrocystogram', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystogram'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :snomed',array(':snomed'=>'56087001'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_3_snp', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_o3s_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_o3s_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_o3s_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_o3s_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Punctoplasty', 'class_name' => 'ElementPunctoplasty', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPunctoplasty'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'36'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ant_orb_conj', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oaoc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oaoc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oaoc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oaoc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Anterior orbitotomy - conjunctival approach', 'class_name' => 'ElementAnteriorOrbitotomyConjunctivalApproach', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorOrbitotomyConjunctivalApproach'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'24'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ant_vity', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oav_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oav_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oav_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oav_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Anterior vitrectomy', 'class_name' => 'ElementAnteriorVitrectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorVitrectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'41'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_bleph_both_lids', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obbl_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obbl_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obbl_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obbl_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Blepharoplasty of both lids', 'class_name' => 'ElementBlepharoplastyOfBothLids', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharoplastyOfBothLids'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'13'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_bleph_lower_lid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obll_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obll_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obll_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obll_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Blepharoplasty of lower lid', 'class_name' => 'ElementBlepharoplastyOfLowerLid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharoplastyOfLowerLid'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'12'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_bleph_upper_lid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obul_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obul_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obul_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obul_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Blepharoplasty of upper lid', 'class_name' => 'ElementBlepharoplastyOfUpperLid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharoplastyOfUpperLid'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'11'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_brow_susp_afl', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obsa_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obsa_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obsa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obsa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Brow suspension with fascia lata', 'class_name' => 'ElementBrowSuspensionWithFasciaLata', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBrowSuspensionWithFasciaLata'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'14'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_brow_susp_synth', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obss_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obss_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obss_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obss_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Brow suspension with synthetic material', 'class_name' => 'ElementBrowSuspensionWithSyntheticMaterial', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBrowSuspensionWithSyntheticMaterial'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'15'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_bx_exc_cnj', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obec_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obec_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obec_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obec_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of conjunctiva - excisional', 'class_name' => 'ElementBiopsyOfConjunctivaExcisional', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfConjunctivaExcisional'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'33'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_bx_lid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obl_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obl_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obl_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obl_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of lid - incisional', 'class_name' => 'ElementBiopsyOfLidIncisional', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfLidIncisional'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'8'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_capsulectomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Capsulectomy', 'class_name' => 'ElementCapsulectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCapsulectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'47'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_corn_sut_removal', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocsr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocsr_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocsr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocsr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of corneal suture', 'class_name' => 'ElementRemovalOfCornealSuture', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfCornealSuture'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'40'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_corneal_suture', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocs_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocs_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocs_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocs_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Suture of cornea', 'class_name' => 'ElementSutureOfCornea', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSutureOfCornea'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'38'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_cryo_collin', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_occ_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_occ_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_occ_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_occ_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cryotherapy with Collin cryoprobe', 'class_name' => 'ElementCryotherapyWithCollinCryoprobe', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyWithCollinCryoprobe'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'16'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_cryo_nitro', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocn_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocn_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocn_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocn_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cryotherapy with liquid nitrogen', 'class_name' => 'ElementCryotherapyWithLiquidNitrogen', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyWithLiquidNitrogen'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'17'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_dcr', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Dacrocystorhinostomy', 'class_name' => 'ElementDacrocystorhinostomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystorhinostomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'2'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_dcr_endo', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ode_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ode_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ode_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ode_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Dacrocystorhinostomy - endonasal', 'class_name' => 'ElementDacrocystorhinostomyEndonasal', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystorhinostomyEndonasal'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'3'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_decomp_3', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Decompression of orbit - 3 walls', 'class_name' => 'ElementDecompressionOfOrbit3Walls', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOrbit3Walls'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'22'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ectr', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Ectropion correction', 'class_name' => 'ElementEctropionCorrection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEctropionCorrection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'19'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_elctrlys', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Electrolysis of eyelash', 'class_name' => 'ElementElectrolysisOfEyelash', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementElectrolysisOfEyelash'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'34'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ent', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Entropion correction - no graft', 'class_name' => 'ElementEntropionCorrectionNoGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEntropionCorrectionNoGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'18'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_enuc__impnlt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oei_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oei_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oei_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oei_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Enucleation and implant', 'class_name' => 'ElementEnucleationAndImplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEnucleationAndImplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'28'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_evisc__ball', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oeb_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oeb_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oeb_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oeb_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Evisceration and implant', 'class_name' => 'ElementEviscerationAndImplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEviscerationAndImplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'29'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_exent', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Exenteration', 'class_name' => 'ElementExenteration', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExenteration'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'30'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_fl_harvest', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ofh_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ofh_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ofh_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ofh_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Fascia lata harvest', 'class_name' => 'ElementFasciaLataHarvest', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFasciaLataHarvest'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'37'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_fornix_mmg', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ofm_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ofm_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ofm_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ofm_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Fornix reconstruction with mucus membrane graft', 'class_name' => 'ElementFornixReconstructionWithMucusMembraneGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFornixReconstructionWithMucusMembraneGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'32'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ic', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Incision and curettage of cyst', 'class_name' => 'ElementIncisionAndCurettageOfCyst', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIncisionAndCurettageOfCyst'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'35'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lat_orbitotomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olo_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olo_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olo_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olo_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lateral orbitotomy', 'class_name' => 'ElementLateralOrbitotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralOrbitotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'26'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lid_recon__flaps', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olrf_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olrf_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olrf_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olrf_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Reconstruction of lid - local flaps', 'class_name' => 'ElementReconstructionOfLidLocalFlaps', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReconstructionOfLidLocalFlaps'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'9'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lid_recon__graft', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olrg_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olrg_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olrg_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olrg_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Reconstruction of lid with graft', 'class_name' => 'ElementReconstructionOfLidWithGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReconstructionOfLidWithGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'10'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lj_tube', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olt_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lester Jones tube', 'class_name' => 'ElementLesterJonesTube', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLesterJonesTube'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'5'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ofi', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oo_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oo_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oo_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oo_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Insertion of orbital floor implant', 'class_name' => 'ElementInsertionOfOrbitalFloorImplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfOrbitalFloorImplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'31'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orbital_biopsy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oob_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oob_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oob_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oob_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of orbit', 'class_name' => 'ElementBiopsyOfOrbit', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfOrbit'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'27'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orbital_fracture', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oof_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oof_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oof_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oof_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Repair of orbital fracture', 'class_name' => 'ElementRepairOfOrbitalFracture', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOfOrbitalFracture'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'23'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_pt_ant_lev_excn', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_opale_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_opale_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_opale_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_opale_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Ptosis correction - anterior levator excision', 'class_name' => 'ElementPtosisCorrectionAnteriorLevatorExcision', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPtosisCorrectionAnteriorLevatorExcision'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'20'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_sp', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_os_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_os_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_os_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_os_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Syringe and probe nasolacrimal duct', 'class_name' => 'ElementSyringeAndProbeNasolacrimalDuct', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSyringeAndProbeNasolacrimalDuct'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'7'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_tars_lat', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_otl_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_otl_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_otl_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_otl_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Tarsorrhaphy - lateral', 'class_name' => 'ElementTarsorrhaphyLateral', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsorrhaphyLateral'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'21'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_adjustsuture', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oa2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oa2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oa2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oa2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Corneal suture adjustment', 'class_name' => 'ElementCornealSutureAdjustment', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealSutureAdjustment'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'73'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_amniotigrft', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oa_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oa_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Amniotic membrane graft', 'class_name' => 'ElementAmnioticMembraneGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAmnioticMembraneGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'54'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ant_capsulotomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oac_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oac_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oac_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oac_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Anterior capsulotomy', 'class_name' => 'ElementAnteriorCapsulotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorCapsulotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'48'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ant_lam_keratop', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oalk_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oalk_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oalk_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oalk_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Keratoplasty - anterior lamellar', 'class_name' => 'ElementKeratoplastyAnteriorLamellar', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyAnteriorLamellar'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'92'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_astig_keratotom', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oak_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oak_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oak_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oak_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Astigmatic keratotomy', 'class_name' => 'ElementAstigmaticKeratotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAstigmaticKeratotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'56'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_bandage', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ob_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ob_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ob_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ob_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Bandage contact lens', 'class_name' => 'ElementBandageContactLens', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBandageContactLens'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('term = :term and snomed_code = :snomed_code',array(':term'=>'Insertion of bandage contact lens',':snomed_code'=>'428497007'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_capsulotomypost', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Capsulotomy (surgical)', 'class_name' => 'ElementCapsulotomySurgical', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCapsulotomySurgical'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'61'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_closure_cornea', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_occ3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_occ3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_occ3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_occ3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Corneal wound suture', 'class_name' => 'ElementCornealWoundSuture', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealWoundSuture'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'76'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_compression_sut', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocs2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocs2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocs2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocs2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Compression suture of graft', 'class_name' => 'ElementCompressionSutureOfGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCompressionSutureOfGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'66'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_corndiath', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Corneal vessel diathermy', 'class_name' => 'ElementCornealVesselDiathermy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealVesselDiathermy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'75'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_corneal_biopsy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocb_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocb_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocb_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocb_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of cornea', 'class_name' => 'ElementBiopsyOfCornea', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfCornea'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'59'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_corneal_fb', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocf_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocf_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocf_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocf_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of corneal foreign body', 'class_name' => 'ElementRemovalOfCornealForeignBody', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfCornealForeignBody'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'82'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_corneal_graft', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocg_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocg_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocg_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocg_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Keratoplasty - penetrating', 'class_name' => 'ElementKeratoplastyPenetrating', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyPenetrating'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'94'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_creatn_conjhood', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_occ2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_occ2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_occ2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_occ2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Conjunctival flap', 'class_name' => 'ElementConjunctivalFlap', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementConjunctivalFlap'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'67'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_crosslinking', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cross-linking of cornea', 'class_name' => 'ElementCrosslinkingOfCornea', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCrosslinkingOfCornea'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'77'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_debride', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Corneal debridement', 'class_name' => 'ElementCornealDebridement', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealDebridement'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'69'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_dmek', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od5_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od5_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od5_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od5_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Keratoplasty - posterior DSAEK', 'class_name' => 'ElementKeratoplastyPosteriorDSAEK', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyPosteriorDSAEK'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'96'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_dsaek', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Keratoplasty - posterior DMEK', 'class_name' => 'ElementKeratoplastyPosteriorDMEK', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyPosteriorDMEK'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'95'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_dsaek_reposition', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_odr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_odr_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_odr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_odr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'DSAEK repositioning', 'class_name' => 'ElementDSAEKRepositioning', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDSAEKRepositioning'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'78'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ecce', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe6_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe6_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe6_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe6_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Extracapsular cataract extraction', 'class_name' => 'ElementExtracapsularCataractExtraction', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExtracapsularCataractExtraction'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'79'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_edta_chelation', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oec_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oec_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oec_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oec_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Chelation of cornea', 'class_name' => 'ElementChelationOfCornea', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementChelationOfCornea'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'65'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_epikeratoplasty', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe7_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe7_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe7_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe7_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Epikeratoplasty', 'class_name' => 'ElementEpikeratoplasty', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEpikeratoplasty'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'80'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_eua', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe8_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe8_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe8_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe8_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Examination under anaesthesia', 'class_name' => 'ElementExaminationUnderAnaesthesia', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExaminationUnderAnaesthesia'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'81'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_excisconjlesion', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe5_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe5_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe5_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe5_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Excision of lesion of conjunctiva', 'class_name' => 'ElementExcisionOfLesionOfConjunctiva', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLesionOfConjunctiva'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'68'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_glue', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_og_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_og_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_og_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_og_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Corneal glue', 'class_name' => 'ElementCornealGlue', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealGlue'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'70'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_graft_tectonic', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ogt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ogt_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ogt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ogt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Keratoplasty - tectonic', 'class_name' => 'ElementKeratoplastyTectonic', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyTectonic'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'98'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_inj_eye', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oie_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oie_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oie_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oie_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Injection into eye', 'class_name' => 'ElementInjectionIntoEye', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInjectionIntoEye'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'83'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_inlay_insert', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oii_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oii_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oii_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oii_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Corneal inlay insertion', 'class_name' => 'ElementCornealInlayInsertion', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealInlayInsertion'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'71'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_inlay_removal', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oir_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oir_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oir_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oir_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Corneal inlay removal', 'class_name' => 'ElementCornealInlayRemoval', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealInlayRemoval'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'72'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_intrastromal', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Intrastromal corneal injection', 'class_name' => 'ElementIntrastromalCornealInjection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIntrastromalCornealInjection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'84'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_irrigatn_ac', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oia_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oia_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oia_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oia_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Irrigation of anterior chamber', 'class_name' => 'ElementIrrigationOfAnteriorChamber', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIrrigationOfAnteriorChamber'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'50'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_moria_alk', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oma_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oma_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oma_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oma_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Keratoplasty - automated Moria', 'class_name' => 'ElementKeratoplastyAutomatedMoria', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyAutomatedMoria'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'93'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_post_capsulotomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_opc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_opc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_opc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_opc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Posterior capsulotomy', 'class_name' => 'ElementPosteriorCapsulotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPosteriorCapsulotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'49'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_pupiloplasty', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_op_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_op_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_op_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_op_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Iridoplasty (occluder)', 'class_name' => 'ElementIridoplastyOccluder', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIridoplastyOccluder'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'90'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_pupiloplasty2', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_op2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_op2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_op2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_op2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Iridoplasty (suture)', 'class_name' => 'ElementIridoplastySuture', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIridoplastySuture'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'91'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_removal_of_sutu', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oros_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oros_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oros_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oros_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Corneal suture removal', 'class_name' => 'ElementCornealSutureRemoval', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealSutureRemoval'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'74'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_repair_iris_prolapse', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_orip_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_orip_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_orip_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_orip_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Repair of prolapsed iris', 'class_name' => 'ElementRepairOfProlapsedIris', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOfProlapsedIris'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'52'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_rotationlcorgft', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_or_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_or_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_or_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_or_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Keratoplasty - rotation autograft', 'class_name' => 'ElementKeratoplastyRotationAutograft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyRotationAutograft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'97'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_surgical_pi', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_osp_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_osp_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_osp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_osp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Surgical iridotomy', 'class_name' => 'ElementSurgicalIridotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSurgicalIridotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'53'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_yag_caps', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oyc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oyc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oyc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oyc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Capsulotomy (YAG)', 'class_name' => 'ElementCapsulotomyYAG', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCapsulotomyYAG'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'62'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_5fu', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_o5_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_o5_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_o5_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_o5_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Application of 5FU', 'class_name' => 'ElementApplicationOf5FU', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementApplicationOf5FU'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'138'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_alt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oa3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oa3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oa3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oa3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Laser trabeculoplasty', 'class_name' => 'ElementLaserTrabeculoplasty', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLaserTrabeculoplasty'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'129'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_beta_irradiation', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obi_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obi_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Application of beta radation', 'class_name' => 'ElementApplicationOfBetaRadation', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementApplicationOfBetaRadation'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'141'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_btxamuscle', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ob2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ob2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ob2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ob2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Botulinum injection eye muscle', 'class_name' => 'ElementBotulinumInjectionEyeMuscle', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBotulinumInjectionEyeMuscle'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'150'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_conj_inject', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oci_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oci_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oci_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oci_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Subconjunctival injection', 'class_name' => 'ElementSubconjunctivalInjection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSubconjunctivalInjection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'113'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_cryo', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc7_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc7_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc7_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc7_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cryotherapy to lesion of retina', 'class_name' => 'ElementCryotherapyToLesionOfRetina', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyToLesionOfRetina'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'146'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_cyclodiaclftrep', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc5_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc5_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc5_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc5_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cyclodialysis cleft repair', 'class_name' => 'ElementCyclodialysisCleftRepair', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCyclodialysisCleftRepair'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'120'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_cyclodiode', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc6_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc6_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc6_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc6_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Laser coagulation ciliary body', 'class_name' => 'ElementLaserCoagulationCiliaryBody', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLaserCoagulationCiliaryBody'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'127'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_donorsclera', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od6_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od6_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od6_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od6_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Graft to sclera', 'class_name' => 'ElementGraftToSclera', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementGraftToSclera'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'123'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_excbxpinguecula', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe9_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe9_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe9_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe9_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Pingueculum excision', 'class_name' => 'ElementPingueculumExcision', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPingueculumExcision'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'107'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_expteryconjaugf', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe10_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe10_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe10_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe10_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Pterygium excision & conj auto-grft', 'class_name' => 'ElementPterygiumExcisionConjAutogrft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPterygiumExcisionConjAutogrft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'108'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_goniotomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_og2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_og2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_og2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_og2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Goniotomy', 'class_name' => 'ElementGoniotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementGoniotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'122'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_graft_to_sclera', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ogts_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ogts_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ogts_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ogts_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Scleral graft', 'class_name' => 'ElementScleralGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementScleralGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'112'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_insaqueousshunt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Insertion of aqueous shunt', 'class_name' => 'ElementInsertionOfAqueousShunt', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfAqueousShunt'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'124'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ir_recess', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oir2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oir2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oir2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oir2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Inferior rectus recession', 'class_name' => 'ElementInferiorRectusRecession', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorRectusRecession'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'151'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ir_resect', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oir3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oir3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oir3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oir3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Inferior rectus resection', 'class_name' => 'ElementInferiorRectusResection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorRectusResection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'152'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_iridoplasty', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Iridoplasty', 'class_name' => 'ElementIridoplasty', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIridoplasty'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'125'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_kpro', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ok_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ok_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ok_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ok_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Keratoprosthesis', 'class_name' => 'ElementKeratoprosthesis', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoprosthesis'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'99'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lasekprk', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ol_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ol_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ol_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ol_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'LASEK/PRK', 'class_name' => 'ElementLASEKPRK', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLASEKPRK'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'100'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_laser_pi', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olp_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olp_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Laser iridotomy', 'class_name' => 'ElementLaserIridotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLaserIridotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'128'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lasik_flap', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olf_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olf_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olf_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olf_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'LASIK flap reposition', 'class_name' => 'ElementLASIKFlapReposition', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLASIKFlapReposition'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'102'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_limbal', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ol2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ol2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ol2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ol2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Limbal cell transplant', 'class_name' => 'ElementLimbalCellTransplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLimbalCellTransplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'103'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lr_', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ol3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ol3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ol3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ol3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lateral rectus recession', 'class_name' => 'ElementLateralRectusRecession', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralRectusRecession'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'153'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lr_2', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ol22_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ol22_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ol22_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ol22_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lateral rectus resection', 'class_name' => 'ElementLateralRectusResection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralRectusResection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'154'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_mmc', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_om2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_om2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_om2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_om2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Application of MMC', 'class_name' => 'ElementApplicationOfMMC', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementApplicationOfMMC'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'139'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_mmg', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_om_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_om_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_om_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_om_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Mucous membrane graft', 'class_name' => 'ElementMucousMembraneGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMucousMembraneGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'104'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_mr_', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_om3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_om3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_om3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_om3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Medial rectus recession', 'class_name' => 'ElementMedialRectusRecession', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMedialRectusRecession'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'155'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_needlingbleb', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_on_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_on_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_on_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_on_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Needling of bleb', 'class_name' => 'ElementNeedlingOfBleb', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementNeedlingOfBleb'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'130'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_occllacrpunctm', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oo2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oo2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oo2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oo2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Occlusion of lacrimal punctum', 'class_name' => 'ElementOcclusionOfLacrimalPunctum', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOcclusionOfLacrimalPunctum'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'105'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_penetrating_inj', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_opi_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_opi_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_opi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_opi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Repair of penetrating injury', 'class_name' => 'ElementRepairOfPenetratingInjury', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOfPenetratingInjury'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'111'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ptk', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_op3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_op3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_op3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_op3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'PTK - Laser superficial keratectomy', 'class_name' => 'ElementPTKLaserSuperficialKeratectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPTKLaserSuperficialKeratectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'109'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_reformation_ac', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ora_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ora_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ora_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ora_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Reformation of AC', 'class_name' => 'ElementReformationOfAC', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReformationOfAC'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'110'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_revaqueousshunt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_or2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_or2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_or2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_or2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Revision of aqueous shunt', 'class_name' => 'ElementRevisionOfAqueousShunt', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRevisionOfAqueousShunt'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'132'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_revision_ac', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ora2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ora2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ora2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ora2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Revision of anterior chamber', 'class_name' => 'ElementRevisionOfAnteriorChamber', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRevisionOfAnteriorChamber'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'131'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_revision_traby', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ort_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ort_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ort_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ort_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Revision of trabeculectomy', 'class_name' => 'ElementRevisionOfTrabeculectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRevisionOfTrabeculectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'134'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_superficial_k', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_osk_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_osk_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_osk_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_osk_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superficial keratectomy', 'class_name' => 'ElementSuperficialKeratectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperficialKeratectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'114'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_tattooing_corne', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_otc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_otc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_otc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_otc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Tattooing of cornea', 'class_name' => 'ElementTattooingOfCornea', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTattooingOfCornea'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'116'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_trabeculotomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ot2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ot2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ot2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ot2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Trabeculotomy', 'class_name' => 'ElementTrabeculotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTrabeculotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'140'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_traby', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ot_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ot_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ot_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ot_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Trabeculectomy', 'class_name' => 'ElementTrabeculectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTrabeculectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'137'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_adjustable', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oa4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oa4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oa4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oa4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Adjustable suture', 'class_name' => 'ElementAdjustableSuture', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAdjustableSuture'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'159'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_al_implnt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oai_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oai_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oai_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oai_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Allogenic implant', 'class_name' => 'ElementAllogenicImplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAllogenicImplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'210'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_cryo2', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc8_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc8_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc8_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc8_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cryotherapy retinopexy', 'class_name' => 'ElementCryotherapyRetinopexy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyRetinopexy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'165'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_delam', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od7_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od7_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od7_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od7_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Delamination', 'class_name' => 'ElementDelamination', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDelamination'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'166'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_drain', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od8_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od8_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od8_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od8_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'External drainage of SRF', 'class_name' => 'ElementExternalDrainageOfSRF', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExternalDrainageOfSRF'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'167'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_excision_cathal_lesion', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oecl_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oecl_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oecl_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oecl_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Excision of lesion of canthus', 'class_name' => 'ElementExcisionOfLesionOfCanthus', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLesionOfCanthus'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'195'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_excision_eyebrow_lesion', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oeel_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oeel_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oeel_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oeel_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Excision of lesion of eyebrow', 'class_name' => 'ElementExcisionOfLesionOfEyebrow', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLesionOfEyebrow'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'194'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_excision_of_lacrimal_gland', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oeolg_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oeolg_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oeolg_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oeolg_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Excision of lacrimal gland', 'class_name' => 'ElementExcisionOfLacrimalGland', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLacrimalGland'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'198'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_frag', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_of_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_of_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_of_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_of_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Fragmatome lensectomy', 'class_name' => 'ElementFragmatomeLensectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFragmatomeLensectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'170'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_gld_wt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ogw_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ogw_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ogw_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ogw_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Insertion of gold weight', 'class_name' => 'ElementInsertionOfGoldWeight', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfGoldWeight'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'197'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_haradiito', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oh_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oh_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oh_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oh_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior oblique Harada-Ito', 'class_name' => 'ElementSuperiorObliqueHaradaIto', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueHaradaIto'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'202'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ilm', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi5_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi5_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi5_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi5_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Internal limiting membrane peel', 'class_name' => 'ElementInternalLimitingMembranePeel', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInternalLimitingMembranePeel'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'172'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_intacs_', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi8_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi8_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi8_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi8_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Insertion of Intacs', 'class_name' => 'ElementInsertionOfIntacs', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfIntacs'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'191'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_intravit', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi6_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi6_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi6_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi6_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Intravitreal injection', 'class_name' => 'ElementIntravitrealInjection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIntravitrealInjection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'174'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_io_faden', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oif_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oif_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oif_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oif_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Inferior oblique Faden', 'class_name' => 'ElementInferiorObliqueFaden', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorObliqueFaden'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'160'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_iofb', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi7_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi7_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi7_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi7_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of intraocular foreign body', 'class_name' => 'ElementRemovalOfIntraocularForeignBody', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfIntraocularForeignBody'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'180'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_knapp', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ok2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ok2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ok2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ok2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Knapp procedure', 'class_name' => 'ElementKnappProcedure', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKnappProcedure'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'161'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_laser', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ol4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ol4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ol4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ol4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Laser retinopexy', 'class_name' => 'ElementLaserRetinopexy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLaserRetinopexy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'176'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lasik', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ol5_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ol5_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ol5_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ol5_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'LASIK', 'class_name' => 'ElementLASIK', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLASIK'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'208'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_mr_2', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_om22_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_om22_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_om22_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_om22_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Medial rectus resection', 'class_name' => 'ElementMedialRectusResection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMedialRectusResection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'156'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_mr_vert_trans', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_omvt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_omvt_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_omvt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_omvt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Medial rectus vertical transposition', 'class_name' => 'ElementMedialRectusVerticalTransposition', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMedialRectusVerticalTransposition'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'162'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orb_implnt_removal', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ooir_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ooir_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ooir_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ooir_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of orbital implant', 'class_name' => 'ElementRemovalOfOrbitalImplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfOrbitalImplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'207'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orb_recn', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oor_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oor_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oor_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oor_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Reconstruction of orbit', 'class_name' => 'ElementReconstructionOfOrbit', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReconstructionOfOrbit'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'193'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_pi', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_op4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_op4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_op4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_op4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Peripheral iridectomy', 'class_name' => 'ElementPeripheralIridectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPeripheralIridectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'175'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_prp', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_op5_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_op5_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_op5_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_op5_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Panretinal photocoagulation', 'class_name' => 'ElementPanretinalPhotocoagulation', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPanretinalPhotocoagulation'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'177'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_removal_of_cnv', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oroc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oroc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oroc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oroc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Subretinal membranectomy', 'class_name' => 'ElementSubretinalMembranectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSubretinalMembranectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'205'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_removal_of_gas', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_orog_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_orog_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_orog_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_orog_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of gas', 'class_name' => 'ElementRemovalOfGas', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfGas'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'206'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_removal_of_intacs', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oroi_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oroi_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oroi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oroi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of Intacs', 'class_name' => 'ElementRemovalOfIntacs', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfIntacs'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'192'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_removal_tube', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ort2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ort2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ort2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ort2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of tube from nasolacrimal duct', 'class_name' => 'ElementRemovalOfTubeFromNasolacrimalDuct', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfTubeFromNasolacrimalDuct'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'199'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ret_biopsy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_orb2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_orb2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_orb2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_orb2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of retina', 'class_name' => 'ElementBiopsyOfRetina', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfRetina'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'184'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_retinectomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_or4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_or4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_or4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_or4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Retinectomy', 'class_name' => 'ElementRetinectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRetinectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'185'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ro_buckle', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_orb_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_orb_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_orb_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_orb_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of buckle', 'class_name' => 'ElementRemovalOfBuckle', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfBuckle'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'179'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_roo', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_or3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_or3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_or3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_or3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of oil', 'class_name' => 'ElementRemovalOfOil', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfOil'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'182'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_so_', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_os3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_os3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_os3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_os3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior oblique recession', 'class_name' => 'ElementSuperiorObliqueRecession', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueRecession'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'163'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_so_tenotomy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ost_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ost_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ost_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ost_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior oblique tenotomy', 'class_name' => 'ElementSuperiorObliqueTenotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueTenotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'164'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_so_tuck', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ost2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ost2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ost2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ost2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior oblique tuck', 'class_name' => 'ElementSuperiorObliqueTuck', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueTuck'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'190'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_sr_', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_os2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_os2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_os2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_os2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior rectus recession', 'class_name' => 'ElementSuperiorRectusRecession', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorRectusRecession'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'157'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_sr_2', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_os22_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_os22_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_os22_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_os22_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior rectus resection', 'class_name' => 'ElementSuperiorRectusResection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorRectusResection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'158'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_vit_biopsy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ovb_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ovb_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ovb_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ovb_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of vitreous', 'class_name' => 'ElementBiopsyOfVitreous', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfVitreous'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'189'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_aur_cart', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oac2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oac2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oac2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oac2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Ear cartilage graft', 'class_name' => 'ElementEarCartilageGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEarCartilageGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'212'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_brow_lift__direct', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obld_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obld_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obld_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obld_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Brow lift - direct', 'class_name' => 'ElementBrowLiftDirect', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBrowLiftDirect'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'231'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_brow_lift__indirect', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obli_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obli_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obli_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obli_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Brow lift - internal', 'class_name' => 'ElementBrowLiftInternal', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBrowLiftInternal'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'232'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_canltmy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc9_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc9_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc9_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc9_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Calaniculotomy for canaliculitis', 'class_name' => 'ElementCalaniculotomyForCanaliculitis', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCalaniculotomyForCanaliculitis'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'241'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_chk_lft', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocl_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocl_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocl_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocl_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cheek lift', 'class_name' => 'ElementCheekLift', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCheekLift'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'246'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_corr_anmly', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oca_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oca_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oca_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oca_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Congenital anomaly - Correction', 'class_name' => 'ElementCongenitalAnomalyCorrection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCongenitalAnomalyCorrection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'247'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_dfg', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od9_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od9_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od9_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od9_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Dermis fat graft', 'class_name' => 'ElementDermisFatGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDermisFatGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'211'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ectr_med', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oem_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oem_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oem_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oem_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Ectropion - medial only correction', 'class_name' => 'ElementEctropionMedialOnlyCorrection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEctropionMedialOnlyCorrection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'249'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_enscpy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oe11_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oe11_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oe11_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oe11_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Endoscopy', 'class_name' => 'ElementEndoscopy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEndoscopy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'213'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ex_lid_lsn', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oell_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oell_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oell_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oell_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Excision of lid lesion - no biopsy', 'class_name' => 'ElementExcisionOfLidLesionNoBiopsy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLidLesionNoBiopsy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'252'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_exc_papilloma', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oep_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oep_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oep_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oep_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Excision of papilloma', 'class_name' => 'ElementExcisionOfPapilloma', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfPapilloma'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'238'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_hpg', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oh2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oh2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oh2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oh2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Hardpalate graft', 'class_name' => 'ElementHardpalateGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementHardpalateGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'214'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_inj_lid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oil_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oil_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oil_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oil_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Injection into eyelid', 'class_name' => 'ElementInjectionIntoEyelid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInjectionIntoEyelid'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'250'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lacrimal_gland__other', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olgo_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olgo_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olgo_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olgo_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Other procedure on lacrimal gland', 'class_name' => 'ElementOtherProcedureOnLacrimalGland', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOtherProcedureOnLacrimalGland'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'221'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lacrimal_gland_biopsy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olgb_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olgb_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olgb_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olgb_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of lacrimal gland', 'class_name' => 'ElementBiopsyOfLacrimalGland', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfLacrimalGland'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'220'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lacrimal_sac__excision', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olse_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olse_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olse_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olse_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Excision of lacrimal sac', 'class_name' => 'ElementExcisionOfLacrimalSac', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLacrimalSac'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'223'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lat_cnthplst', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olc2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olc2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olc2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olc2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Canthoplasty - Lateral ', 'class_name' => 'ElementCanthoplastyLateral', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCanthoplastyLateral'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'243'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lat_cnthpxy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Canthopexy - Lateral ', 'class_name' => 'ElementCanthopexyLateral', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCanthopexyLateral'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'242'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lee', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ol6_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ol6_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ol6_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ol6_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Canthoplasty - Medial Lee', 'class_name' => 'ElementCanthoplastyMedialLee', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCanthoplastyMedialLee'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'245'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lid_low_ant', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olla_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olla_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olla_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olla_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lid lowering anterior approach', 'class_name' => 'ElementLidLoweringAnteriorApproach', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLidLoweringAnteriorApproach'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'225'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lid_low_post', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ollp_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ollp_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ollp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ollp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lid lowering posterior approach', 'class_name' => 'ElementLidLoweringPosteriorApproach', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLidLoweringPosteriorApproach'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'226'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ll_elvtn', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ole_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ole_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ole_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ole_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lower lid elevation - specify graft material', 'class_name' => 'ElementLowerLidElevationSpecifyGraftMaterial', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLowerLidElevationSpecifyGraftMaterial'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'253'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lsac_bx', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olb_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olb_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olb_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olb_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of lacrimal sac', 'class_name' => 'ElementBiopsyOfLacrimalSac', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfLacrimalSac'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'222'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_med_cnthplsty', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_omc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_omc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_omc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_omc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Canthoplasty - Medial', 'class_name' => 'ElementCanthoplastyMedial', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCanthoplastyMedial'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'244'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_opn_eyelid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ooe_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ooe_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ooe_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ooe_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Operation on eyelid', 'class_name' => 'ElementOperationOnEyelid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOperationOnEyelid'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'239'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orb_ball_implnt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oobi_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oobi_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oobi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oobi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Insertion of orbital implant', 'class_name' => 'ElementInsertionOfOrbitalImplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfOrbitalImplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'237'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_pt_ant', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_opa_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_opa_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_opa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_opa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Ptosis correction - apo repair - anterior approach', 'class_name' => 'ElementPtosisCorrectionApoRepairAnteriorApproach', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPtosisCorrectionApoRepairAnteriorApproach'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'254'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_pt_post', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_opp_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_opp_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_opp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_opp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Ptosis correction - apo repair - posterior approach', 'class_name' => 'ElementPtosisCorrectionApoRepairPosteriorApproach', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPtosisCorrectionApoRepairPosteriorApproach'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'255'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_punctal_occl', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_opo_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_opo_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_opo_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_opo_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Punctum closure', 'class_name' => 'ElementPunctumClosure', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPunctumClosure'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'256'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_rep_canaliculus', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_orc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_orc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_orc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_orc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Repair of canaliculus', 'class_name' => 'ElementRepairOfCanaliculus', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOfCanaliculus'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'224'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_rmv_gld_wt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_orgw_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_orgw_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_orgw_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_orgw_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of gold weight', 'class_name' => 'ElementRemovalOfGoldWeight', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfGoldWeight'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'257'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_scar_rvn', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_osr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_osr_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_osr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_osr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Revision of scar', 'class_name' => 'ElementRevisionOfScar', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRevisionOfScar'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'258'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_skin_crease_reformation', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oscr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oscr_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oscr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oscr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Reformation of skin crease', 'class_name' => 'ElementReformationOfSkinCrease', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReformationOfSkinCrease'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'227'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_snb', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_os4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_os4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_os4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_os4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Sentinel node biopsy', 'class_name' => 'ElementSentinelNodeBiopsy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSentinelNodeBiopsy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'216'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_split_skin', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oss_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oss_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oss_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oss_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Split skin graft', 'class_name' => 'ElementSplitSkinGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSplitSkinGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'217'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_squint_op', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oso_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oso_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oso_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oso_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Operation for squint', 'class_name' => 'ElementOperationForSquint', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOperationForSquint'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'240'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_tars_med', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_otm_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_otm_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_otm_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_otm_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Tarsorrhaphy - medial pillar', 'class_name' => 'ElementTarsorrhaphyMedialPillar', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsorrhaphyMedialPillar'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'261'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_tars_permnt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_otp_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_otp_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_otp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_otp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Tarsorrhaphy - permanent', 'class_name' => 'ElementTarsorrhaphyPermanent', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsorrhaphyPermanent'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'259'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_tars_temp', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ott_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ott_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ott_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ott_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Tarsorrhaphy - temporary', 'class_name' => 'ElementTarsorrhaphyTemporary', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsorrhaphyTemporary'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'260'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_telecnth_wire', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_otw_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_otw_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_otw_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_otw_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Telecanthus - correction with wire', 'class_name' => 'ElementTelecanthusCorrectionWithWire', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTelecanthusCorrectionWithWire'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'234'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_absc_drng', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oad_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oad_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oad_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oad_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Drainage of eyelid abscess', 'class_name' => 'ElementDrainageOfEyelidAbscess', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrainageOfEyelidAbscess'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'262'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ant_orb_ll', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oaol_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oaol_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oaol_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oaol_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Anterior orbitotomy - lower lid approach', 'class_name' => 'ElementAnteriorOrbitotomyLowerLidApproach', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorOrbitotomyLowerLidApproach'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'283'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ant_orb_ul', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oaou_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oaou_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oaou_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oaou_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Anterior orbitotomy - upper lid approach', 'class_name' => 'ElementAnteriorOrbitotomyUpperLidApproach', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorOrbitotomyUpperLidApproach'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'284'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_blphmosis_canth', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obc2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obc2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obc2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obc2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Blepharophimosis - canthal surgery', 'class_name' => 'ElementBlepharophimosisCanthalSurgery', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharophimosisCanthalSurgery'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'265'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_botox_inj', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obi2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obi2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obi2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obi2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Botulinum toxin injection to eyelid', 'class_name' => 'ElementBotulinumToxinInjectionToEyelid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBotulinumToxinInjectionToEyelid'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'266'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_bx_cnj', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obc_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obc_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obc_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obc_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of conjunctiva - incisional', 'class_name' => 'ElementBiopsyOfConjunctivaIncisional', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfConjunctivaIncisional'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'263'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_bx_exc_ld', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obel_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obel_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obel_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obel_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of lid - excisional', 'class_name' => 'ElementBiopsyOfLidExcisional', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfLidExcisional'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'264'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_canaliculodcr', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc10_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc10_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc10_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc10_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Dacrocystorhinostomy & retrotubes', 'class_name' => 'ElementDacrocystorhinostomyRetrotubes', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystorhinostomyRetrotubes'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'269'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_dctmy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od10_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od10_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od10_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od10_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Dacrocystectomy', 'class_name' => 'ElementDacrocystectomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystectomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'268'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_deblk_ft', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_odf_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_odf_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_odf_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_odf_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Orbital fat prolapse - trans conjunctival reduction', 'class_name' => 'ElementOrbitalFatProlapseTransConjunctivalReduction', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOrbitalFatProlapseTransConjunctivalReduction'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'291'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_decmp_2_balanced', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od2b_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od2b_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od2b_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od2b_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Decompression of orbit 2(+) walls balanced approach', 'class_name' => 'ElementDecompressionOfOrbit2WallsBalancedApproach', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOrbit2WallsBalancedApproach'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'286'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_decmp_lat', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_odl_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_odl_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_odl_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_odl_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Decompression of orbit lateral wall', 'class_name' => 'ElementDecompressionOfOrbitLateralWall', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOrbitLateralWall'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'287'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_decmp_med_only', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_odmo_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_odmo_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_odmo_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_odmo_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Decompression of orbit - medial wall for neuropathy', 'class_name' => 'ElementDecompressionOfOrbitMedialWallForNeuropathy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOrbitMedialWallForNeuropathy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'285'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_dfgsock', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od12_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od12_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od12_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od12_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Dermis fat graft to socket', 'class_name' => 'ElementDermisFatGraftToSocket', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDermisFatGraftToSocket'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'293'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_drmlpm', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_od11_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_od11_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_od11_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_od11_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Dermolipoma excision (microscope)', 'class_name' => 'ElementDermolipomaExcisionMicroscope', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDermolipomaExcisionMicroscope'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'288'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_endo_rev_dcr', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oerd_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oerd_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oerd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oerd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Endonasal revision of DCR', 'class_name' => 'ElementEndonasalRevisionOfDCR', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEndonasalRevisionOfDCR'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'270'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ent_lower_lid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oell2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oell2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oell2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oell2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Entropion correction of lower eyelid', 'class_name' => 'ElementEntropionCorrectionOfLowerEyelid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEntropionCorrectionOfLowerEyelid'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'280'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ent_upper_lid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oeul_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oeul_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oeul_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oeul_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Entropion Correction of upper eyelid', 'class_name' => 'ElementEntropionCorrectionOfUpperEyelid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEntropionCorrectionOfUpperEyelid'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'281'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_enuc_no_implnt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oeni_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oeni_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oeni_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oeni_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Enucleation no implant', 'class_name' => 'ElementEnucleationNoImplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEnucleationNoImplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'294'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_evisc_no_ball', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oenb_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oenb_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oenb_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oenb_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Evisceration no implant', 'class_name' => 'ElementEviscerationNoImplant', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEviscerationNoImplant'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'295'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_frnx_recon', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ofr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ofr_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ofr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ofr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Reconstruction of eye socket with MMG', 'class_name' => 'ElementReconstructionOfEyeSocketWithMMG', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReconstructionOfEyeSocketWithMMG'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'297'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ftsg', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_of2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_of2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_of2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_of2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Full thickness skin graft', 'class_name' => 'ElementFullThicknessSkinGraft', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFullThicknessSkinGraft'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'274'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ir_faden', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oif2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oif2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oif2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oif2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Inferior rectus Faden', 'class_name' => 'ElementInferiorRectusFaden', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorRectusFaden'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'301'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_iv_steroid_injection', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oisi_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oisi_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oisi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oisi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Intravenous steroid injection', 'class_name' => 'ElementIntravenousSteroidInjection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIntravenousSteroidInjection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'278'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lacintub', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ol7_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ol7_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ol7_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ol7_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lacrimal intubation', 'class_name' => 'ElementLacrimalIntubation', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLacrimalIntubation'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'271'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lr_faden', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olf2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olf2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olf2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olf2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lateral rectus Faden', 'class_name' => 'ElementLateralRectusFaden', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralRectusFaden'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'302'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_mld', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_om5_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_om5_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_om5_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_om5_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Moulding of socket', 'class_name' => 'ElementMouldingOfSocket', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMouldingOfSocket'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'296'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_mmg2', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_om4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_om4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_om4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_om4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'MMG to ocular suface', 'class_name' => 'ElementMMGToOcularSuface', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMMGToOcularSuface'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'282'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_mr_faden', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_omf_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_omf_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_omf_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_omf_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Medial rectus Faden', 'class_name' => 'ElementMedialRectusFaden', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMedialRectusFaden'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'303'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_nasedosc', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_on2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_on2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_on2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_on2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Nasendoscopy', 'class_name' => 'ElementNasendoscopy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementNasendoscopy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'272'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orb_absc', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ooa_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ooa_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ooa_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ooa_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Drainage of orbital abscess', 'class_name' => 'ElementDrainageOfOrbitalAbscess', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrainageOfOrbitalAbscess'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'290'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orb_explrn', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ooe2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ooe2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ooe2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ooe2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Exploration of orbit', 'class_name' => 'ElementExplorationOfOrbit', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExplorationOfOrbit'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'289'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orb_fb', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oof2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oof2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oof2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oof2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of orbital foreign body', 'class_name' => 'ElementRemovalOfOrbitalForeignBody', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfOrbitalForeignBody'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'292'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orbicularismsst', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oo3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oo3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oo3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oo3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Blepharospasm - orbicularis muscle stripping', 'class_name' => 'ElementBlepharospasmOrbicularisMuscleStripping', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharospasmOrbicularisMuscleStripping'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'267'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_rem_stnt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ors_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ors_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ors_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ors_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Removal of stent', 'class_name' => 'ElementRemovalOfStent', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfStent'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'273'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_sckt_exp', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ose_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ose_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ose_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ose_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Insertion of socket expander', 'class_name' => 'ElementInsertionOfSocketExpander', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfSocketExpander'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'300'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_so_disinsertion', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_osd_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_osd_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_osd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_osd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior oblique disinsertion', 'class_name' => 'ElementSuperiorObliqueDisinsertion', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueDisinsertion'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'306'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_so_faden', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_osf_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_osf_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_osf_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_osf_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior oblique Faden', 'class_name' => 'ElementSuperiorObliqueFaden', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueFaden'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'304'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_sr_faden', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_osf2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_osf2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_osf2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_osf2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior rectus Faden', 'class_name' => 'ElementSuperiorRectusFaden', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorRectusFaden'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'305'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_biopsy_iris', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_obi3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_obi3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_obi3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_obi3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of iris', 'class_name' => 'ElementBiopsyOfIris', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfIris'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'325'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_chor_biopsy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ocb2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ocb2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ocb2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ocb2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of choroid', 'class_name' => 'ElementBiopsyOfChoroid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfChoroid'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'321'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_cyclocryo', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oc11_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oc11_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oc11_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oc11_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Cryotherapy of ciliary body', 'class_name' => 'ElementCryotherapyOfCiliaryBody', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyOfCiliaryBody'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'329'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_fsak', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_of3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_of3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_of3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_of3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Femtosecond astigmatic keratotomy', 'class_name' => 'ElementFemtosecondAstigmaticKeratotomy', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFemtosecondAstigmaticKeratotomy'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'310'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_inj_ac', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oia2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oia2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oia2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oia2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Injection of anterior chamber of eye', 'class_name' => 'ElementInjectionOfAnteriorChamberOfEye', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInjectionOfAnteriorChamberOfEye'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'324'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_intralasik', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi9_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi9_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi9_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi9_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'IntraLASIK Eye Surgery', 'class_name' => 'ElementIntraLASIKEyeSurgery', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIntraLASIKEyeSurgery'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'311'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_io_', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oi10_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oi10_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oi10_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oi10_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Inferior oblique disinsertion', 'class_name' => 'ElementInferiorObliqueDisinsertion', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorObliqueDisinsertion'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'317'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_io_ant_trans', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oiat_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oiat_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oiat_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oiat_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Inferior oblique anterior transposition', 'class_name' => 'ElementInferiorObliqueAnteriorTransposition', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorObliqueAnteriorTransposition'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'316'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ir_transposition', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oit_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oit_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oit_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oit_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Inferior rectus horizontal transposition', 'class_name' => 'ElementInferiorRectusHorizontalTransposition', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorRectusHorizontalTransposition'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'315'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lr_vert_trans', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olvt_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olvt_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olvt_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olvt_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lateral rectus vertical transposition', 'class_name' => 'ElementLateralRectusVerticalTransposition', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralRectusVerticalTransposition'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'307'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_onsdopticdecom', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oo4_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oo4_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oo4_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oo4_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Decompression of optic nerve', 'class_name' => 'ElementDecompressionOfOpticNerve', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOpticNerve'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'314'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_periocular_steroid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ops_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ops_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ops_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ops_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Periocular steroid injection', 'class_name' => 'ElementPeriocularSteroidInjection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPeriocularSteroidInjection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'327'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_plaque', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_op6_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_op6_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_op6_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_op6_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Radioactive plaque', 'class_name' => 'ElementRadioactivePlaque', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRadioactivePlaque'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'328'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_retro_steroid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ors2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ors2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ors2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ors2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Retrobulbar steroid injection', 'class_name' => 'ElementRetrobulbarSteroidInjection', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRetrobulbarSteroidInjection'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'326'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_so_ant_transposition', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_osat_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_osat_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_osat_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_osat_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior oblique anterior transposition', 'class_name' => 'ElementSuperiorObliqueAnteriorTransposition', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueAnteriorTransposition'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'320'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_squint_opn', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oso2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oso2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oso2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oso2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Combined operation on eye muscles', 'class_name' => 'ElementCombinedOperationOnEyeMuscles', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCombinedOperationOnEyeMuscles'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'313'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_sr_transposition', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ost3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ost3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ost3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ost3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Superior rectus horizontal transposition', 'class_name' => 'ElementSuperiorRectusHorizontalTransposition', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorRectusHorizontalTransposition'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'319'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_tempartrybiopsy', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ot3_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ot3_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ot3_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ot3_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Biopsy of temporal artery', 'class_name' => 'ElementBiopsyOfTemporalArtery', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfTemporalArtery'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('id = :id',array(':id'=>'312'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSurgeon'))->queryRow();
		$this->update('element_type',array('display_order'=>4),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrugs'))->queryRow();
		$this->update('element_type',array('display_order'=>5),'id='.$element_type['id']);

		$this->createTable('et_ophtroperationnote_comments',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'postop_instructions' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_comments_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_comments_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_comments_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_comments_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('element_type', array('name' => 'Comments', 'class_name' => 'ElementComments', 'event_type_id' => $event_type['id'], 'display_order' => 6, 'default' => 1));
			$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:event_type_id',array(':name'=>'Drugs',':event_type_id'=>$event_type['id']))->queryRow();

		$this->update('element_type',array('name'=>'Post-op drugs','class_name'=>'ElementPostOpDrugs'),'id='.$element_type['id']);

		$this->dropForeignKey('et_ophtroperationnote_drugs_type_created_user_id_fk','et_ophtroperationnote_drugs');
		$this->dropForeignKey('et_ophtroperationnote_drugs_type_last_modified_user_id_fk','et_ophtroperationnote_drugs');
		$this->dropIndex('et_ophtroperationnote_drugs_type_created_user_id_fk','et_ophtroperationnote_drugs');
		$this->dropIndex('et_ophtroperationnote_drugs_type_last_modified_user_id_fk','et_ophtroperationnote_drugs');
		$this->createIndex('et_ophtroperationnote_postop_drugs_last_modified_user_id_fk','et_ophtroperationnote_drugs','last_modified_user_id');
		$this->createIndex('et_ophtroperationnote_postop_drugs_created_user_id_fk','et_ophtroperationnote_drugs','created_user_id');
		$this->addForeignKey('et_ophtroperationnote_postop_drugs_last_modified_user_id_fk','et_ophtroperationnote_drugs','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_postop_drugs_created_user_id_fk','et_ophtroperationnote_drugs','created_user_id','user','id');
		$this->renameTable('et_ophtroperationnote_drugs','et_ophtroperationnote_postop_drugs');

		$this->dropForeignKey('et_ophtroperationnote_dd_drugs_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_dd_drug_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_dd_created_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_dd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_dd_drugs_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_dd_drug_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_dd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_dd_created_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->renameColumn('et_ophtroperationnote_drugs_drug','et_ophtroperationnote_drugs_id','et_ophtroperationnote_postop_drugs_id');
		$this->createIndex('et_ophtroperationnote_pdd_created_user_id_fk','et_ophtroperationnote_drugs_drug','created_user_id');
		$this->createIndex('et_ophtroperationnote_pdd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug','last_modified_user_id');
		$this->createIndex('et_ophtroperationnote_pdd_drug_id_fk','et_ophtroperationnote_drugs_drug','drug_id');
		$this->createIndex('et_ophtroperationnote_pdd_drugs_id_fk','et_ophtroperationnote_drugs_drug','et_ophtroperationnote_postop_drugs_id');
		$this->addForeignKey('et_ophtroperationnote_pdd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_pdd_created_user_id_fk','et_ophtroperationnote_drugs_drug','created_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_pdd_drug_id_fk','et_ophtroperationnote_drugs_drug','drug_id','drug','id');
		$this->addForeignKey('et_ophtroperationnote_pdd_drugs_id_fk','et_ophtroperationnote_drugs_drug','et_ophtroperationnote_postop_drugs_id','et_ophtroperationnote_postop_drugs','id');
		$this->renameTable('et_ophtroperationnote_drugs_drug','et_ophtroperationnote_postop_drugs_drug');
			$this->createTable('et_ophtroperationnote_cataract_operative_device',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'cataract_id' => 'int(10) unsigned NOT NULL',
				'operative_device_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_ccd_cataract_id_fk` (`cataract_id`)',
				'KEY `et_ophtroperationnote_ccd_operative_device_id_fk` (`operative_device_id`)',
				'KEY `et_ophtroperationnote_ccd_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ccd_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ccd_cataract_id_fk` FOREIGN KEY (`cataract_id`) REFERENCES `et_ophtroperationnote_cataract` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ccd_operative_device_id_fk` FOREIGN KEY (`operative_device_id`) REFERENCES `operative_device` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ccd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ccd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
			$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>2),'id=1');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>3),'id=2');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>4),'id=3');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>5),'id=4');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>6),'id=5');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>7),'id=6');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>8),'id=7');

		$this->insert('et_ophtroperationnote_cataract_iol_position',array('id'=>8,'name'=>'None','display_order'=>1));
			$this->addColumn('et_ophtroperationnote_cataract','iol_power','varchar(5) COLLATE utf8_bin NOT NULL');
			$this->dropColumn('et_ophtroperationnote_cataract','vision_blue');
			$this->createTable('et_ophtroperationnote_cataract_iol_type',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_cot_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_cot_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_cot_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cot_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_cataract_iol_type',array('name'=>'Type 1','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('name'=>'Type 2','display_order'=>2));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('name'=>'Type 3','display_order'=>3));

		$this->addColumn('et_ophtroperationnote_cataract','iol_type_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_cataract_iol_type_id_fk','et_ophtroperationnote_cataract','iol_type_id');
		$this->addForeignKey('et_ophtroperationnote_cataract_iol_type_id_fk','et_ophtroperationnote_cataract','iol_type_id','et_ophtroperationnote_cataract_iol_type','id');
			$this->delete('et_ophtroperationnote_cataract_iol_type');

		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>1,'name'=>'SA60AT','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>2,'name'=>'SN60WF','display_order'=>2));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>3,'name'=>'MA60MA','display_order'=>3));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>4,'name'=>'MA60AC','display_order'=>4));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>5,'name'=>'CZ70BD','display_order'=>5));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>6,'name'=>'MTA3UO','display_order'=>6));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>7,'name'=>'MTA4UO','display_order'=>7));
			$this->createTable('et_ophtroperationnote_site_subspecialty_postop_instructions',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'site_id' => 'int(10) unsigned NOT NULL',
				'subspecialty_id' => 'int(10) unsigned NOT NULL',
				'content' => 'varchar(1024) COLLATE utf8_bin NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_sspi_site_id` (`site_id`)',
				'KEY `et_ophtroperationnote_sspi_subspecialty_id` (`subspecialty_id`)',
				'KEY `et_ophtroperationnote_sspi_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_sspi_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_sspi_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_sspi_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_sspi_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_sspi_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		if ($subspecialty = $this->dbConnection->createCommand()->select('id')->from('subspecialty')->where('id=:id', array(':id'=>4))->queryRow()) {
			$this->insert('et_ophtroperationnote_site_subspecialty_postop_instructions',array('id'=>1,'site_id'=>1,'subspecialty_id'=>$subspecialty['id'],'content'=>'Use drops three times a day','display_order'=>1));
			$this->insert('et_ophtroperationnote_site_subspecialty_postop_instructions',array('id'=>2,'site_id'=>1,'subspecialty_id'=>$subspecialty['id'],'content'=>'Use drops four times a day','display_order'=>2));
		}
			$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Procedure list'))->queryRow();
		$this->update('element_type',array('display_order'=>1),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Vitrectomy'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Membrane peel'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Tamponade'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Buckle'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Cataract'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Anaesthetic'))->queryRow();
		$this->update('element_type',array('display_order'=>3),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Surgeon'))->queryRow();
		$this->update('element_type',array('display_order'=>4),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Post-op drugs'))->queryRow();
		$this->update('element_type',array('display_order'=>5),'id='.$element_type['id']);
	}

	public function down() {
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Procedure list'))->queryRow();
		$this->update('element_type',array('display_order'=>1),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Vitrectomy'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Membrane peel'))->queryRow();
		$this->update('element_type',array('display_order'=>3),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Tamponade'))->queryRow();
		$this->update('element_type',array('display_order'=>4),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Buckle'))->queryRow();
		$this->update('element_type',array('display_order'=>5),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Cataract'))->queryRow();
		$this->update('element_type',array('display_order'=>6),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Anaesthetic'))->queryRow();
		$this->update('element_type',array('display_order'=>7),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Surgeon'))->queryRow();
		$this->update('element_type',array('display_order'=>4),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Post-op drugs'))->queryRow();
		$this->update('element_type',array('display_order'=>5),'id='.$element_type['id']);
	
	
		$this->dropTable('et_ophtroperationnote_site_subspecialty_postop_instructions');
	
	
		$this->delete('et_ophtroperationnote_cataract_iol_type');

		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>1,'name'=>'Type 1','display_order'=>1));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>2,'name'=>'Type 2','display_order'=>2));
		$this->insert('et_ophtroperationnote_cataract_iol_type',array('id'=>3,'name'=>'Type 3','display_order'=>3));
	
	
		$this->dropForeignKey('et_ophtroperationnote_cataract_iol_type_id_fk','et_ophtroperationnote_cataract');
		$this->dropIndex('et_ophtroperationnote_cataract_iol_type_id_fk','et_ophtroperationnote_cataract');
		$this->dropColumn('et_ophtroperationnote_cataract','iol_type_id');

		$this->dropTable('et_ophtroperationnote_cataract_iol_type');
	
		$this->addColumn('et_ophtroperationnote_cataract','vision_blue',"tinyint(1) unsigned NOT NULL DEFAULT '1'");
	
		$this->dropColumn('et_ophtroperationnote_cataract','iol_power');
	
		$this->delete('et_ophtroperationnote_cataract_iol_position','id=8');

		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>1),'id=1');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>2),'id=2');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>3),'id=3');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>4),'id=4');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>5),'id=5');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>6),'id=6');
		$this->update('et_ophtroperationnote_cataract_iol_position',array('display_order'=>7),'id=7');
	
		$this->dropTable('et_ophtroperationnote_cataract_operative_device');
	
		$this->renameTable('et_ophtroperationnote_postop_drugs_drug','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_pdd_drugs_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_pdd_drug_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_pdd_created_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_pdd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_pdd_created_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_pdd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_pdd_drug_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_pdd_drugs_id_fk','et_ophtroperationnote_drugs_drug');
		$this->renameColumn('et_ophtroperationnote_drugs_drug','et_ophtroperationnote_postop_drugs_id','et_ophtroperationnote_drugs_id');
		$this->createIndex('et_ophtroperationnote_dd_drugs_id_fk','et_ophtroperationnote_drugs_drug','et_ophtroperationnote_drugs_id');
		$this->createIndex('et_ophtroperationnote_dd_drug_id_fk','et_ophtroperationnote_drugs_drug','drug_id');
		$this->createIndex('et_ophtroperationnote_dd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug','last_modified_user_id');
		$this->createIndex('et_ophtroperationnote_dd_created_user_id_fk','et_ophtroperationnote_drugs_drug','created_user_id');
		$this->addForeignKey('et_ophtroperationnote_dd_drugs_id_fk','et_ophtroperationnote_drugs_drug','et_ophtroperationnote_drugs_id','et_ophtroperationnote_postop_drugs','id');
		$this->addForeignKey('et_ophtroperationnote_dd_drug_id_fk','et_ophtroperationnote_drugs_drug','drug_id','drug','id');
		$this->addForeignKey('et_ophtroperationnote_dd_created_user_id_fk','et_ophtroperationnote_drugs_drug','created_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_dd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug','last_modified_user_id','user','id');

		$this->dropForeignKey('et_ophtroperationnote_postop_drugs_created_user_id_fk','et_ophtroperationnote_postop_drugs');
		$this->dropForeignKey('et_ophtroperationnote_postop_drugs_last_modified_user_id_fk','et_ophtroperationnote_postop_drugs');
		$this->dropIndex('et_ophtroperationnote_postop_drugs_created_user_id_fk','et_ophtroperationnote_postop_drugs');
		$this->dropIndex('et_ophtroperationnote_postop_drugs_last_modified_user_id_fk','et_ophtroperationnote_postop_drugs');
		$this->createIndex('et_ophtroperationnote_drugs_type_last_modified_user_id_fk','et_ophtroperationnote_postop_drugs','last_modified_user_id');
		$this->createIndex('et_ophtroperationnote_drugs_type_created_user_id_fk','et_ophtroperationnote_postop_drugs','created_user_id');
		$this->addForeignKey('et_ophtroperationnote_drugs_type_last_modified_user_id_fk','et_ophtroperationnote_postop_drugs','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_drugs_type_created_user_id_fk','et_ophtroperationnote_postop_drugs','created_user_id','user','id');
		$this->renameTable('et_ophtroperationnote_postop_drugs','et_ophtroperationnote_drugs');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:event_type_id',array(':name'=>'Post-op drugs',':event_type_id'=>$event_type['id']))->queryRow();

		$this->update('element_type',array('name'=>'Drugs','class_name'=>'ElementDrugs'),'id='.$element_type['id']);
	

		$this->dropTable('et_ophtroperationnote_comments');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementComments'))->queryRow();
		
		$this->delete('element_type','id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSurgeon'))->queryRow();
		$this->update('element_type',array('display_order'=>3),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrugs'))->queryRow();
		$this->update('element_type',array('display_order'=>8),'id='.$element_type['id']);
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfTemporalArtery'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_tempartrybiopsy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorRectusHorizontalTransposition'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_sr_transposition');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCombinedOperationOnEyeMuscles'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_squint_opn');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueAnteriorTransposition'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_so_ant_transposition');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRetrobulbarSteroidInjection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_retro_steroid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRadioactivePlaque'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_plaque');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPeriocularSteroidInjection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_periocular_steroid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOpticNerve'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_onsdopticdecom');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralRectusVerticalTransposition'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lr_vert_trans');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorRectusHorizontalTransposition'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ir_transposition');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorObliqueAnteriorTransposition'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_io_ant_trans');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorObliqueDisinsertion'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_io_');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIntraLASIKEyeSurgery'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_intralasik');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInjectionOfAnteriorChamberOfEye'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_inj_ac');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFemtosecondAstigmaticKeratotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_fsak');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyOfCiliaryBody'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_cyclocryo');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfChoroid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_chor_biopsy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfIris'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_biopsy_iris');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorRectusFaden'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_sr_faden');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueFaden'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_so_faden');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueDisinsertion'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_so_disinsertion');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfSocketExpander'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_sckt_exp');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfStent'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_rem_stnt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharospasmOrbicularisMuscleStripping'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orbicularismsst');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfOrbitalForeignBody'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orb_fb');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExplorationOfOrbit'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orb_explrn');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrainageOfOrbitalAbscess'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orb_absc');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementNasendoscopy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_nasedosc');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMedialRectusFaden'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_mr_faden');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMMGToOcularSuface'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_mmg2');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMouldingOfSocket'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_mld');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralRectusFaden'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lr_faden');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLacrimalIntubation'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lacintub');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIntravenousSteroidInjection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_iv_steroid_injection');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorRectusFaden'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ir_faden');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFullThicknessSkinGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ftsg');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReconstructionOfEyeSocketWithMMG'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_frnx_recon');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEviscerationNoImplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_evisc_no_ball');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEnucleationNoImplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_enuc_no_implnt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEntropionCorrectionOfUpperEyelid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ent_upper_lid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEntropionCorrectionOfLowerEyelid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ent_lower_lid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEndonasalRevisionOfDCR'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_endo_rev_dcr');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDermolipomaExcisionMicroscope'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_drmlpm');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDermisFatGraftToSocket'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dfgsock');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOrbitMedialWallForNeuropathy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_decmp_med_only');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOrbitLateralWall'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_decmp_lat');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOrbit2WallsBalancedApproach'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_decmp_2_balanced');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOrbitalFatProlapseTransConjunctivalReduction'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_deblk_ft');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dctmy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystorhinostomyRetrotubes'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_canaliculodcr');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfLidExcisional'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_bx_exc_ld');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfConjunctivaIncisional'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_bx_cnj');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBotulinumToxinInjectionToEyelid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_botox_inj');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharophimosisCanthalSurgery'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_blphmosis_canth');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorOrbitotomyUpperLidApproach'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ant_orb_ul');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorOrbitotomyLowerLidApproach'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ant_orb_ll');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrainageOfEyelidAbscess'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_absc_drng');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTelecanthusCorrectionWithWire'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_telecnth_wire');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsorrhaphyTemporary'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_tars_temp');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsorrhaphyPermanent'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_tars_permnt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsorrhaphyMedialPillar'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_tars_med');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOperationForSquint'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_squint_op');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSplitSkinGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_split_skin');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSentinelNodeBiopsy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_snb');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReformationOfSkinCrease'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_skin_crease_reformation');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRevisionOfScar'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_scar_rvn');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfGoldWeight'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_rmv_gld_wt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOfCanaliculus'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_rep_canaliculus');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPunctumClosure'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_punctal_occl');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPtosisCorrectionApoRepairPosteriorApproach'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_pt_post');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPtosisCorrectionApoRepairAnteriorApproach'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_pt_ant');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfOrbitalImplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orb_ball_implnt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOperationOnEyelid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_opn_eyelid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCanthoplastyMedial'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_med_cnthplsty');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfLacrimalSac'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lsac_bx');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLowerLidElevationSpecifyGraftMaterial'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ll_elvtn');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLidLoweringPosteriorApproach'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lid_low_post');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLidLoweringAnteriorApproach'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lid_low_ant');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCanthoplastyMedialLee'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lee');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCanthopexyLateral'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lat_cnthpxy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCanthoplastyLateral'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lat_cnthplst');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLacrimalSac'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lacrimal_sac__excision');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfLacrimalGland'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lacrimal_gland_biopsy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOtherProcedureOnLacrimalGland'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lacrimal_gland__other');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInjectionIntoEyelid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_inj_lid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementHardpalateGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_hpg');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfPapilloma'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_exc_papilloma');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLidLesionNoBiopsy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ex_lid_lsn');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEndoscopy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_enscpy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEctropionMedialOnlyCorrection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ectr_med');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDermisFatGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dfg');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCongenitalAnomalyCorrection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_corr_anmly');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCheekLift'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_chk_lft');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCalaniculotomyForCanaliculitis'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_canltmy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBrowLiftInternal'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_brow_lift__indirect');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBrowLiftDirect'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_brow_lift__direct');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEarCartilageGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_aur_cart');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfVitreous'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_vit_biopsy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorRectusResection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_sr_2');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorRectusRecession'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_sr_');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueTuck'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_so_tuck');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueTenotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_so_tenotomy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueRecession'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_so_');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfOil'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_roo');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfBuckle'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ro_buckle');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRetinectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_retinectomy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfRetina'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ret_biopsy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfTubeFromNasolacrimalDuct'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_removal_tube');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfIntacs'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_removal_of_intacs');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfGas'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_removal_of_gas');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSubretinalMembranectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_removal_of_cnv');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPanretinalPhotocoagulation'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_prp');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPeripheralIridectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_pi');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReconstructionOfOrbit'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orb_recn');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfOrbitalImplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orb_implnt_removal');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMedialRectusVerticalTransposition'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_mr_vert_trans');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMedialRectusResection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_mr_2');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLASIK'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lasik');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLaserRetinopexy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_laser');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKnappProcedure'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_knapp');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfIntraocularForeignBody'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_iofb');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorObliqueFaden'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_io_faden');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIntravitrealInjection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_intravit');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfIntacs'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_intacs_');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInternalLimitingMembranePeel'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ilm');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperiorObliqueHaradaIto'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_haradiito');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfGoldWeight'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_gld_wt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFragmatomeLensectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_frag');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLacrimalGland'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_excision_of_lacrimal_gland');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLesionOfEyebrow'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_excision_eyebrow_lesion');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLesionOfCanthus'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_excision_cathal_lesion');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExternalDrainageOfSRF'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_drain');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDelamination'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_delam');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyRetinopexy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_cryo2');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAllogenicImplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_al_implnt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAdjustableSuture'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_adjustable');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTrabeculectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_traby');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTrabeculotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_trabeculotomy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTattooingOfCornea'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_tattooing_corne');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSuperficialKeratectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_superficial_k');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRevisionOfTrabeculectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_revision_traby');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRevisionOfAnteriorChamber'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_revision_ac');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRevisionOfAqueousShunt'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_revaqueousshunt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReformationOfAC'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_reformation_ac');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPTKLaserSuperficialKeratectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ptk');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOfPenetratingInjury'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_penetrating_inj');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOcclusionOfLacrimalPunctum'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_occllacrpunctm');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementNeedlingOfBleb'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_needlingbleb');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMedialRectusRecession'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_mr_');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMucousMembraneGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_mmg');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementApplicationOfMMC'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_mmc');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralRectusResection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lr_2');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralRectusRecession'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lr_');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLimbalCellTransplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_limbal');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLASIKFlapReposition'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lasik_flap');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLaserIridotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_laser_pi');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLASEKPRK'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lasekprk');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoprosthesis'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_kpro');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIridoplasty'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_iridoplasty');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorRectusResection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ir_resect');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInferiorRectusRecession'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ir_recess');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfAqueousShunt'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_insaqueousshunt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementScleralGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_graft_to_sclera');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementGoniotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_goniotomy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPterygiumExcisionConjAutogrft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_expteryconjaugf');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPingueculumExcision'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_excbxpinguecula');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementGraftToSclera'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_donorsclera');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLaserCoagulationCiliaryBody'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_cyclodiode');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCyclodialysisCleftRepair'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_cyclodiaclftrep');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyToLesionOfRetina'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_cryo');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSubconjunctivalInjection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_conj_inject');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBotulinumInjectionEyeMuscle'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_btxamuscle');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementApplicationOfBetaRadation'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_beta_irradiation');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLaserTrabeculoplasty'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_alt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementApplicationOf5FU'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_5fu');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCapsulotomyYAG'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_yag_caps');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSurgicalIridotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_surgical_pi');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyRotationAutograft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_rotationlcorgft');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOfProlapsedIris'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_repair_iris_prolapse');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealSutureRemoval'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_removal_of_sutu');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIridoplastySuture'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_pupiloplasty2');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIridoplastyOccluder'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_pupiloplasty');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPosteriorCapsulotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_post_capsulotomy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyAutomatedMoria'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_moria_alk');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIrrigationOfAnteriorChamber'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_irrigatn_ac');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIntrastromalCornealInjection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_intrastromal');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealInlayRemoval'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_inlay_removal');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealInlayInsertion'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_inlay_insert');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInjectionIntoEye'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_inj_eye');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyTectonic'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_graft_tectonic');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealGlue'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_glue');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExcisionOfLesionOfConjunctiva'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_excisconjlesion');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExaminationUnderAnaesthesia'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_eua');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEpikeratoplasty'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_epikeratoplasty');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementChelationOfCornea'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_edta_chelation');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExtracapsularCataractExtraction'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ecce');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDSAEKRepositioning'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dsaek_reposition');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyPosteriorDMEK'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dsaek');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyPosteriorDSAEK'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dmek');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealDebridement'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_debride');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCrosslinkingOfCornea'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_crosslinking');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementConjunctivalFlap'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_creatn_conjhood');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyPenetrating'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_corneal_graft');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfCornealForeignBody'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_corneal_fb');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfCornea'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_corneal_biopsy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealVesselDiathermy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_corndiath');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCompressionSutureOfGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_compression_sut');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealWoundSuture'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_closure_cornea');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCapsulotomySurgical'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_capsulotomypost');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBandageContactLens'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_bandage');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAstigmaticKeratotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_astig_keratotom');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementKeratoplastyAnteriorLamellar'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ant_lam_keratop');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorCapsulotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ant_capsulotomy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAmnioticMembraneGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_amniotigrft');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCornealSutureAdjustment'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_adjustsuture');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsorrhaphyLateral'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_tars_lat');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSyringeAndProbeNasolacrimalDuct'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_sp');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPtosisCorrectionAnteriorLevatorExcision'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_pt_ant_lev_excn');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOfOrbitalFracture'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orbital_fracture');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfOrbit'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orbital_biopsy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementInsertionOfOrbitalFloorImplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ofi');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLesterJonesTube'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lj_tube');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReconstructionOfLidWithGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lid_recon__graft');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementReconstructionOfLidLocalFlaps'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lid_recon__flaps');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLateralOrbitotomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lat_orbitotomy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementIncisionAndCurettageOfCyst'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ic');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFornixReconstructionWithMucusMembraneGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_fornix_mmg');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementFasciaLataHarvest'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_fl_harvest');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementExenteration'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_exent');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEviscerationAndImplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_evisc__ball');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEnucleationAndImplant'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_enuc__impnlt');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEntropionCorrectionNoGraft'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ent');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementElectrolysisOfEyelash'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_elctrlys');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementEctropionCorrection'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ectr');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDecompressionOfOrbit3Walls'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_decomp_3');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystorhinostomyEndonasal'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dcr_endo');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystorhinostomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dcr');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyWithLiquidNitrogen'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_cryo_nitro');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCryotherapyWithCollinCryoprobe'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_cryo_collin');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSutureOfCornea'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_corneal_suture');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemovalOfCornealSuture'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_corn_sut_removal');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCapsulectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_capsulectomy');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfLidIncisional'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_bx_lid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBiopsyOfConjunctivaExcisional'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_bx_exc_cnj');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBrowSuspensionWithSyntheticMaterial'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_brow_susp_synth');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBrowSuspensionWithFasciaLata'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_brow_susp_afl');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharoplastyOfUpperLid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_bleph_upper_lid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharoplastyOfLowerLid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_bleph_lower_lid');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBlepharoplastyOfBothLids'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_bleph_both_lids');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorVitrectomy'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ant_vity');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnteriorOrbitotomyConjunctivalApproach'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ant_orb_conj');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPunctoplasty'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_3_snp');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDacrocystogram'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_dacrocystogram');
	
		$this->dropForeignKey('et_ophtroperationnote_anaesthetic_aca_complication_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication');
		$this->dropIndex('et_ophtroperationnote_anaesthetic_aca_complication_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication');
		$this->dropColumn('et_ophtroperationnote_anaesthetic_anaesthetic_complication','anaesthetic_complication_id');

		$this->dropTable('et_ophtroperationnote_anaesthetic_anaesthetic_complications');

		$this->dropForeignKey('et_ophtroperationnote_anaesthetic_ac_anaesthetic_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication');
		$this->dropIndex('et_ophtroperationnote_anaesthetic_ac_anaesthetic_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication');
		$this->dropColumn('et_ophtroperationnote_anaesthetic_anaesthetic_complication','et_ophtroperationnote_anaesthetic_id');

		$this->delete('et_ophtroperationnote_anaesthetic_anaesthetic_complication');

		$this->addColumn('et_ophtroperationnote_anaesthetic_anaesthetic_complication','procedurelist_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_pac_procedurelist_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication','procedurelist_id');
		$this->addForeignKey('et_ophtroperationnote_pac_procedurelist_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication','procedurelist_id','et_ophtroperationnote_procedurelist','id');

		$this->addColumn('et_ophtroperationnote_anaesthetic_anaesthetic_complication','anaesthetic_complication_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_pac_anaesthetic_complication_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication','anaesthetic_complication_id');
		$this->addForeignKey('et_ophtroperationnote_pac_anaesthetic_complication_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_complication','anaesthetic_complication_id','anaesthetic_complication','id');
	
		$this->dropForeignKey('et_ophtroperationnote_paa_anaesthetic_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_agent');
		$this->dropIndex('et_ophtroperationnote_paa_anaesthetic_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_agent');
		$this->dropColumn('et_ophtroperationnote_anaesthetic_anaesthetic_agent','et_ophtroperationnote_anaesthetic_id');

		$this->delete('et_ophtroperationnote_anaesthetic_anaesthetic_agent');

		$this->addColumn('et_ophtroperationnote_anaesthetic_anaesthetic_agent','procedurelist_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_paa_procedurelist_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_agent','procedurelist_id');
		$this->addForeignKey('et_ophtroperationnote_paa_procedurelist_id_fk','et_ophtroperationnote_anaesthetic_anaesthetic_agent','procedurelist_id','et_ophtroperationnote_procedurelist','id');
	
		$this->alterColumn('et_ophtroperationnote_anaesthetic','anaesthetic_delivery_id',"int(10) unsigned NOT NULL DEFAULT '1'");
	
		$this->dropTable('et_ophtroperationnote_cataract_complication');
		$this->dropTable('et_ophtroperationnote_cataract_complications');

		$this->addColumn('et_ophtroperationnote_cataract','wound_burn','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','iris_trauma','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','zonular_dialysis','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','pc_rupture','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','decentered_iol','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','iol_exchange','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','dropped_nucleus','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','op_cancelled','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','corneal_odema','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','iris_prolapse','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','zonular_rupture','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','vitreous_loss','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','iol_into_vitreous','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','other_iol_problem','tinyint(1) unsigned NOT NULL DEFAULT 0');
		$this->addColumn('et_ophtroperationnote_cataract','choroidal_haem','tinyint(1) unsigned NOT NULL DEFAULT 0');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrugs'))->queryRow();

		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_drugs_drug');
		$this->dropTable('et_ophtroperationnote_drugs');
	
		$this->dropColumn('et_ophtroperationnote_cataract','eyedraw2');
	
		$this->update('element_type',array('display_order'=>3),"event_type_id = 4 and class_name = 'ElementMembranePeel'");
		$this->update('element_type',array('display_order'=>4),"event_type_id = 4 and class_name = 'ElementTamponade'");
		$this->update('element_type',array('display_order'=>5),"event_type_id = 4 and class_name = 'ElementBuckle'");
		$this->update('element_type',array('display_order'=>6),"event_type_id = 4 and class_name = 'ElementCataract'");
		$this->update('element_type',array('display_order'=>7),"event_type_id = 4 and class_name = 'ElementAnaesthetic'");
		$this->update('element_type',array('display_order'=>8),"event_type_id = 4 and class_name = 'ElementSurgeon'");
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementSurgeon'))->queryRow();
		$this->delete('element_type','id='.$element_type['id']);

		$this->delete('et_ophtroperationnote_procedurelist');

		$this->addColumn('et_ophtroperationnote_procedurelist','assistant_id','int(10) unsigned DEFAULT NULL');
		$this->addColumn('et_ophtroperationnote_procedurelist','supervising_surgeon_id','int(10) unsigned DEFAULT NULL');
		$this->addColumn('et_ophtroperationnote_procedurelist','surgeon_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophtroperationnote_procedurelist_surgeon_id_fk','et_ophtroperationnote_procedurelist','surgeon_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_surgeon_id_fk','et_ophtroperationnote_procedurelist','surgeon_id','consultant','id');

		$this->dropTable('et_ophtroperationnote_surgeon');
	
		$this->dropTable('et_ophtroperationnote_anaesthetic');

		$this->addColumn('et_ophtroperationnote_procedurelist','anaesthetic_comment','varchar(1024) COLLATE utf8_bin DEFAULT NULL');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementAnaesthetic'))->queryRow();
		$pl_element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementProcedureList'))->queryRow();

		$this->delete('element_type_anaesthetic_type','element_type_id='.$element_type['id']);
		$this->delete('element_type_anaesthetist','element_type_id='.$element_type['id']);
		$this->delete('element_type_anaesthetic_delivery','element_type_id='.$element_type['id']);
		$this->delete('element_type_anaesthetic_agent','element_type_id='.$element_type['id']);
		$this->delete('element_type_anaesthetic_complication','element_type_id='.$element_type['id']);

		$this->addColumn('et_ophtroperationnote_procedurelist','anaesthetist_id','integer(10) unsigned NOT NULL DEFAULT 1');
		$this->createIndex('et_ophtroperationnote_procedurelist_anaesthetist_fk','et_ophtroperationnote_procedurelist','anaesthetist_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_anaesthetist_fk','et_ophtroperationnote_procedurelist','anaesthetist_id','anaesthetist','id');

		$this->insert('element_type_anaesthetist',array('element_type_id'=>$pl_element_type['id'],'anaesthetist_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$pl_element_type['id'],'anaesthetist_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$pl_element_type['id'],'anaesthetist_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$pl_element_type['id'],'anaesthetist_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetist',array('element_type_id'=>$pl_element_type['id'],'anaesthetist_id'=>5,'display_order'=>5));

		$this->addColumn('et_ophtroperationnote_procedurelist','anaesthetic_delivery_id','integer(10) unsigned NOT NULL DEFAULT 1');
		$this->createIndex('et_ophtroperationnote_procedurelist_anaesthetic_delivery_fk','et_ophtroperationnote_procedurelist','anaesthetic_delivery_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_anaesthetic_delivery_fk','et_ophtroperationnote_procedurelist','anaesthetic_delivery_id','anaesthetic_delivery','id');

		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_delivery_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_delivery_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_delivery_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_delivery_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_delivery_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_delivery_id'=>6,'display_order'=>6));
		$this->insert('element_type_anaesthetic_delivery',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_delivery_id'=>7,'display_order'=>7));

		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_agent_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_agent_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_agent_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_agent_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_agent_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_agent',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_agent_id'=>6,'display_order'=>6));

		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>1,'display_order'=>1));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>2,'display_order'=>2));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>3,'display_order'=>3));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>4,'display_order'=>4));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>5,'display_order'=>5));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>6,'display_order'=>6));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>7,'display_order'=>7));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>8,'display_order'=>8));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>9,'display_order'=>9));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>10,'display_order'=>10));
		$this->insert('element_type_anaesthetic_complication',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_complication_id'=>11,'display_order'=>11));

		$to = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'Topical'))->queryRow();
		$lac = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LAC'))->queryRow();
		$la = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LA'))->queryRow();
		$las = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'LAS'))->queryRow();
		$ga = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('name=:name',array(':name'=>'GA'))->queryRow();

		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_type_id'=>$to['id'],'display_order'=>1));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_type_id'=>$la['id'],'display_order'=>2));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_type_id'=>$lac['id'],'display_order'=>3));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_type_id'=>$las['id'],'display_order'=>4));
		$this->insert('element_type_anaesthetic_type',array('element_type_id'=>$pl_element_type['id'],'anaesthetic_type_id'=>$ga['id'],'display_order'=>5));

		$this->renameTable('et_ophtroperationnote_anaesthetic_anaesthetic_agent','et_ophtroperationnote_procedurelist_anaesthetic_agent');
		$this->renameTable('et_ophtroperationnote_anaesthetic_anaesthetic_complication','et_ophtroperationnote_procedurelist_anaesthetic_complication');

		$this->addColumn('et_ophtroperationnote_procedurelist','anaesthetic_type_id',"int(10) unsigned NOT NULL DEFAULT '1'");
		$this->createIndex('et_ophtroperationnote_procedurelist_anaesthetic_type_id_fk','et_ophtroperationnote_procedurelist','anaesthetic_type_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_anaesthetic_type_id_fk','et_ophtroperationnote_procedurelist','anaesthetic_type_id','anaesthetic_type','id');

		$this->delete('element_type', 'id='.$element_type['id']);
	
		$this->alterColumn('et_ophtroperationnote_cataract','vision_blue',"tinyint(1) unsigned NOT NULL DEFAULT '0'");
		$this->alterColumn('et_ophtroperationnote_cataract','report',"varchar(4096) COLLATE utf8_bin NOT NULL");
	
		$this->dropColumn('et_ophtroperationnote_cataract','complication_notes');
	
		$this->dropColumn('et_ophtroperationnote_procedurelist','anaesthetic_comment');

		$this->dropTable('et_ophtroperationnote_procedurelist_anaesthetic_complication');
		$this->dropTable('et_ophtroperationnote_procedurelist_anaesthetic_agent');

		$this->dropForeignKey('et_ophtroperationnote_procedurelist_anaesthetic_delivery_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_anaesthetic_delivery_fk','et_ophtroperationnote_procedurelist');
		$this->dropColumn('et_ophtroperationnote_procedurelist','anaesthetic_delivery_id');

		$this->dropForeignKey('et_ophtroperationnote_procedurelist_anaesthetist_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_anaesthetist_fk','et_ophtroperationnote_procedurelist');
		$this->dropColumn('et_ophtroperationnote_procedurelist','anaesthetist_id');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementProcedureList'))->queryRow();

		$this->delete('element_type_anaesthetist','element_type_id='.$element_type['id']);
		$this->delete('element_type_anaesthetic_delivery','element_type_id='.$element_type['id']);
		$this->delete('element_type_anaesthetic_agent','element_type_id='.$element_type['id']);
		$this->delete('element_type_anaesthetic_complication','element_type_id='.$element_type['id']);
	
		$this->dropColumn('et_ophtroperationnote_procedurelist','supervising_surgeon_id');
	
		$this->dropForeignKey('et_ophtroperationnote_cataract_iol_position_fk','et_ophtroperationnote_cataract');
		$this->dropIndex('et_ophtroperationnote_cataract_iol_position_fk','et_ophtroperationnote_cataract');
		$this->dropColumn('et_ophtroperationnote_cataract','iol_position_id');
		$this->dropTable('et_ophtroperationnote_cataract_iol_position');
		$this->dropColumn('et_ophtroperationnote_cataract','vision_blue');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementProcedureList'))->queryRow();

		$this->delete('element_type_eye','element_type_id='.$element_type['id']);

		$this->dropForeignKey('et_ophtroperationnote_procedurelist_eye_id_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_eye_id_fk','et_ophtroperationnote_procedurelist');
		$this->dropColumn('et_ophtroperationnote_procedurelist','eye_id');
	
		$this->alterColumn('et_ophtroperationnote_vitrectomy','gauge_id','int(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophtroperationnote_tamponade','gas_type_id','int(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophtroperationnote_buckle','drainage_type_id','int(10) unsigned NOT NULL DEFAULT 1');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementCataract'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);

		$this->delete('element_type','event_type_id = '.$event_type['id']." and class_name = 'ElementCataract'");

		$this->dropTable('et_ophtroperationnote_cataract');
		$this->dropTable('et_ophtroperationnote_cataract_incision_type');
		$this->dropTable('et_ophtroperationnote_cataract_incision_site');
	
		$this->dropColumn('et_ophtroperationnote_buckle','report');
	
		$this->alterColumn('et_ophtroperationnote_buckle','eyedraw','varchar(1024) COLLATE utf8_bin NOT NULL');
	
		$this->createIndex('et_ophtroperationnote_procedurelist_assistant_id_fk','et_ophtroperationnote_procedurelist','assistant_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_assistant_id_fk','et_ophtroperationnote_procedurelist','assistant_id','user','id');
	
		$this->dropForeignKey('et_ophtroperationnote_tamponade_gv_id','et_ophtroperationnote_tamponade');
		$this->dropIndex('et_ophtroperationnote_tamponade_gv_id','et_ophtroperationnote_tamponade');
		$this->dropForeignKey('et_ophtroperationnote_tamponade_pc_id','et_ophtroperationnote_tamponade');
		$this->dropIndex('et_ophtroperationnote_tamponade_pc_id','et_ophtroperationnote_tamponade');

		$this->alterColumn('et_ophtroperationnote_tamponade','gas_volume_id','int(10) unsigned NOT NULL DEFAULT 0');
		$this->renameColumn('et_ophtroperationnote_tamponade','gas_volume_id','volume');
		$this->renameColumn('et_ophtroperationnote_tamponade','gas_percentage_id','percentage');

		$this->dropTable('et_ophtroperationnote_gas_volume');
		$this->dropTable('et_ophtroperationnote_gas_percentage');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementBuckle'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);

		$this->delete('element_type','event_type_id = '.$event_type['id']." and class_name = 'ElementBuckle'");

		$this->dropTable('et_ophtroperationnote_buckle');
		$this->dropTable('et_ophtroperationnote_buckle_drainage_type');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTamponade'))->queryRow();
		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_term=:snomed',array(':snomed'=>'Injection of silicone oil into vitreous'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id'].' and procedure_id='.$proc['id']);
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTamponade'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);

		$this->delete('element_type','event_type_id = '.$event_type['id']." and class_name = 'ElementTamponade'");

		$this->dropTable('et_ophtroperationnote_tamponade');
		$this->dropTable('et_ophtroperationnote_gas_type');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementMembranePeel'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->dropTable('et_ophtroperationnote_membrane_peel');

		$this->delete('element_type','id = '.$element_type['id']);
	
		$this->dropTable('et_ophtroperationnote_procedure_element');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->delete('element_type','event_type_id = '.$event_type['id']." and class_name = 'ElementVitrectomy'");

		$this->dropTable('et_ophtroperationnote_vitrectomy');
		$this->dropTable('et_ophtroperationnote_gauge');
	
		$this->dropTable('et_ophtroperationnote_procedurelist_procedure_assignment');
	
		$this->dropForeignKey('et_ophtroperationnote_procedurelist_anaesthetic_type_id_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_anaesthetic_type_id_fk','et_ophtroperationnote_procedurelist');
		$this->alterColumn('et_ophtroperationnote_procedurelist','anaesthetic_type_id',"tinyint(1) unsigned DEFAULT '0'");
		$this->renameColumn('et_ophtroperationnote_procedurelist','anaesthetic_type_id','anaesthetic_type');

		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>0),'anaesthetic_type=1');
		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>1),'anaesthetic_type=2');
		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>2),'anaesthetic_type=3');
		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>3),'anaesthetic_type=4');
		$this->update('et_ophtroperationnote_procedurelist',array('anaesthetic_type'=>4),'anaesthetic_type=5');
	
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:event_type_id',array(':name'=>'Procedure list',':event_type_id'=>$event_type['id']))->queryRow();

		$this->delete('element_type_anaesthetic_type','element_type_id='.$element_type['id']);
	
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name', array(':name'=>'Procedure list'))->queryRow();

		$this->delete('element_type','id='.$element_type['id']);

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		// Get all the events for opnote and delete them, making a note of any related episodes
		$episodes = array();
		foreach ($this->dbConnection->createCommand()->select('episode_id')->from('event')->where('event_type_id=:event_type_id', array(':event_type_id'=>$event_type['id']))->queryAll() as $event) {
			if (!in_array($event['episode_id'], $episodes)) {
				$episodes[] = $event['episode_id'];
			}
		}

		$this->delete('event', 'event_type_id='.$event_type['id']);
		$this->delete('event_type','id='.$event_type['id']);

		// If any of the related episodes now have no events, remove them
		if (!empty($episodes)) {
			foreach ($this->dbConnection->createCommand()->select('id')->from('episode')->where('id in ('.implode(',',$episodes).')')->queryAll() as $episode) {
				$n = 0;
				foreach ($this->dbConnection->createCommand()->select('id')->from('event')->where('episode_id=:episode_id',array(':episode_id'=>$episode['id']))->queryAll() as $event) {
					$n++;
				}

				if ($n == 0) {
					$this->delete('episode','id='.$episode['id']);
				}
			}
		}

		$this->dropTable('et_ophtroperationnote_procedurelist');
	}
}
