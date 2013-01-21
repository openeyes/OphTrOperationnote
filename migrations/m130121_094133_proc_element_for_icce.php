<?php

class m130121_094133_proc_element_for_icce extends CDbMigration
{
	public function up()
	{
		$this->createTable('et_ophtroperationnote_icce', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_icce_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_icce_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_icce_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_icce_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));

		$this->insert('element_type',array('name'=>'ICCE','class_name'=>'ElementICCE','event_type_id'=>$event_type->id,'display_order'=>20,'default'=>0));

		$element_type = ElementType::model()->find('event_type_id=? and name=?',array($event_type->id,'ICCE'));

		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Intracapsular cataract extraction','260216002'));

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc->id,'element_type_id'=>$element_type->id));
	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_icce');

		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));

		$element_type = ElementType::model()->find('event_type_id=? and name=?',array($event_type->id,'ICCE'));

		$proc = Procedure::model()->find('term=? and snomed_code=?',array('Intracapsular cataract extraction','260216002'));

		$this->delete('et_ophtroperationnote_procedure_element',"procedure_id = $proc->id and element_type_id = $element_type->id");

		$this->delete('element_type',"id=$element_type->id");
	}
}
