<?php

class m120709_093714_add_new_cataract_complications extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('update et_ophtroperationnote_cataract_complications set display_order = display_order * 10;')->query();

		$this->insert('et_ophtroperationnote_cataract_complications',array('name'=>'Anterior Capsular tear','display_order'=>5));
		$this->insert('et_ophtroperationnote_cataract_complications',array('name'=>'Hyphaema','display_order'=>45));
	}

	public function down()
	{
		$this->delete('et_ophtroperationnote_cataract_complications',"name='Hyphaema'");
		$this->delete('et_ophtroperationnote_cataract_complications',"name='Anterior Capsular tear'");

		Yii::app()->db->createCommand('update et_ophtroperationnote_cataract_complications set display_order = display_order / 10;')->query();
	}
}
