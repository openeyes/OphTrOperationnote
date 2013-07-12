<?php

class m120628_130847_fix_procedure_element_associations extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		// Fix dupe mappings for Revision of aqueous shunt
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementRemAqueousShunt'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id=132 and element_type_id='.$element_type['id']);

		// Fix dupe mapping for Reformation of skin crease
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementLidOther'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id=227 and element_type_id='.$element_type['id']);

		// Fix dupe mapping for Dacrocystorhinostomy & retrotubes
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementRedoDCR'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id=269 and element_type_id='.$element_type['id']);

		// Add missing mapping for Redo external DCR

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>335,'element_type_id'=>$element_type['id']));

		// Add missing mapping for Other procedure on eyelid

		$this->createTable('et_ophtroperationnote_other_eyelid', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'comments' => 'varchar(4096) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtroperationnote_other_eyelid_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophtroperationnote_other_eyelid_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophtroperationnote_other_eyelid_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtroperationnote_other_eyelid_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('element_type', array('name' => 'Other procedure on eyelid', 'class_name' => 'ElementOtherProcedureEyelid', 'event_type_id' => $event_type['id'], 'display_order' => 2, 'default' => 0));

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementOtherProcedureEyelid'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>336,'element_type_id'=>$element_type['id']));

		// Add missing mapping for Removal of aqueous shunt

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementRemAqueousShunt'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>338,'element_type_id'=>$element_type['id']));
	}

	public function down()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		// Restore dupe mapping for Revision of aqueous shunt
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementRemAqueousShunt'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>132,'element_type_id'=>$element_type['id']));

		// Restore dupe mapping for Reformation of skin crease

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementLidOther'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>227,'element_type_id'=>$element_type['id']));

		// Restore dupe mapping for Dacrocystorhinostomy & retrotubes

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementRedoDCR'))->queryRow();

		$this->insert('et_ophtroperationnote_procedure_element',array('procedure_id'=>269,'element_type_id'=>$element_type['id']));

		// Remove mapping for Redo external DCR

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id=335 and element_type_id='.$element_type['id']);

		// Remove mapping for other procedure on eyelid

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementOtherProcedureEyelid'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id=336 and element_type_id='.$element_type['id']);

		$this->delete('element_type','id='.$element_type['id']);
		$this->dropTable('et_ophtroperationnote_other_eyelid');

		// Remove mapping for Removal of aqueous shunt

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:className',array(':eventTypeId'=>$event_type['id'],':className'=>'ElementRemAqueousShunt'))->queryRow();

		$this->delete('et_ophtroperationnote_procedure_element','procedure_id=338 and element_type_id='.$element_type['id']);
	}
}
