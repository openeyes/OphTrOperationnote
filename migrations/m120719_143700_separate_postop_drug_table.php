<?php

class m120719_143700_separate_postop_drug_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('et_ophtroperationnote_postop_drug',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(255) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_postop_drug_l_m_u_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_postop_drug_c_u_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_postop_drug_c_u_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_postop_drug_l_m_u_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
		),
				'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
		$this->createTable('et_ophtroperationnote_postop_site_subspecialty_drug',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'site_id' => 'int(10) unsigned NOT NULL',
				'subspecialty_id' => 'int(10) unsigned NOT NULL',
				'drug_id' => 'int(10) unsigned NOT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL',
				'default' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_postop_site_subspecialty_drug_l_m_u_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_postop_site_subspecialty_drug_c_u_id_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_postop_site_subspecialty_drug_site_id` (`site_id`)',
				'KEY `et_ophtroperationnote_postop_site_subspecialty_drug_s_id` (`subspecialty_id`)',
				'KEY `et_ophtroperationnote_postop_site_subspecialty_drug_drug_id` (`drug_id`)',
				'CONSTRAINT `et_ophtroperationnote_postop_site_subspecialty_drug_c_u_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_postop_site_subspecialty_drug_l_m_u_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_postop_site_subspecialty_drug_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_postop_site_subspecialty_drug_s_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_postop_site_subspecialty_drug_drug_id_fk` FOREIGN KEY (`drug_id`) REFERENCES `et_ophtroperationnote_postop_drug` (`id`)',
		),
				'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		// Truncate postopdrugs table
		// FIXME: THIS WILL RESULT IN LOST DATA
		$drugs = $this->dbConnection->createCommand()->truncateTable('et_ophtroperationnote_postop_drugs_drug');

		// Drop old foreign key
		$this->dropForeignKey('et_ophtroperationnote_pdd_drug_id_fk', 'et_ophtroperationnote_postop_drugs_drug');

		// Update drug relationships to new table
		/*
		$drugs = $this->dbConnection->createCommand()
		->selectDistinct('drug.id, drug.name')
		->from('drug')
		->join('et_ophtroperationnote_postop_drugs_drug','et_ophtroperationnote_postop_drugs_drug.drug_id = drug.id')
		->query();
		if ($drugs) {

			// Use datestamp to avoid colliding IDs
			$datestamp = date('Y-m-d H:i:s');

			foreach ($drugs as $drug) {
				$new_drug_id = $this->dbConnection->createCommand()
				->select('id')
				->from('postop_drug')
				->where('name = :drug_name')
				->queryScalar(array(':drug_name' => $drug['name']));
				$this->update('et_ophtroperationnote_postop_drugs_drug',
						array('drug_id' => $new_drug_id, 'last_modified_date' => $datestamp),
						'drug_id = :drug_id AND last_modified_date != :datestamp',
						array(':drug_id' => $drug['id'], ':datestamp' => $datestamp)
				);
			}
		}
		*/

		// Add new foreign key
		$this->addForeignKey('et_ophtroperationnote_pdd_drug_id_fk', 'et_ophtroperationnote_postop_drugs_drug', 'drug_id', 'et_ophtroperationnote_postop_drug', 'id');

	}

	public function down()
	{
		$this->dropForeignKey('et_ophtroperationnote_pdd_drug_id_fk', 'et_ophtroperationnote_postop_drugs_drug');
		$drugs = $this->dbConnection->createCommand()
		->select('id, name')
		->from('postop_drug')
		->query();
		if ($drugs) {
			$datestamp = date('Y-m-d H:i:s');
			foreach ($drugs as $drug) {
				$new_drug_id = $this->dbConnection->createCommand()
				->select('id')
				->from('drug')
				->where('name = :drug_name')
				->queryScalar(array(':drug_name' => $drug['name']));
				$this->update('et_ophtroperationnote_postop_drugs_drug',
						array('drug_id' => $new_drug_id, 'last_modified_date' => $datestamp),
						'drug_id = :drug_id AND last_modified_date != :datestamp',
						array(':drug_id' => $drug['id'], ':datestamp' => $datestamp)
				);
			}
		}
		$this->addForeignKey('et_ophtroperationnote_pdd_drug_id_fk', 'et_ophtroperationnote_postop_drugs_drug', 'drug_id', 'drug', 'id');
	}

}
