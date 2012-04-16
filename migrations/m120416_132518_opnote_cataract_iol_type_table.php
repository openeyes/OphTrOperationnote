<?php

class m120416_132518_opnote_cataract_iol_type_table extends CDbMigration
{
	public function up()
	{
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
	}

	public function down()
	{
		$this->dropForeignKey('et_ophtroperationnote_cataract_iol_type_id_fk','et_ophtroperationnote_cataract');
		$this->dropIndex('et_ophtroperationnote_cataract_iol_type_id_fk','et_ophtroperationnote_cataract');
		$this->dropColumn('et_ophtroperationnote_cataract','iol_type_id');

		$this->dropTable('et_ophtroperationnote_cataract_iol_type');
	}
}
