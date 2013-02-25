<?php

class m130225_101706_opnote_link_to_booking_event extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophtroperationnote_procedurelist','booking_event_id','int(10) unsigned NULL');
		$this->createIndex('et_ophtroperationnote_procedurelist_bei_fk','et_ophtroperationnote_procedurelist','booking_event_id');
		$this->addForeignKey('et_ophtroperationnote_procedurelist_bei_fk','et_ophtroperationnote_procedurelist','booking_event_id','event','id');
	}

	public function down()
	{
		$this->dropForeignKey('et_ophtroperationnote_procedurelist_bei_fk','et_ophtroperationnote_procedurelist');
		$this->dropIndex('et_ophtroperationnote_procedurelist_bei_fk','et_ophtroperationnote_procedurelist');
		$this->dropColumn('et_ophtroperationnote_procedurelist','booking_event_id');
	}
}
