<?php

class m130218_125200_new_proc_elements_shouldnt_be_default extends CDbMigration
{
	public function up()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));

		foreach (array('ElementRemovalOfEyelash','ElementRemovalOfForeignBodyFromConjunctiva','ElementExcisionOfLesionOfCornea','ElementAdjustmentToCornealSuture','ElementRemovalOfReleasableSuture','ElementReformationOfAnteriorChamber','ElementTopicalLocalAnaesthetic','ElementConjunctivalSwab','ElementICGAngiogram','ElementBScanUltrasound','ElementScanningLaserOphthalmoscopy','ElementOpticalCoherenceTomography','ElementInsertionSlowRelease') as $class) {
			$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,$class));
			$this->update('element_type',array('default'=>0),'id='.$element_type->id);
		}
	}

	public function down()
	{
		$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));

		foreach (array('ElementRemovalOfEyelash','ElementRemovalOfForeignBodyFromConjunctiva','ElementExcisionOfLesionOfCornea','ElementAdjustmentToCornealSuture','ElementRemovalOfReleasableSuture','ElementReformationOfAnteriorChamber','ElementTopicalLocalAnaesthetic','ElementConjunctivalSwab','ElementICGAngiogram','ElementBScanUltrasound','ElementScanningLaserOphthalmoscopy','ElementOpticalCoherenceTomography','ElementInsertionSlowRelease') as $class) {
			$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,$class));
			$this->update('element_type',array('default'=>1),'id='.$element_type->id);
		}
	}
}
