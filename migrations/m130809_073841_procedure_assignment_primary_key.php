<?php

class m130809_073841_procedure_assignment_primary_key extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_procedurelist_procedure_assignment','id','int(10) unsigned NULL');

		foreach (Yii::app()->db->createCommand()->select("*")->from("et_ophtroperationnote_procedurelist_procedure_assignment")->order("created_date asc")->queryAll() as $i => $row) {
			$this->update('et_ophtroperationnote_procedurelist_procedure_assignment',array('id'=>($i+1)),"procedurelist_id=:plid and proc_id=:prid",array(":plid"=>$row['procedurelist_id'],":prid"=>$row['proc_id']));
		}

		Yii::app()->db->createCommand("alter table et_ophtroperationnote_procedurelist_procedure_assignment drop primary key;")->query();
		Yii::app()->db->createCommand("alter table et_ophtroperationnote_procedurelist_procedure_assignment add primary key (id);")->query();
		Yii::app()->db->createCommand("alter table et_ophtroperationnote_procedurelist_procedure_assignment add key `procedurelist_procid_key` (`procedurelist_id`,`proc_id`)")->query();

		$this->alterColumn('et_ophtroperationnote_procedurelist_procedure_assignment','id','int(10) unsigned NOT NULL AUTO_INCREMENT');
	}

	public function down()
	{
		$this->alterColumn('et_ophtroperationnote_procedurelist_procedure_assignment','id','int(10) unsigned NOT NULL');

		$this->dropIndex('procedurelist_procid_key','et_ophtroperationnote_procedurelist_procedure_assignment');
		Yii::app()->db->createCommand("alter table et_ophtroperationnote_procedurelist_procedure_assignment drop primary key;")->query();
		Yii::app()->db->createCommand("alter table et_ophtroperationnote_procedurelist_procedure_assignment add primary key (procedurelist_id,proc_id)")->query();

		$this->dropColumn('et_ophtroperationnote_procedurelist_procedure_assignment','id');
	}
}
