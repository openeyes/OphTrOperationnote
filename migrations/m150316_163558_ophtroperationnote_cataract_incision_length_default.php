<?php

class m150316_163558_ophtroperationnote_cataract_incision_length_default extends CDbMigration
{
	public function up()
	{
	      $this->createTable('ophtroperationnote_cataract_incision_length_default', array(
		    'id' => 'pk',
		    'firm_id' => 'int',
		    'value' => 'float'	
		));
	}

	public function down()
	{
		echo "m150316_163558_ophtroperationnote_cataract_incision_length_default does not support migration down.\n";
		return false;
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
