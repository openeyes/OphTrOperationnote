<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "et_ophtroperationnote_procedurelist".
 *
 * The followings are the available columns in table 'et_ophtroperationnote_procedurelist':
 * @property string $id
 * @property integer $event_id
 *
 * The followings are the available model relations:
 * @property Event $event
 */
class ElementProcedureList extends BaseEventTypeElement
{
	public $service;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ElementProcedureList the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'et_ophtroperationnote_procedurelist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, eye_id, booking_event_id', 'safe'),
			array('eye_id', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, eye_id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'element_type' => array(self::HAS_ONE, 'ElementType', 'id','on' => "element_type.class_name='".get_class($this)."'"),
			'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
			'anaesthetic_type' => array(self::BELONGS_TO, 'AnaestheticType', 'anaesthetic_type_id'),
			'eye' => array(self::BELONGS_TO, 'Eye', 'eye_id'),
			'procedures' => array(self::MANY_MANY, 'Procedure', 'et_ophtroperationnote_procedurelist_procedure_assignment(procedurelist_id, proc_id)', 'order' => 'display_order ASC'),
			'procedure_assignments' => array(self::HAS_MANY, 'ProcedureListProcedureAssignment', 'procedurelist_id', 'order' => 'display_order ASC'),
			'bookingEvent' => array(self::BELONGS_TO, 'Event', 'booking_event_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'eye_id' => 'Eye',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('event_id', $this->event_id, true);
		$criteria->compare('eye_id', $this->eye_id, true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria' => $criteria,
		));
	}

	protected function afterSave()
	{
		if (!empty($_POST['Procedures_procs'])) {

			$existing_procedure_assignments = array();
			foreach (ProcedureListProcedureAssignment::model()->findAll('procedurelist_id = :id', array(':id' => $this->id)) as $procedure_assignment) {
				$existing_procedure_assignments[$procedure_assignment->proc_id] = $procedure_assignment;
			}

			$current_display_order = 1;
			foreach ($_POST['Procedures_procs'] as $procedure_id) {
				if (isset($existing_procedure_assignments[$procedure_id])) {
					$procedure_assignment = $existing_procedure_assignments[$procedure_id];
					if ($procedure_assignment->display_order != $current_display_order) {
						// Updated display order of existing assignment
						$procedure_assignment->display_order = $current_display_order;
						if (!$procedure_assignment->save()) {
							throw new Exception('Unable to save procedure assignment');
						}
					}
				} else {
					// Create new assignment
					$procedure_assignment = new ProcedureListProcedureAssignment;
					$procedure_assignment->procedurelist_id = $this->id;
					$procedure_assignment->proc_id = $procedure_id;
					$procedure_assignment->display_order = $current_display_order;
					if (!$procedure_assignment->save()) {
						throw new Exception('Unable to save procedure assignment');
					}
				}
				$current_display_order++;
			}

			foreach ($existing_procedure_assignments as $procedure_id => $procedure_assignment) {
				if (!in_array($procedure_id, $_POST['Procedures_procs'])) {
					// Delete removed procedure
					if (!$procedure_assignment->delete()) {
						throw new Exception('Unable to delete procedure assignment: '.print_r($pa->getErrors(),true));
					}
				}
			}
		}

		$this->event->episode->episode_status_id = 4;

		if (!$this->event->episode->save()) {
			throw new Exception('Unable to change episode status for episode '.$this->event->episode->id);
		}

		if (Yii::app()->getController() && Yii::app()->getController()->getAction()->id == 'create') {
			if ($this->booking_event_id && $api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
				$api->setOperationStatus($this->booking_event_id, 'Completed');
			}
		}

		return parent::afterSave();
	}

	protected function beforeValidate()
	{
		if (!empty($_POST) && (!isset($_POST['Procedures_procs']) || empty($_POST['Procedures_procs']))) {
			$this->addError('no_field', 'At least one procedure must be entered');
		}

		return parent::beforeValidate();
	}

	public function getSelected_procedures()
	{
		if (Yii::app()->getController()->getAction()->id == 'create') {
			if (ctype_digit($_GET['booking_event_id']) && $api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
				return $api->getProceduresForOperation($_GET['booking_event_id']);
			}
			return array();
		}

		if (Yii::app()->getController()->getAction()->id == 'update') {
			return $this->procedures;
		}
	}

	public function getSelectedEye()
	{
		if (ctype_digit(@$_GET['booking_event_id']) && $api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
			$eye = $api->getEyeForOperation($_GET['booking_event_id']);

			$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);

			if ($eye->name == 'Both' && !in_array($firm->serviceSubspecialtyAssignment->subspecialty_id,array(2,14))) {
				return Eye::model()->find('name=?',array('Right'));
			}

			return $eye;
		}
	}

	public function getFormOptions($table)
	{
		if ($table == 'eye') {
			$event_type = EventType::model()->find('class_name=?',array('OphTrOperationnote'));
			$element_type = ElementType::model()->find('event_type_id=? and class_name=?',array($event_type->id,'ElementProcedureList'));

			$criteria = new CDbCriteria;
			$criteria->addCondition('element_type_id = :elementTypeId');
			$criteria->params[':elementTypeId'] = $element_type->id;
			$criteria->order = 't.display_order asc';

			if (!in_array(Firm::model()->findByPk(Yii::app()->session['selected_firm_id'])->serviceSubspecialtyAssignment->subspecialty->name,array('Adnexal','Strabismus'))) {
				$criteria->addCondition('t.id != :three');
				$criteria->params[':three'] = 3;
			}

			return CHtml::listData(Eye::model()
				->with(array(
					'elementTypes' => array(
						'joinType' => 'JOIN',
					),
				))
				->findAll($criteria),'id','name');
		}

		return parent::getFormOptions($table);
	}
}
