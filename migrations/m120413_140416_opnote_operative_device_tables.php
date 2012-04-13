<?php

class m120413_140416_opnote_operative_device_tables extends CDbMigration
{
	public function up()
	{
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
	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_cataract_operative_device');
	}
}
