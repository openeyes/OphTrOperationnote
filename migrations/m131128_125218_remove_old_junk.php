<?php

class m131128_125218_remove_old_junk extends CDbMigration
{
	public function up()
	{
		$opnote = Yii::app()->db->createCommand()->select("*")->from("event_type")->where("class_name = :class_name",array(":class_name" => "OphTrOperationnote"))->queryRow();

		foreach (array(
			'et_ophtroperationnote_al_trabeculoplasty' => 'ElementArgonLaserTrabeculoplasty',
			'et_ophtroperationnote_cycloablation' => 'ElementCycloablation',
			'et_ophtroperationnote_fl_photocoagulation' => 'ElementFocalLaserPhotocoagulation',
			'et_ophtroperationnote_laser_chor' => 'ElementLaserChorioretinal',
			'et_ophtroperationnote_laser_demarcation' => 'ElementLaserDemarcation',
			'et_ophtroperationnote_laser_gonio' => 'ElementLaserGonioplasty',
			'et_ophtroperationnote_laser_hyal' => 'ElementLaserHyaloidotomy',
			'et_ophtroperationnote_laser_irid' => 'ElementLaserIridoplasty',
			'et_ophtroperationnote_laser_vitr' => 'ElementLaserVitreolysis',
			'et_ophtroperationnote_macular_grid' => 'ElementMacularGrid',
			'et_ophtroperationnote_suture_lys' => 'ElementSutureLysis') as $table => $element) {

			$element_type = Yii::app()->db->createCommand()->select('id')->from("element_type")->where("event_type_id=:event_type_id and class_name=:class_name",array(':event_type_id'=>$opnote['id'],':class_name'=>$element))->queryRow();
			$pe = Yii::app()->db->createCommand()->select("*")->from("ophtroperationnote_procedure_element")->where("element_type_id=:element_type_id",array(':element_type_id'=>$element_type['id']))->queryRow();

			foreach (Yii::app()->db->createCommand()->select("*")->from($table)->order('id asc')->queryAll() as $row) {
				$this->insert('et_ophtroperationnote_genericprocedure',array(
						'event_id' => $row['event_id'],
						'proc_id' => $pe['procedure_id'],
						'comments' => $row['comments'],
						'created_user_id' => $row['created_user_id'],
						'created_date' => $row['created_date'],
						'last_modified_user_id' => $row['last_modified_user_id'],
						'last_modified_date' => $row['last_modified_date'],
					));
			}

			if ($pe['id']) {
				$this->delete('ophtroperationnote_procedure_element',"id={$pe['id']}");
			}
			$this->delete('element_type',"id={$element_type['id']}");
			$this->dropTable($table);
		}
	}

	public function down()
	{
	}
}
