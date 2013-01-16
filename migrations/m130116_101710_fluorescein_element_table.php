<?php

class m130116_101710_fluorescein_element_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('et_ophtroperationnote_fluorescein', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_flrscn_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_flrscn_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_flrscn_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_flrscn_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_flrscn_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_flrscn_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_fluorescein');
	}
}
