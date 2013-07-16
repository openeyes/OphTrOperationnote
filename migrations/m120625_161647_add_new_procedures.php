<?php

class m120625_161647_add_new_procedures extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Ozurdex', 'class_name' => 'ElementOzurdex', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOzurdex'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'419222003'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_ozurdex', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oozurdex_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oozurdex_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oozurdex_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oozurdex_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'RemAqueousShunt', 'class_name' => 'ElementRemAqueousShunt', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemAqueousShunt'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'440587008'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_rem_aqueous_shunt', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oras_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oras_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oras_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oras_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Orbit - other', 'class_name' => 'ElementOrbitOther', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOrbitOther'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'172189002'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_orbit_other', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oorbitother_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_ooorbitother_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_ooorbitother_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_ooorbitother_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lid - other', 'class_name' => 'ElementLidOther', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLidOther'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'118912008'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lid_other', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olidother_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olidother_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olidother_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olidother_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'RedoDCR', 'class_name' => 'ElementRedoDCR', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRedoDCR'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'265281004'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_redo_dcr', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_oredodcr_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_oredodcr_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_oredodcr_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_oredodcr_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Repair Orbit', 'class_name' => 'ElementRepairOrbit', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOrbit'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'239385005'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_repair_orbit', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_orepairorbit_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_orepairorbit_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_orepairorbit_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_orepairorbit_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Drain Supra', 'class_name' => 'ElementDrainSupra', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrainSupra'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'231788008'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_drain_supra', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_odrainsupra_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_odrainsupra_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_odrainsupra_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_odrainsupra_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Tarsoconj diamond', 'class_name' => 'ElementTarsoconjDiamond', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsoconjDiamond'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'231603008'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_tarsoconj_diamond', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_otarsoconjd_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_otarsoconjd_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_otarsoconjd_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_otarsoconjd_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lat canth sling', 'class_name' => 'ElementLatCanthSling', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLatCanthSling'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'231590007'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lat_canth_sling', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olatcanthsling_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olatcanthsling_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olatcanthsling_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olatcanthsling_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Three snip', 'class_name' => 'ElementThreeSnip', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementThreeSnip'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'172311004'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_three_snip', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_othreesnip_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_othreesnip_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_othreesnip_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_othreesnip_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
	}

	public function down()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOzurdex'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_ozurdex');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRemAqueousShunt'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_rem_aqueous_shunt');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementOrbitOther'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_orbit_other');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLidOther'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lid_other');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRedoDCR'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_redo_dcr');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementRepairOrbit'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_repair_orbit');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementDrainSupra'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_drain_supra');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementTarsoconjDiamond'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_tarsoconj_diamond');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLatCanthSling'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lat_canth_sling');

		//----

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementThreeSnip'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_three_snip');
	}
}
