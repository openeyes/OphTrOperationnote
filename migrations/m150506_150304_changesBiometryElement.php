<?php

class m150506_150304_changesBiometryElement extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select("*")->from("event_type")->where("class_name = :class_name",array(":class_name"=>"OphTrOperationnote"))->queryRow();

		$parent_element = $this->dbConnection->createCommand()->select("*")->from("element_type")->where("class_name = :class_name",array(":class_name"=>"Element_OphTrOperationnote_ProcedureList"))->queryRow();

		$this->insert('element_type',array('event_type_id'=>$event_type['id'],'name'=>'Biometry','class_name'=>'Element_OphTrOperationnote_Biometry','display_order'=>10,'default'=>0, 'parent_element_type_id'=>$parent_element['id']));

		$element_type = $this->dbConnection->createCommand()->select("*")->from("element_type")->where("event_type_id = :event_type_id and class_name = :class_name",array(":event_type_id"=>$event_type['id'],":class_name"=>"Element_OphTrOperationnote_Biometry"))->queryRow();

		$proc = $this->dbConnection->createCommand()->select("*")->from("proc")->where("term = :term",array(":term" => "Extracapsular cataract extraction"))->queryRow();
		if ($proc) {
			$this->insert('ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
		} else {
			echo "**WARNING** 'Extracapsular cataract extraction' not present in proc table, not linking to element type\n";
		}
		unset($proc);
		$proc = $this->dbConnection->createCommand()->select("*")->from("proc")->where("term = :term",array(":term" => "Extracapsular cataract extraction and insertion of IOL"))->queryRow();
		if ($proc) {
			$this->insert('ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
		} else {
			echo "**WARNING** 'Extracapsular cataract extraction and insertion of IOL' not present in proc table, not linking to element type\n";
		}

		unset($proc);
		$proc = $this->dbConnection->createCommand()->select("*")->from("proc")->where("term = :term",array(":term" => "Intracapsular cataract extraction"))->queryRow();
		if ($proc) {
			$this->insert('ophtroperationnote_procedure_element',array('procedure_id'=>$proc['id'],'element_type_id'=>$element_type['id']));
		} else {
			echo "**WARNING** 'Intracapsular cataract extraction' not present in proc table, not linking to element type\n";
		}

		$this->execute("CREATE OR REPLACE VIEW et_ophtroperationnote_biometry AS
							SELECT eol.event_id, eol.eye_id, eol.last_modified_date AS last_modified_date, target_refraction_left, target_refraction_right,
							(SELECT name FROM ophinbiometry_lenstype_lens oll WHERE oll.id=lens_id_left) as lens_left,
							(SELECT description FROM ophinbiometry_lenstype_lens oll WHERE oll.id=lens_id_left) as lens_description_left,
							(SELECT acon FROM ophinbiometry_lenstype_lens oll WHERE oll.id=lens_id_left) AS lens_acon_left,
							(SELECT name FROM ophinbiometry_lenstype_lens oll WHERE oll.id=lens_id_right) as lens_right,
							(SELECT description FROM ophinbiometry_lenstype_lens oll WHERE oll.id=lens_id_right) as lens_description_right,
							(SELECT acon FROM ophinbiometry_lenstype_lens oll WHERE oll.id=lens_id_right) AS lens_acon_right,
							k1_left, k1_right, k2_left, k2_right, axis_k1_left, axis_k1_right, axial_length_left, axial_length_right, snr_left,
							snr_right, iol_power_left, iol_power_right, predicted_refraction_left, predicted_refraction_right
							FROM et_ophinbiometry_lenstype eol
							JOIN et_ophinbiometry_calculation eoc ON eoc.event_id=eol.event_id
							JOIN et_ophinbiometry_selection eos ON eos.event_id=eol.event_id
							ORDER BY eol.last_modified_date;");

	}

	public function down()
	{

		$this->execute("DROP VIEW et_ophtroperationnote_biometry;");

		$event_type = $this->dbConnection->createCommand()->select("*")->from("event_type")->where("class_name = :class_name",array(":class_name"=>"OphTrOperationnote"))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select("*")->from("element_type")->where("event_type_id = :event_type_id and class_name = :class_name",array(":event_type_id"=>$event_type['id'],":class_name"=>"Element_OphTrOperationnote_Biometry"))->queryRow();

		$this->delete('ophtroperationnote_procedure_element',"element_type_id = {$element_type['id']}");

		$this->delete('element_type', "id={$element_type['id']}");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}