<?php

class m120719_143700_separate_postop_drug_table extends CDbMigration {

	public function up() {

		// Remove old foreign key to prevent ref int issues
		$this->dropForeignKey('et_ophtroperationnote_pdd_drug_id_fk', 'et_ophtroperationnote_postop_drugs_drug');

		// Update drug relationships to new table
		$drugs = $this->dbConnection->createCommand()
		->selectDistinct('drug.id, drug.name')
		->from('drug')
		->join('et_ophtroperationnote_postop_drugs_drug','et_ophtroperationnote_postop_drugs_drug.drug_id = drug.id')
		->query();
		if($drugs) {
			
			// Use datestamp to avoid colliding IDs
			$datestamp = date('Y-m-d H:i:s');
			
			foreach($drugs as $drug) {
				$new_drug_id = $this->dbConnection->createCommand()
				->select('id')
				->from('postop_drug')
				->where('name = :drug_name')
				->queryScalar(array(':drug_name' => $drug['name']));
				$this->update('et_ophtroperationnote_postop_drugs_drug',
						array('drug_id' => $new_drug_id, 'last_modified_date' => $datestamp),
						'drug_id = :drug_id AND last_modified_date != :datestamp',
						array(':drug_id' => $drug['id'], ':datestamp' => $datestamp)
				);
			}
		}

		// Add new foreign key
		$this->addForeignKey('et_ophtroperationnote_pdd_drug_id_fk', 'et_ophtroperationnote_postop_drugs_drug', 'drug_id', 'postop_drug', 'id');

		// TODO: Remove drugs from drug table
		
	}

	public function down() {
		$this->dropForeignKey('et_ophtroperationnote_pdd_drug_id_fk', 'et_ophtroperationnote_postop_drugs_drug');
		$drugs = $this->dbConnection->createCommand()
		->select('id, name')
		->from('postop_drug')
		->query();
		if($drugs) {
			$datestamp = date('Y-m-d H:i:s');
			foreach($drugs as $drug) {
				$new_drug_id = $this->dbConnection->createCommand()
				->select('id')
				->from('drug')
				->where('name = :drug_name')
				->queryScalar(array(':drug_name' => $drug['name']));
				$this->update('et_ophtroperationnote_postop_drugs_drug',
						array('drug_id' => $new_drug_id, 'last_modified_date' => $datestamp),
						'drug_id = :drug_id AND last_modified_date != :datestamp',
						array(':drug_id' => $drug['id'], ':datestamp' => $datestamp)
				);
			}
		}
		$this->addForeignKey('et_ophtroperationnote_pdd_drug_id_fk', 'et_ophtroperationnote_postop_drugs_drug', 'drug_id', 'drug', 'id');
	}

}
