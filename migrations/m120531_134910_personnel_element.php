<?php

class m120531_134910_personnel_element extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$this->insert('element_type', array('name' => 'Personnel', 'class_name' => 'ElementPersonnel', 'event_type_id' => $event_type['id'], 'display_order' => 45, 'default' => 1));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPersonnel'))->queryRow();

		$this->createTable('et_ophtroperationnote_personnel', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'scrub_nurse_id' => 'int(10) unsigned NOT NULL',
				'floor_nurse_id' => 'int(10) unsigned NOT NULL',
				'accompanying_nurse_id' => 'int(10) unsigned NOT NULL',
				'operating_department_practitioner_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_p_event_id_fk` (`event_id`)',
				'KEY `et_ophtroperationnote_p_scrub_nurse_id_fk` (`scrub_nurse_id`)',
				'KEY `et_ophtroperationnote_p_floor_nurse_id_fk` (`floor_nurse_id`)',
				'KEY `et_ophtroperationnote_p_accompanying_nurse_id_fk` (`accompanying_nurse_id`)',
				'KEY `et_ophtroperationnote_p_operating_department_practitioner_id_fk` (`operating_department_practitioner_id`)',
				'KEY `et_ophtroperationnote_p_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_p_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_p_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_p_scrub_nurse_id_fk` FOREIGN KEY (`scrub_nurse_id`) REFERENCES `contact` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_p_floor_nurse_id_fk` FOREIGN KEY (`floor_nurse_id`) REFERENCES `contact` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_p_accompanying_nurse_id_fk` FOREIGN KEY (`accompanying_nurse_id`) REFERENCES `contact` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_p_operating_department_practitioner_id_fk` FOREIGN KEY (`operating_department_practitioner_id`) REFERENCES `contact` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_p_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_p_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_personnel');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementPersonnel'))->queryRow();

		$this->delete('element_type','id='.$element_type['id']);
	}
}
