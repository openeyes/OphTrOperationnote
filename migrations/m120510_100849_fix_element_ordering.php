<?php

class m120510_100849_fix_element_ordering extends CDbMigration
{
	public function up()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Procedure list'))->queryRow();
		$this->update('element_type',array('display_order'=>1),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Vitrectomy'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Membrane peel'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Tamponade'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Buckle'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Cataract'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Anaesthetic'))->queryRow();
		$this->update('element_type',array('display_order'=>3),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Surgeon'))->queryRow();
		$this->update('element_type',array('display_order'=>4),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Post-op drugs'))->queryRow();
		$this->update('element_type',array('display_order'=>5),'id='.$element_type['id']);
	}

	public function down()
	{
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('name=:name', array(':name'=>'Operation note'))->queryRow();

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Procedure list'))->queryRow();
		$this->update('element_type',array('display_order'=>1),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Vitrectomy'))->queryRow();
		$this->update('element_type',array('display_order'=>2),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Membrane peel'))->queryRow();
		$this->update('element_type',array('display_order'=>3),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Tamponade'))->queryRow();
		$this->update('element_type',array('display_order'=>4),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Buckle'))->queryRow();
		$this->update('element_type',array('display_order'=>5),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Cataract'))->queryRow();
		$this->update('element_type',array('display_order'=>6),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Anaesthetic'))->queryRow();
		$this->update('element_type',array('display_order'=>7),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Surgeon'))->queryRow();
		$this->update('element_type',array('display_order'=>4),'id='.$element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:event_type_id and name=:name', array(':event_type_id'=>$event_type['id'],':name'=>'Post-op drugs'))->queryRow();
		$this->update('element_type',array('display_order'=>5),'id='.$element_type['id']);
	}
}
