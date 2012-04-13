<?php

class m120413_121943_change_drugs_element_to_postop_drugs extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:event_type_id',array(':name'=>'Drugs',':event_type_id'=>$event_type['id']))->queryRow();

		$this->update('element_type',array('name'=>'Post-op drugs','class_name'=>'ElementPostOpDrugs'),'id='.$element_type['id']);

		$this->dropForeignKey('et_ophtroperationnote_drugs_type_created_user_id_fk','et_ophtroperationnote_drugs');
		$this->dropForeignKey('et_ophtroperationnote_drugs_type_last_modified_user_id_fk','et_ophtroperationnote_drugs');
		$this->dropIndex('et_ophtroperationnote_drugs_type_created_user_id_fk','et_ophtroperationnote_drugs');
		$this->dropIndex('et_ophtroperationnote_drugs_type_last_modified_user_id_fk','et_ophtroperationnote_drugs');
		$this->createIndex('et_ophtroperationnote_postop_drugs_last_modified_user_id_fk','et_ophtroperationnote_drugs','last_modified_user_id');
		$this->createIndex('et_ophtroperationnote_postop_drugs_created_user_id_fk','et_ophtroperationnote_drugs','created_user_id');
		$this->addForeignKey('et_ophtroperationnote_postop_drugs_last_modified_user_id_fk','et_ophtroperationnote_drugs','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_postop_drugs_created_user_id_fk','et_ophtroperationnote_drugs','created_user_id','user','id');
		$this->renameTable('et_ophtroperationnote_drugs','et_ophtroperationnote_postop_drugs');

		$this->dropForeignKey('et_ophtroperationnote_dd_drugs_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_dd_drug_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_dd_created_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_dd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_dd_drugs_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_dd_drug_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_dd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_dd_created_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->renameColumn('et_ophtroperationnote_drugs_drug','et_ophtroperationnote_drugs_id','et_ophtroperationnote_postop_drugs_id');
		$this->createIndex('et_ophtroperationnote_pdd_created_user_id_fk','et_ophtroperationnote_drugs_drug','created_user_id');
		$this->createIndex('et_ophtroperationnote_pdd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug','last_modified_user_id');
		$this->createIndex('et_ophtroperationnote_pdd_drug_id_fk','et_ophtroperationnote_drugs_drug','drug_id');
		$this->createIndex('et_ophtroperationnote_pdd_drugs_id_fk','et_ophtroperationnote_drugs_drug','et_ophtroperationnote_postop_drugs_id');
		$this->addForeignKey('et_ophtroperationnote_pdd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_pdd_created_user_id_fk','et_ophtroperationnote_drugs_drug','created_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_pdd_drug_id_fk','et_ophtroperationnote_drugs_drug','drug_id','drug','id');
		$this->addForeignKey('et_ophtroperationnote_pdd_drugs_id_fk','et_ophtroperationnote_drugs_drug','et_ophtroperationnote_postop_drugs_id','et_ophtroperationnote_postop_drugs','id');
		$this->renameTable('et_ophtroperationnote_drugs_drug','et_ophtroperationnote_postop_drugs_drug');
	}

	public function down()
	{
		$this->renameTable('et_ophtroperationnote_postop_drugs_drug','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_pdd_drugs_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_pdd_drug_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_pdd_created_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropForeignKey('et_ophtroperationnote_pdd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_pdd_created_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_pdd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_pdd_drug_id_fk','et_ophtroperationnote_drugs_drug');
		$this->dropIndex('et_ophtroperationnote_pdd_drugs_id_fk','et_ophtroperationnote_drugs_drug');
		$this->renameColumn('et_ophtroperationnote_drugs_drug','et_ophtroperationnote_postop_drugs_id','et_ophtroperationnote_drugs_id');
		$this->createIndex('et_ophtroperationnote_dd_drugs_id_fk','et_ophtroperationnote_drugs_drug','et_ophtroperationnote_drugs_id');
		$this->createIndex('et_ophtroperationnote_dd_drug_id_fk','et_ophtroperationnote_drugs_drug','drug_id');
		$this->createIndex('et_ophtroperationnote_dd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug','last_modified_user_id');
		$this->createIndex('et_ophtroperationnote_dd_created_user_id_fk','et_ophtroperationnote_drugs_drug','created_user_id');
		$this->addForeignKey('et_ophtroperationnote_dd_drugs_id_fk','et_ophtroperationnote_drugs_drug','et_ophtroperationnote_drugs_id','et_ophtroperationnote_postop_drugs','id');
		$this->addForeignKey('et_ophtroperationnote_dd_drug_id_fk','et_ophtroperationnote_drugs_drug','drug_id','drug','id');
		$this->addForeignKey('et_ophtroperationnote_dd_created_user_id_fk','et_ophtroperationnote_drugs_drug','created_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_dd_last_modified_user_id_fk','et_ophtroperationnote_drugs_drug','last_modified_user_id','user','id');

		$this->dropForeignKey('et_ophtroperationnote_postop_drugs_created_user_id_fk','et_ophtroperationnote_postop_drugs');
		$this->dropForeignKey('et_ophtroperationnote_postop_drugs_last_modified_user_id_fk','et_ophtroperationnote_postop_drugs');
		$this->dropIndex('et_ophtroperationnote_postop_drugs_created_user_id_fk','et_ophtroperationnote_postop_drugs');
		$this->dropIndex('et_ophtroperationnote_postop_drugs_last_modified_user_id_fk','et_ophtroperationnote_postop_drugs');
		$this->createIndex('et_ophtroperationnote_drugs_type_last_modified_user_id_fk','et_ophtroperationnote_postop_drugs','last_modified_user_id');
		$this->createIndex('et_ophtroperationnote_drugs_type_created_user_id_fk','et_ophtroperationnote_postop_drugs','created_user_id');
		$this->addForeignKey('et_ophtroperationnote_drugs_type_last_modified_user_id_fk','et_ophtroperationnote_postop_drugs','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophtroperationnote_drugs_type_created_user_id_fk','et_ophtroperationnote_postop_drugs','created_user_id','user','id');
		$this->renameTable('et_ophtroperationnote_postop_drugs','et_ophtroperationnote_drugs');

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:event_type_id',array(':name'=>'Post-op drugs',':event_type_id'=>$event_type['id']))->queryRow();

		$this->update('element_type',array('name'=>'Drugs','class_name'=>'ElementDrugs'),'id='.$element_type['id']);
	}
}
