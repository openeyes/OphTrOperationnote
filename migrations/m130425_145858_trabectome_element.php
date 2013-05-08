<?php

class m130425_145858_trabectome_element extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Trabectome', 'class_name' => 'ElementTrabectome', 'event_type_id' => $event_type['id'], 'display_order' => 20, 'default' => 0));

		$this->createTable('et_ophtroperationnote_trabectome', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_trbctm_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_trbctm_cui_fk` (`created_user_id`)',
				'KEY `et_ophtroperationnote_trbctm_ev_fk` (`event_id`)',
				'CONSTRAINT `et_ophtroperationnote_trbctm_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_trbctm_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_trbctm_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$element_type = Yii::app()->db->createCommand()->select("id")->from("element_type")->where('event_type_id=:event_type_id and class_name=:class_name',array(':event_type_id'=>$event_type['id'],':class_name'=>'ElementTrabectome'))->queryRow();
		$proc = Yii::app()->db->createCommand()->select("id")->from("proc")->where("snomed_code=:snomed_code",array(':snomed_code'=>'31337'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
	}

	public function down()
	{
		$this->dropTable('et_ophtroperationnote_trabectome');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = Yii::app()->db->createCommand()->select("id")->from("element_type")->where('event_type_id=:event_type_id and class_name=:class_name',array(':event_type_id'=>$event_type['id'],':class_name'=>'ElementTrabectome'))->queryRow();
		$this->delete('et_ophtroperationnote_procedure_element',"element_type_id={$element_type['id']}");
		$this->delete('element_type',"id={$element_type['id']}");
	}
}
