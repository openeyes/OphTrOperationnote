<?php

class m131128_125218_remove_old_junk extends CDbMigration
{
	public function up()
	{
		$this->dropTable('et_ophtroperationnote_al_trabeculoplasty');
		$this->dropTable('et_ophtroperationnote_cycloablation');
		$this->dropTable('et_ophtroperationnote_fl_photocoagulation');
		$this->dropTable('et_ophtroperationnote_laser_chor');
		$this->dropTable('et_ophtroperationnote_laser_demarcation');
		$this->dropTable('et_ophtroperationnote_laser_gonio');
		$this->dropTable('et_ophtroperationnote_laser_hyal');
		$this->dropTable('et_ophtroperationnote_laser_irid');
		$this->dropTable('et_ophtroperationnote_laser_vitr');
		$this->dropTable('et_ophtroperationnote_macular_grid');
		$this->dropTable('et_ophtroperationnote_suture_lys');

		$et_opnote = Yii::app()->db->createCommand()->select("id")->from("event_type")->where("class_name = :class_name",array(":class_name" => "OphTrOperationnote"))->queryRow();

		foreach (Yii::app()->db->createCommand()->select("id")->from("element_type")->where("event_type_id = :event_type_id and class_name not like :class_name",array(":event_type_id"=>$et_opnote['id'],":class_name" => "Element\\_%"))->queryAll() as $et) {
			$this->delete("ophtroperationnote_procedure_element","element_type_id = {$et['id']}");
			$this->delete("element_type","id = {$et['id']}");
		}
	}

	public function down()
	{
	}
}
