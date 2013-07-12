<?php

class m120704_100346_change_new_procedures_to_longname extends CDbMigration
{
	public function up()
	{
		$this->update('element_type', array('name' => 'Dexamethasone 700microgram intravitreal implant'), 'class_name=:class_name', array(':class_name' => 'ElementOzurdex'));
		//
		$this->update('element_type', array('name' => 'Removal of aqueous shunt'), 'class_name=:class_name', array(':class_name' => 'ElementRemAqueousShunt'));
		//
		$this->update('element_type', array('name' => 'Other procedure on orbit'), 'class_name=:class_name', array(':class_name' => 'ElementOrbitOther'));
		//
		// Drop ElementLidOther as its a duplicate
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLidOther'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','element_type_id='.$element_type['id']);
		$this->delete('element_type','id='.$element_type['id']);

		$this->dropTable('et_ophtroperationnote_lid_other');
		//
		$this->update('element_type', array('name' => 'Redo external DCR'), 'class_name=:class_name', array(':class_name' => 'ElementRedoDCR'));
		//
		$this->update('element_type', array('name' => 'Repair orbital floor'), 'class_name=:class_name', array(':class_name' => 'ElementRepairOrbit'));
		//
		$this->update('element_type', array('name' => 'Drainage of suprachoroidal fluid'), 'class_name=:class_name', array(':class_name' => 'ElementDrainSupra'));
		//
		$this->update('element_type', array('name' => 'Tarsoconjunctival diamond excision'), 'class_name=:class_name', array(':class_name' => 'ElementTarsoconjDiamond'));
		//
		$this->update('element_type', array('name' => 'Lateral canthal sling'), 'class_name=:class_name', array(':class_name' => 'ElementLatCanthSling'));
		//
		$this->update('element_type', array('name' => 'Three snip procedure'), 'class_name=:class_name', array(':class_name' => 'ElementThreeSnip'));

	}

	public function down()
	{
		$this->update('element_type', array('name' => 'Ozurdex'), 'class_name=:class_name', array(':class_name' => 'ElementOzurdex'));
		//
		$this->update('element_type', array('name' => 'RemAqueousShunt'), 'class_name=:class_name', array(':class_name' => 'ElementRemAqueousShunt'));
		//
		$this->update('element_type', array('name' => 'Orbit - other'), 'class_name=:class_name', array(':class_name' => 'ElementOrbitOther'));
		//
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$this->insert('element_type', array('name' => 'Lid - other', 'class_name' => 'ElementLidOther', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id = :event_type_id and class_name=:class_name',array(':event_type_id' => $event_type['id'], ':class_name'=>'ElementLidOther'))->queryRow();

		$proc = $this->dbConnection->createCommand()->select('id')->from('proc')->where('snomed_code = :sc',array(':sc'=>'118912008'))->queryRow();
		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
			$this->createTable('et_ophtroperationnote_lid_other', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_olidother_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_olidother_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_olidother_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_olidother_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
		//
		$this->update('element_type', array('name' => 'RedoDCR'), 'class_name=:class_name', array(':class_name' => 'ElementRedoDCR'));
		//
		$this->update('element_type', array('name' => 'Repair Orbit'), 'class_name=:class_name', array(':class_name' => 'ElementRepairOrbit'));
		//
		$this->update('element_type', array('name' => 'Drain Supra'), 'class_name=:class_name', array(':class_name' => 'ElementDrainSupra'));
		//
		$this->update('element_type', array('name' => 'Tarsoconj diamond'), 'class_name=:class_name', array(':class_name' => 'ElementTarsoconjDiamond'));
		//
		$this->update('element_type', array('name' => 'Lat canth sling'), 'class_name=:class_name', array(':class_name' => 'ElementLatCanthSling'));
		//
		$this->update('element_type', array('name' => 'Three snip'), 'class_name=:class_name', array(':class_name' => 'ElementThreeSnip'));
	}

}
