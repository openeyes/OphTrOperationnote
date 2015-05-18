<?php

class m150518_111819_modifyCataractComplication extends CDbMigration
{
	protected $newComplications = array(
		"None",
		"Zonule rupture no vitreous loss",
		"Zonule rupture with vitreous loss",
		"PC rupture no vitreous loss",
		"PC rupture with vitreous loss",
		"Lens fragments into vitreous",
		"Other");

	protected $inactivateComplications = array("Zonular rupture","PC rupture");

	protected $changeComplications = array("Wound burn"=>"Phaco wound burn","Choroidal haem"=>"Choroidal / expulsive haemorrhage");

	public function up()
	{
		// inserting new rows
		foreach( $this->newComplications as $newComp ){
			$this->insert('ophtroperationnote_cataract_complications', array('name'=>$newComp));
		}

		// inactivating rows
		foreach( $this->inactivateComplications as $inactivateComp ) {
			$this->update('ophtroperationnote_cataract_complications', array('active'=>0), "name = :name", array(":name"=>$inactivateComp));
		}

		// updating rows
		foreach( $this->changeComplications as $changeCompKey=>$changeCompValue){
			var_dump($changeCompKey);
			var_dump($changeCompValue);
			$this->update('ophtroperationnote_cataract_complications', array('name'=>$changeCompValue), "name = :name", array(":name"=>$changeCompKey));
		}
	}

	public function down()
	{
		foreach( $this->changeComplications as $changeCompKey=>$changeCompValue){
			$this->update('ophtroperationnote_cataract_complications', array('name'=>$changeCompKey), "name = :name", array(":name"=>$changeCompValue));
		}

		foreach( $this->inactivateComplications as $inactivateComp ) {
			$this->update('ophtroperationnote_cataract_complications', array('active'=>1), "name = :name", array(":name"=>$inactivateComp));
		}

		foreach( $this->newComplications as $newComp ){
			$this->delete('ophtroperationnote_cataract_complications', "name = :name", array(':name'=>$newComp));
		}
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