<?php

class m120530_130251_fields_for_fife extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_anaesthetic','anaesthetic_witness_id','integer(10) unsigned');
		$this->createIndex('et_ophtroperationnote_ana_anaesthetic_witness_id_fk','et_ophtroperationnote_anaesthetic','anaesthetic_witness_id');
		$this->addForeignKey('et_ophtroperationnote_ana_anaesthetic_witness_id_fk','et_ophtroperationnote_anaesthetic','anaesthetic_witness_id','user','id');

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
				'CONSTRAINT `et_ophtroperationnote_csp_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_csp_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
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
	}

	public function down()
	{
		$this->dropForeignKey('et_ophtroperationnote_ca_intraocular_solution_id_fk','et_ophtroperationnote_cataract');
		$this->dropIndex('et_ophtroperationnote_ca_intraocular_solution_id_fk','et_ophtroperationnote_cataract');
		$this->dropColumn('et_ophtroperationnote_cataract','intraocular_solution_id');

		$this->dropTable('et_ophtroperationnote_cataract_intraocular_solution');

		$this->dropForeignKey('et_ophtroperationnote_ca_skin_preparation_id_fk','et_ophtroperationnote_cataract');
		$this->dropIndex('et_ophtroperationnote_ca_skin_preparation_id_fk','et_ophtroperationnote_cataract');
		$this->dropColumn('et_ophtroperationnote_cataract','skin_preparation_id');

		$this->dropTable('et_ophtroperationnote_cataract_skin_preparation');

		$this->dropForeignKey('et_ophtroperationnote_ana_anaesthetic_witness_id_fk','et_ophtroperationnote_anaesthetic');
		$this->dropIndex('et_ophtroperationnote_ana_anaesthetic_witness_id_fk','et_ophtroperationnote_anaesthetic');
		$this->dropColumn('et_ophtroperationnote_anaesthetic','anaesthetic_witness_id');
	}
}
