<?php

class m120629_130712_fife_preparation_element extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$this->insert('element_type', array('name' => 'Preparation', 'class_name' => 'ElementPreparation', 'event_type_id' => $event_type['id'], 'display_order' => 15, 'default' => 1));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPreparation'))->queryRow();

		$this->createTable('et_ophtroperationnote_preparation_skin_preparation',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned DEFAULT \'0\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_psp_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_psp_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_psp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_psp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_preparation_skin_preparation',array('id'=>1,'name'=>'Tisept'));
		$this->insert('et_ophtroperationnote_preparation_skin_preparation',array('id'=>2,'name'=>'Betadine'));
		$this->insert('et_ophtroperationnote_preparation_skin_preparation',array('id'=>3,'name'=>'Other (please specify)'));

		$this->createTable('et_ophtroperationnote_preparation_intraocular_solution',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned DEFAULT \'0\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_pis_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_pis_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_pis_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_pis_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_preparation_intraocular_solution',array('id'=>1,'name'=>'BSS'));
		$this->insert('et_ophtroperationnote_preparation_intraocular_solution',array('id'=>2,'name'=>'BSS with adrenaline'));
		$this->insert('et_ophtroperationnote_preparation_intraocular_solution',array('id'=>3,'name'=>'Hartmans'));

		$this->createTable('et_ophtroperationnote_preparation', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'spo2' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'oxygen' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'pulse' => 'smallint(2) unsigned NOT NULL DEFAULT 0',
				'skin_preparation_id' => 'int(10) unsigned NOT NULL',
				'intraocular_solution_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_preparation_event_id_fk` (`event_id`)',
				'KEY `et_ophtroperationnote_preparation_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_preparation_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_preparation_skin_preparation_id_fk` (`skin_preparation_id`)',
				'KEY `et_ophtroperationnote_preparation_intraocular_solution_id_fk` (`intraocular_solution_id`)',
				'CONSTRAINT `et_ophtroperationnote_preparation_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_preparation_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_preparation_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_preparation_skin_preparation_id_fk` FOREIGN KEY (`skin_preparation_id`) REFERENCES `et_ophtroperationnote_preparation_skin_preparation` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_preparation_intraocular_solution_id_fk` FOREIGN KEY (`intraocular_solution_id`) REFERENCES `et_ophtroperationnote_preparation_intraocular_solution` (`id`)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->dropForeignKey('et_ophtroperationnote_ca_intraocular_solution_id_fk','et_ophtroperationnote_cataract');
		$this->dropIndex('et_ophtroperationnote_ca_intraocular_solution_id_fk','et_ophtroperationnote_cataract');
		$this->dropColumn('et_ophtroperationnote_cataract','intraocular_solution_id');

		$this->dropTable('et_ophtroperationnote_cataract_intraocular_solution');

		$this->dropForeignKey('et_ophtroperationnote_ca_skin_preparation_id_fk','et_ophtroperationnote_cataract');
		$this->dropIndex('et_ophtroperationnote_ca_skin_preparation_id_fk','et_ophtroperationnote_cataract');
		$this->dropColumn('et_ophtroperationnote_cataract','skin_preparation_id');

		$this->dropTable('et_ophtroperationnote_cataract_skin_preparation');
	}

	public function down()
	{
		$this->createTable('et_ophtroperationnote_cataract_skin_preparation',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned DEFAULT \'0\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_csp_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_csp_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_csp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',				 'CONSTRAINT `et_ophtroperationnote_csp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_cataract_skin_preparation',array('id'=>1,'name'=>'Tisept'));
		$this->insert('et_ophtroperationnote_cataract_skin_preparation',array('id'=>2,'name'=>'Betadine'));
		$this->insert('et_ophtroperationnote_cataract_skin_preparation',array('id'=>3,'name'=>'Other (please specify)'));

		$this->addColumn('et_ophtroperationnote_cataract','skin_preparation_id','integer(10) unsigned');
		$this->createIndex('et_ophtroperationnote_ca_skin_preparation_id_fk','et_ophtroperationnote_cataract','skin_preparation_id');
		$this->addForeignKey('et_ophtroperationnote_ca_skin_preparation_id_fk','et_ophtroperationnote_cataract','skin_preparation_id','et_ophtroperationnote_cataract_skin_preparation','id');

		$this->createTable('et_ophtroperationnote_cataract_intraocular_solution',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned DEFAULT \'0\'',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_cis2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_cis2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_cis2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_cis2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophtroperationnote_cataract_intraocular_solution',array('id'=>1,'name'=>'BSS'));
		$this->insert('et_ophtroperationnote_cataract_intraocular_solution',array('id'=>2,'name'=>'BSS with adrenaline'));
		$this->insert('et_ophtroperationnote_cataract_intraocular_solution',array('id'=>3,'name'=>'Hartmans'));

		$this->addColumn('et_ophtroperationnote_cataract','intraocular_solution_id','integer(10) unsigned');
		$this->createIndex('et_ophtroperationnote_ca_intraocular_solution_id_fk','et_ophtroperationnote_cataract','intraocular_solution_id');
		$this->addForeignKey('et_ophtroperationnote_ca_intraocular_solution_id_fk','et_ophtroperationnote_cataract','intraocular_solution_id','et_ophtroperationnote_cataract_intraocular_solution','id');

		$this->dropTable('et_ophtroperationnote_preparation');
		$this->dropTable('et_ophtroperationnote_preparation_intraocular_solution');
		$this->dropTable('et_ophtroperationnote_preparation_skin_preparation');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPreparation'))->queryRow();

		$this->delete('element_type','id='.$element_type['id']);
	}
}
