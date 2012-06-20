<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "element_procedurelist".
 *
 * The followings are the available columns in table 'element_operation':
 * @property string $id
 * @property integer $event_id
 * @property integer $surgeon_id
 * @property integer $assistant_id
 * @property integer $anaesthetic_type
 *
 * The followings are the available model relations:
 * @property Event $event
 */
class ElementCataract extends BaseEventTypeElement
{
	public $service;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ElementOperation the static model class
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
			array('event_id, incision_site_id, length, meridian, incision_type_id, iol_position_id, iol_power, iol_type_id, eyedraw, report, complication_notes, eyedraw2, skin_preparation_id, intraocular_solution_id, report2', 'safe'),
			array('incision_site_id, length, meridian, incision_type_id, iol_position_id, iol_power, iol_type_id, eyedraw, report, eyedraw2', 'required'),
			array('length', 'numerical', 'integerOnly' => false, 'numberPattern' => '/^[0-9](\.[0-9])?$/', 'message' => 'Length must be 0 - 9.9 in increments of 0.1'),
			array('meridian', 'numerical', 'integerOnly' => false, 'numberPattern' => '/^[0-9]{1,3}(\.[0-9])?$/', 'min' => 000, 'max' => 360, 'message' => 'Meridian must be 000.5 - 360.0 degrees'),
			array('iol_power', 'numerical', 'integerOnly' => false, 'numberPattern' => '/^\-?[0-9]{1,3}(\.[0-9])?$/', 'message' => 'IOL power must be a number with an optional single decimal place'),
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
			'operative_devices' => array(self::HAS_MANY, 'CataractOperativeDevice', 'cataract_id'),
			'iol_type' => array(self::BELONGS_TO, 'IOLType', 'iol_type_id'),
			'skin_preparation' => array(self::BELONGS_TO, 'CataractSkinPreparation', 'skin_preparation_id'),
			'intraocular_solution' => array(self::BELONGS_TO, 'CataractIntraocularSolution', 'intraocular_solution_id'),
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
			'iol_position_id' => 'IOL Position',
			'iol_power' => 'IOL Power',
			'iol_type_id' => 'IOL Type',
			'length' => 'Length',
			'meridian' => 'Meridian',
			'report' => 'Details',
			'complication_notes' => 'Complication notes',
			'skin_preparation_id' => 'Skin preparation',
			'intraocular_solution_id' => 'Intraocular solution',
			'report2' => 'Details',
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
	}

	protected function beforeSave()
	{
		return parent::beforeSave();
	}

	protected function afterSave()
	{
		if (!empty($_POST['CataractComplications'])) {

			$existing_complication_ids = array();

			foreach (CataractComplication::model()->findAll('cataract_id = :cataractId', array(':cataractId' => $this->id)) as $cc) {
				$existing_complication_ids[] = $cc->complication_id;
			}

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

			foreach ($existing_complication_ids as $id) {
				if (!in_array($id,$_POST['CataractComplications'])) {
					$cc = CataractComplication::model()->find('cataract_id = :cataractId and complication_id = :complicationId',array(':cataractId' => $this->id, ':complicationId' => $id));
					if (!$cc->delete()) {
						throw new Exception('Unable to delete cataract complication: '.print_r($cc->getErrors(),true));
					}
				}
			}
		}

		if (!empty($_POST['CataractOperativeDevices'])) {
			
			$existing_device_ids = array();

			foreach (CataractOperativeDevice::model()->findAll('cataract_id = :cataractId', array(':cataractId' => $this->id)) as $cod) {
				$existing_device_ids[] = $cod->operative_device_id;
			}

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

			foreach ($existing_device_ids as $id) {
				if (!in_array($id,$_POST['CataractOperativeDevices'])) {
					$cod = CataractOperativeDevice::model()->find('cataract_id = :cataractId and operative_device_id = :operativeDeviceId', array(':cataractId' => $this->id, ':operativeDeviceId' => $id));
					if (!$cod->delete()) {
						throw new Exception('Unable to delete operative device: '.print_r($cod->getErrors(),true));
					}
				}
			}
		}

		return parent::afterSave();
	}

	public function getSelectedEye() {
		if (Yii::app()->getController()->getAction()->id == 'create') {
			// Get the procedure list and eye from the most recent booking for the episode of the current user's subspecialty
			if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
				throw new SystemException('Patient not found: '.@$_GET['patient_id']);
			}

			if ($episode = $patient->getEpisodeForCurrentSubspecialty()) {
				if ($booking = $episode->getMostRecentBooking()) {
					return $booking->elementOperation->eye;
				}
			}
		}

		if (isset($_GET['eye'])) {
			return Eye::model()->findByPk($_GET['eye']);
		}

		return new Eye;
	}

	public function getEye() {
		return ElementProcedureList::model()->find('event_id=?',array($this->event_id))->eye;
	}

	public function getOperative_device_list() {
		return $this->getDevicesBySiteAndSubspecialty();
	}

	public function getOperative_device_defaults() {
		$ids = array();
		foreach ($this->getDevicesBySiteAndSubspecialty(true) as $id => $item) {
			$ids[] = $id;
		}
		return $ids;
	}

	public function getDevicesBySiteAndSubspecialty($default=false) {
		$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);
		$subspecialty_id = $firm->serviceSubspecialtyAssignment->subspecialty_id;
		$site_id = Yii::app()->request->cookies['site_id']->value;

		$params = array(':subSpecialtyId'=>$subspecialty_id,':siteId'=>$site_id);

		if ($default) {
			$where = ' and site_subspecialty_operative_device.default = :default ';
			$params[':default'] = 1;
		}

		return CHtml::listData(Yii::app()->db->createCommand()
			->select('operative_device.id, operative_device.name')
			->from('operative_device')
			->join('site_subspecialty_operative_device','site_subspecialty_operative_device.operative_device_id = operative_device.id')
			->where('site_subspecialty_operative_device.subspecialty_id = :subSpecialtyId and site_subspecialty_operative_device.site_id = :siteId'.@$where, $params)
			->order('operative_device.name asc')
			->queryAll(), 'id', 'name');
	}

	public function beforeValidate() {
		if (Yii::app()->params['fife']) {
			if (!@$_POST['ElementCataract']['skin_preparation_id']) {
				$this->addError('skin_preparation_id','Please select a skin preparation');
			}
			if (!@$_POST['ElementCataract']['intraocular_solution_id']) {
				$this->addError('intraocular_solution_id','Please select an intraocular solution');
			}
		}

		return parent::beforeValidate();
	}

	public function getIOLTypes_NHS() {
		$criteria = new CDbCriteria;

		$criteria->compare('private', 0);
		$criteria->order = 'display_order asc';

		return IOLType::model()->findAll($criteria);
	}

	public function getIOLTypes_Private() {
		$criteria = new CDbCriteria;
	
		$criteria->compare('private', 1);
		$criteria->order = 'display_order asc';

		return IOLType::model()->findAll($criteria);
	}
}
