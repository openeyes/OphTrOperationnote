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
 * This is the model class for table "et_ophtroperationnote_anaesthetic".
 *
 * The followings are the available columns in table 'et_ophtroperationnote_anaesthetic':
 * @property integer $id
 * @property integer $event_id
 * @property integer $anaesthetic_type_id
 * @property integer $anaesthetist_id
 * @property integer $anaesthetic_delivery_id
 * @property string $anaesthetic_comment
 * @property integer $display_order
 * @property integer $anaesthetic_witness_id
 *
 * The followings are the available model relations:
 * @property Event $event
 * @property EventType $eventType
 * @property ElementType $element_type
 * @property AnaestheticType $anaesthetic_type
 * @property Anaesthetist $anaesthetist
 * @property AnaestheticDelivery $anaesthetic_delivery
 * @property OphTrOperationnote_OperationAnaestheticAgent[] $anaesthetic_agents
 * @property OphTrOperationnote_AnaestheticComplication[] $anaesthetic_complications
 * @property User $witness
 */
class Element_OphTrOperationnote_Anaesthetic extends BaseEventTypeElement
{
	public $service;
	public $surgeonlist;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Element_OphTrOperationnote_Anaesthetic the static model class
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
		return 'et_ophtroperationnote_anaesthetic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, anaesthetist_id, anaesthetic_type_id, anaesthetic_delivery_id, anaesthetic_comment, anaesthetic_witness_id', 'safe'),
			array('anaesthetic_type_id, anaesthetist_id, anaesthetic_delivery_id', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, anaesthetist_id, anaesthetic_type_id, anaesthetic_delivery_id, anaesthetic_comment, anaesthetic_witness_id', 'safe', 'on' => 'search'),
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
			'anaesthetist' => array(self::BELONGS_TO, 'Anaesthetist', 'anaesthetist_id'),
			'anaesthetic_delivery' => array(self::BELONGS_TO, 'AnaestheticDelivery', 'anaesthetic_delivery_id'),
			'anaesthetic_agents' => array(self::HAS_MANY, 'OphTrOperationnote_OperationAnaestheticAgent', 'et_ophtroperationnote_anaesthetic_id'),
			'anaesthetic_complications' => array(self::HAS_MANY, 'OphTrOperationnote_AnaestheticComplication', 'et_ophtroperationnote_anaesthetic_id'),
			'witness' => array(self::BELONGS_TO, 'User', 'anaesthetic_witness_id'),
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
			'agents' => 'Agents',
			'anaesthetic_type_id' => 'Type',
			'anaesthetic_witness_id' => 'Witness',
			'anaesthetist_id' => 'Given by',
			'anaesthetic_delivery_id' => 'Delivery',
			'anaesthetic_comment' => 'Comments',
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
		$criteria->compare('anaesthetic_type_id', $this->anaesthetic_type_id);
		$criteria->compare('anaesthetist_id', $this->anaesthetist_id);
		$criteria->compare('anaesthetic_type_id', $this->anaesthetic_type_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria' => $criteria,
		));
	}

	/**
	* Set default values for forms on create
	*/
	public function setDefaultOptions()
	{
		$this->anaesthetic_type_id = 1;

		if (Yii::app()->getController()->getAction()->id == 'create') {
			if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
				throw new SystemException('Patient not found: '.@$_GET['patient_id']);
			}

			if (($episode = $patient->getEpisodeForCurrentSubspecialty()) &&
				($api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) &&
				($booking = $api->getMostRecentBookingForEpisode($patient, $episode))) {
				$this->anaesthetic_type_id = $booking->operation->anaesthetic_type_id;
			} else {
				$key = $patient->isChild() ? 'ophtroperationnote_default_anaesthetic_child' : 'ophtroperationnote_default_anaesthetic';

				if (isset(Yii::app()->params[$key])) {
					if ($at = AnaestheticType::model()->find('code=?',array(Yii::app()->params[$key]))) {
						$this->anaesthetic_type_id = $at->id;
					}
				}
			}
		}
	}

	public function getHidden()
	{
		if (Yii::app()->getController()->getAction()->id == 'create') {
			if (empty($_POST)) {
				return ($this->anaesthetic_type_id == 5);
			} else {
				return (@$_POST['Element_OphTrOperationnote_Anaesthetic']['anaesthetic_type_id'] == 5);
			}
		} else {
			if (empty($_POST)) {
				if ($this->event_id) {
					$anaesthetic_element = Element_OphTrOperationnote_Anaesthetic::model()->find('event_id=?',array($this->event_id));
					return ($anaesthetic_element->anaesthetic_type_id == 5);
				}
			}

			return (@$_POST['Element_OphTrOperationnote_Anaesthetic']['anaesthetic_type_id'] == 5);
		}
	}

	public function getWitness_hidden()
	{
		if (Yii::app()->getController()->getAction()->id == 'create') {
			return (@$_POST['Element_OphTrOperationnote_Anaesthetic']['anaesthetist_id'] != 3);
		} else {
			if (empty($_POST)) {
				$anaesthetic_element = Element_OphTrOperationnote_Anaesthetic::model()->find('event_id=?',array($this->event_id));
				return ($anaesthetic_element->anaesthetist_id != 3);
			}

			return (@$_POST['Element_OphTrOperationnote_Anaesthetic']['anaesthetist_id'] != 3);
		}
	}

	public function getAnaesthetic_agent_list()
	{
		return $this->getAnaestheticAgentsBySiteAndSubspecialty();
	}

	// TODO: the anaesthetic agent list should be managed by the controller if it is dependent on controller state
	public function getAnaestheticAgentsBySiteAndSubspecialty($relation = 'siteSubspecialtyAssignments')
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition('site_id = :siteId and subspecialty_id = :subspecialtyId');
		$criteria->params[':siteId'] = Yii::app()->session['selected_site_id'];
		$criteria->params[':subspecialtyId'] = Firm::model()->findByPk(Yii::app()->session['selected_firm_id'])->serviceSubspecialtyAssignment->subspecialty_id;
		$criteria->order = 'name';

		return CHtml::listData(AnaestheticAgent::model()
			->with(array(
				$relation => array(
					'joinType' => 'JOIN',
				),
			))
			->findAll($criteria),'id','name');
	}

	public function getAnaesthetic_agent_defaults()
	{
		$ids = array();
		foreach ($this->getAnaestheticAgentsBySiteAndSubspecialty('siteSubspecialtyAssignmentDefaults') as $id => $anaesthetic_agent) {
			$ids[] = $id;
		}
		return $ids;
	}

	/**
	 * Need to delete associated records
	 * @see CActiveRecord::beforeDelete()
	 */
	protected function beforeDelete()
	{
		OphTrOperationnote_OperationAnaestheticAgent::model()->deleteAllByAttributes(array('et_ophtroperationnote_anaesthetic_id' => $this->id));
		OphTrOperationnote_AnaestheticComplication::model()->deleteAllByAttributes(array('et_ophtroperationnote_anaesthetic_id' => $this->id));
		return parent::beforeDelete();
	}

	protected function afterSave()
	{
		$existing_agent_ids = array();

		foreach (OphTrOperationnote_OperationAnaestheticAgent::model()->findAll('et_ophtroperationnote_anaesthetic_id = :anaestheticId', array(':anaestheticId' => $this->id)) as $oaa) {
			$existing_agent_ids[] = $oaa->anaesthetic_agent_id;
		}

		if (isset($_POST['AnaestheticAgent'])) {
			foreach ($_POST['AnaestheticAgent'] as $id) {
				if (!in_array($id,$existing_agent_ids)) {
					$anaesthetic_agent = new OphTrOperationnote_OperationAnaestheticAgent;
					$anaesthetic_agent->et_ophtroperationnote_anaesthetic_id = $this->id;
					$anaesthetic_agent->anaesthetic_agent_id = $id;

					if (!$anaesthetic_agent->save()) {
						throw new Exception('Unable to save anaesthetic_agent: '.print_r($anaesthetic_agent->getErrors(),true));
					}
				}
			}
		}

		foreach ($existing_agent_ids as $id) {
			if (!isset($_POST['AnaestheticAgent']) || !in_array($id,$_POST['AnaestheticAgent'])) {
				$oaa = OphTrOperationnote_OperationAnaestheticAgent::model()->find('et_ophtroperationnote_anaesthetic_id = :anaestheticId and anaesthetic_agent_id = :anaestheticAgentId',array(':anaestheticId' => $this->id, ':anaestheticAgentId' => $id));
				if (!$oaa->delete()) {
					throw new Exception('Unable to delete anaesthetic agent: '.print_r($oaa->getErrors(),true));
				}
			}
		}

		$existing_complication_ids = array();

		foreach (OphTrOperationnote_AnaestheticComplication::model()->findAll('et_ophtroperationnote_anaesthetic_id = :anaestheticId', array(':anaestheticId' => $this->id)) as $ac) {
			$existing_complication_ids[] = $ac->anaesthetic_complication_id;
		}

		if (isset($_POST['OphTrOperationnote_AnaestheticComplications'])) {
			foreach ($_POST['OphTrOperationnote_AnaestheticComplications'] as $id) {
				if (!in_array($id,$existing_complication_ids)) {
					$anaesthetic_complication = new OphTrOperationnote_AnaestheticComplication;
					$anaesthetic_complication->et_ophtroperationnote_anaesthetic_id = $this->id;
					$anaesthetic_complication->anaesthetic_complication_id = $id;

					if (!$anaesthetic_complication->save()) {
						throw new Exception('Unable to save anaesthetic_complication: '.print_r($anaesthetic_complication->getErrors(),true));
					}
				}
			}
		}

		foreach ($existing_complication_ids as $id) {
			if (!isset($_POST['OphTrOperationnote_AnaestheticComplications']) || !in_array($id,$_POST['OphTrOperationnote_AnaestheticComplications'])) {
				$ac = OphTrOperationnote_AnaestheticComplication::model()->find('et_ophtroperationnote_anaesthetic_id = :anaestheticId and anaesthetic_complication_id = :anaestheticComplicationId',array(':anaestheticId' => $this->id, ':anaestheticComplicationId' => $id));
				if (!$ac->delete()) {
					throw new Exception('Unable to delete anaesthetic complication: '.print_r($ac->getErrors(),true));
				}
			}
		}

		return parent::afterSave();
	}

	public function getAnaesthetic_complication_list()
	{
	}

	public function getSurgeons()
	{
		if (!$this->surgeonlist) {
			$criteria = new CDbCriteria;
			$criteria->compare('is_doctor',1);
			$criteria->order = 'first_name,last_name asc';

			$this->surgeonlist = User::model()->findAll($criteria);
		}

		return $this->surgeonlist;
	}

	public function beforeValidate()
	{
		if (Yii::app()->params['fife']) {
			if (@$_POST['Element_OphTrOperationnote_Anaesthetic']['anaesthetist_id'] == 3) {
				if (!@$_POST['Element_OphTrOperationnote_Anaesthetic']['anaesthetic_witness_id']) {
					$this->addError('anaesthetic_witness_id','Please select a witness');
				}
			}
		}

		return parent::beforeValidate();
	}
}
