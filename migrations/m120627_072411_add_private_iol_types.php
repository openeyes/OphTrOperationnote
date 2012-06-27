<?php

class m120627_072411_add_private_iol_types extends CDbMigration
{
	public function up()
	{
		foreach (array('570C','ADAPT-AO','AKREOS.A','AT 809M','CT Asphina','CZ70BD','ICL','L-302-1','MA60AC','MA60MA','MTA3U0','MTA4U0','Oculentis L-313','SA60AT','SN60AT','SN60T8','SN60WF','SN6AD1') as $i => $iol) {
			$this->insert('et_ophtroperationnote_cataract_iol_type',array('name'=>$iol,'display_order'=>($i+8),'private'=>1));
		}
	}

	public function down()
	{
		$this->delete('et_ophtroperationnote_cataract_iol_type','private=1');
	}
}
