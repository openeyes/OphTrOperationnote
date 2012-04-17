<?php

class m120417_111135_opnote_site_subspecialty_postop_instructions extends CDbMigration
{
	public function up()
	{
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

		$this->insert('et_ophtroperationnote_site_subspecialty_postop_instructions',array('id'=>1,'site_id'=>1,'subspecialty_id'=>4,'content'=>'Use drops three times a day','display_order'=>1));
		$this->insert('et_ophtroperationnote_site_subspecialty_postop_instructions',array('id'=>2,'site_id'=>1,'subspecialty_id'=>4,'content'=>'Use drops four times a day','display_order'=>2));
	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_site_subspecialty_postop_instructions');
	}
}
