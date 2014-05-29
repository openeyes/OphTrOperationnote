<?php

class m140529_123505_remove_unneeded_item extends CDbMigration
{
	public function up()
	{
		$this->delete('ophtroperationnote_trabeculectomy_sclerostomy_type',"name = 'Ex-Press shunt'");
	}

	public function down()
	{
		$this->insert('ophtroperationnote_trabeculectomy_sclerostomy_type',array('id'=>3,'name'=>'Ex-Press shunt','display_order'=>3));
	}
}
