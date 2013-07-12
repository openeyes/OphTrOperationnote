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
 * This is the model class for table "et_ophtroperationnote_cataract".
 *
 * The followings are the available columns in table 'et_ophtroperationnote_cataract':
 * @property integer $id
 * @property integer $event_id
 * @property integer $incision_site_id
 * @property string $length
 * @property string $meridian
 * @property integer $incision_type_id
 * @property string $eyedraw
 * @property string $report
 * @property integer $iol_position_id
 * @property string $complication_notes
 * @property string $eyedraw2
 * @property string $iol_power
 * @property integer $iol_type_id
 * @property string $report2
 *
 * The followings are the available model relations:
 * @property Event $event
 * @property IncisionType $incision_type
 * @property IncisionSite $incision_site
 * @property IOLPosition $iol_position
 * @property CataractComplication[] $complications
 * @property CataractOperativeDevice[] $operative_devices
 * @property IOLType $iol_type
 */
class ElementCataract extends BaseEventTypeElement
{
	public $service;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ElementCataract the static model class
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
		return 'et_ophtroperationnote_cataract';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, incision_site_id, length, meridian, incision_type_id, iol_position_id, iol_type_id, iol_power, eyedraw, report, complication_notes, eyedraw2, report2, predicted_refraction', 'safe'),
			array('incision_site_id, length, meridian, incision_type_id, iol_position_id, eyedraw, report, eyedraw2', 'required'),
			array('length', 'numerical', 'integerOnly' => false, 'numberPattern' => '/^[0-9](\.[0-9])?$/', 'message' => 'Length must be 0 - 9.9 in increments of 0.1'),
			array('meridian', 'numerical', 'integerOnly' => false, 'numberPattern' => '/^[0-9]{1,3}(\.[0-9])?$/', 'min' => 000, 'max' => 360, 'message' => 'Meridian must be 000.5 - 360.0 degrees'),
			array('predicted_refraction', 'numerical', 'integerOnly' => false, 'numberPattern' => '/^\-?[0-9]{1,2}(\.[0-9]{2})?$/', 'min' => -30, 'max' => 30, 'message' => 'Predicted refraction must be between -30.00 and 30.00'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, event_id, incision_site_id, length, meridian, incision_type_id, eyedraw, report, wound_burn, iris_trauma, zonular_dialysis, pc_rupture, decentered_iol, iol_exchange, dropped_nucleus, op_cancelled, corneal_odema, iris_prolapse, zonular_rupture, vitreous_loss, iol_into_vitreous, other_iol_problem, choroidal_haem', 'on' => 'search'),
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
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'incision_type' => array(self::BELONGS_TO, 'IncisionType', 'incision_type_id'),
			'incision_site' => array(self::BELONGS_TO, 'IncisionSite', 'incision_site_id'),
			'iol_position' => array(self::BELONGS_TO, 'IOLPosition', 'iol_position_id'),
			'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
			'complications' => array(self::HAS_MANY, 'CataractComplication', 'cataract_id'),
			'complicationItems' => array(self::MANY_MANY, 'CataractComplications', 'et_ophtroperationnote_cataract_complication(cataract_id, complication_id)'),
			'operative_devices' => array(self::HAS_MANY, 'CataractOperativeDevice', 'cataract_id'),
			'iol_type' => array(self::BELONGS_TO, 'IOLType', 'iol_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'incision_site_id' => 'Incision site',
			'incision_type_id' => 'Incision type',
			'iol_position_id' => 'IOL position',
			'iol_power' => 'IOL power',
			'iol_type_id' => 'IOL type',
			'length' => 'Length',
			'meridian' => 'Meridian',
			'report' => 'Details',
			'complication_notes' => 'Complication notes',
			'report2' => 'Details',
			'predicted_refraction' => 'Predicted refraction',
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

		return new CActiveDataProvider(get_class($this), array(
				'criteria' => $criteria,
			));
	}

	/**
	 * Set default values for forms on create
	 */
	public function setDefaultOptions()
	{
		if ($this->getSelectedEye()->id == 1) {
			$this->meridian = 0;
		}
	}

	/**
	 * Need to delete associated records
	 * @see CActiveRecord::beforeDelete()
	 */
	protected function beforeDelete()
	{
		CataractComplication::model()->deleteAllByAttributes(array('cataract_id' => $this->id));
		CataractOperativeDevice::model()->deleteAllByAttributes(array('cataract_id' => $this->id));
		return parent::beforeDelete();
	}

	protected function beforeSave()
	{
		return parent::beforeSave();
	}

	protected function afterSave()
	{
		$existing_complication_ids = array();

		foreach (CataractComplication::model()->findAll('cataract_id = :cataractId', array(':cataractId' => $this->id)) as $cc) {
			$existing_complication_ids[] = $cc->complication_id;
		}

		if (isset($_POST['CataractComplications'])) {
			foreach ($_POST['CataractComplications'] as $id) {
				if (!in_array($id,$existing_complication_ids)) {
					$complication = new CataractComplication;
					$complication->cataract_id = $this->id;
					$complication->complication_id = $id;

					if (!$complication->save()) {
						throw new Exception('Unable to save cataract complication: '.print_r($complication->getErrors(),true));
					}
				}
			}
		}

		foreach ($existing_complication_ids as $id) {
			if (!isset($_POST['CataractComplications']) || !in_array($id,$_POST['CataractComplications'])) {
				$cc = CataractComplication::model()->find('cataract_id = :cataractId and complication_id = :complicationId',array(':cataractId' => $this->id, ':complicationId' => $id));
				if (!$cc->delete()) {
					throw new Exception('Unable to delete cataract complication: '.print_r($cc->getErrors(),true));
				}
			}
		}

		$existing_device_ids = array();

		foreach (CataractOperativeDevice::model()->findAll('cataract_id = :cataractId', array(':cataractId' => $this->id)) as $cod) {
			$existing_device_ids[] = $cod->operative_device_id;
		}

		if (isset($_POST['CataractOperativeDevices'])) {
			foreach ($_POST['CataractOperativeDevices'] as $id) {
				if (!in_array($id,$existing_device_ids)) {
					$operative_device = new CataractOperativeDevice;
					$operative_device->cataract_id = $this->id;
					$operative_device->operative_device_id = $id;

					if (!$operative_device->save()) {
						throw new Exception('Unable to save cataract operative device: '.print_r($operative_device->getErrors(),true));
					}
				}
			}
		}

		foreach ($existing_device_ids as $id) {
			if (!isset($_POST['CataractOperativeDevices']) || !in_array($id,$_POST['CataractOperativeDevices'])) {
				$cod = CataractOperativeDevice::model()->find('cataract_id = :cataractId and operative_device_id = :operativeDeviceId', array(':cataractId' => $this->id, ':operativeDeviceId' => $id));
				if (!$cod->delete()) {
					throw new Exception('Unable to delete operative device: '.print_r($cod->getErrors(),true));
				}
			}
		}

		return parent::afterSave();
	}

	public function getSelectedEye()
	{
		if (Yii::app()->getController()->getAction()->id == 'create') {
			// Get the procedure list and eye from the most recent booking for the episode of the current user's subspecialty
			if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
				throw new SystemException('Patient not found: '.@$_GET['patient_id']);
			}

			if ($episode = $patient->getEpisodeForCurrentSubspecialty()) {
				if ($api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
					if ($booking = $api->getMostRecentBookingForEpisode($patient, $episode)) {
						return $booking->operation->eye;
					}
				}
			}
		}

		if (isset($_GET['eye'])) {
			return Eye::model()->findByPk($_GET['eye']);
		}

		return new Eye;
	}

	public function getEye()
	{
		return ElementProcedureList::model()->find('event_id=?',array($this->event_id))->eye;
	}

	public function getOperative_device_list()
	{
		return $this->getDevicesBySiteAndSubspecialty();
	}

	public function getOperative_device_defaults()
	{
		$ids = array();
		foreach ($this->getDevicesBySiteAndSubspecialty(true) as $id => $item) {
			$ids[] = $id;
		}
		return $ids;
	}

	public function getDevicesBySiteAndSubspecialty($default=false)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition('subspecialty_id = :subspecialtyId and site_id = :siteId');
		$criteria->params[':subspecialtyId'] = Firm::model()->findByPk(Yii::app()->session['selected_firm_id'])->serviceSubspecialtyAssignment->subspecialty_id;
		$criteria->params[':siteId'] = Yii::app()->session['selected_site_id'];

		if ($default) {
			$criteria->addCondition('siteSubspecialtyAssignments.default = :one');
			$criteria->params[':one'] = 1;
		}

		$criteria->order = 'name asc';

		return CHtml::listData(OperativeDevice::model()
			->with(array(
				'siteSubspecialtyAssignments' => array(
					'joinType' => 'JOIN',
				),
			))
			->findAll($criteria),'id','name');
	}

	public function getIOLTypes_NHS()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('private', 0);
		$criteria->order = 'display_order asc';

		return IOLType::model()->findAll($criteria);
	}

	public function getIOLTypes_Private()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('private', 1);
		$criteria->order = 'display_order asc';

		return IOLType::model()->findAll($criteria);
	}

	public function beforeValidate()
	{
		$iol_position = IOLPosition::model()->findByPk($this->iol_position_id);

		if (!$iol_position || $iol_position->name != 'None') {
			if (!$this->iol_type_id) {
				$this->addError('Cataract','IOL type cannot be blank');
			}
			if (!$this->iol_power) {
				$this->addError('Cataract','IOL power cannot be blank');
			} elseif (!preg_match('/^\-?[0-9]{1,3}(\.[0-9])?$/',$this->iol_power)) {
				$this->addError('Cataract','IOL power must be a number with an optional single decimal place');
			}
		}

		return parent::beforeValidate();
	}

	public function getIol_hidden()
	{
		if (!empty($_POST)) {
			$eyedraw = json_decode($_POST['ElementCataract']['eyedraw']);

			foreach ($eyedraw as $object) {
				if (in_array($object->subclass,Yii::app()->params['eyedraw_iol_classes'])) {
					return false;
				}
			}

			return true;
		}

		if ($eyedraw = @json_decode($this->eyedraw)) {
			if (is_array($eyedraw)) {
				foreach ($eyedraw as $object) {
					if (in_array($object->subclass,Yii::app()->params['eyedraw_iol_classes'])) {
						return false;
					}
				}
				return true;
			}
		}

		return false;
	}
}
