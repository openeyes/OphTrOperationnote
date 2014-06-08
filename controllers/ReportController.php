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

//TODO: direct use of models should be replaced by API when this is not master branch

class ReportController extends BaseController
{

	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('index', 'operation'),
				'roles' => array('OprnGenerateReport'),
			)
		);
	}

	protected function array2Csv(array $data)
	{
		if (count($data) == 0) {
			return null;
		}
		ob_start();
		$df = fopen("php://output", 'w');
		fputcsv($df, array_keys(reset($data)));
		foreach ($data as $row) {
			fputcsv($df, $row);
		}
		fclose($df);
		return ob_get_clean();
	}

	protected function sendCsvHeaders($filename)
	{
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=$filename");
		header("Pragma: no-cache");
		header("Expires: 0");
	}

	public function actionIndex()
	{
		$this->redirect(array('operation'));
	}

	public function actionOperation()
	{
		if (isset($_GET['yt0'])) {
			$surgeon = null;
			$date_from = date('Y-m-d', strtotime("-1 year"));
			$date_to = date('Y-m-d');

			if (@$_GET['surgeon_id'] && (int)$_GET['surgeon_id']) {
				$surgeon_id = (int)$_GET['surgeon_id'];
				if(!$surgeon = User::model()->findByPk($surgeon_id)) {
					throw new CException("Unknown surgeon $surgeon_id");
				}
			}
			if (@$_GET['date_from'] && date('Y-m-d', strtotime($_GET['date_from']))) {
				$date_from = date('Y-m-d', strtotime($_GET['date_from']));
			}
			if (@$_GET['date_to'] && date('Y-m-d', strtotime($_GET['date_to']))) {
				$date_to = date('Y-m-d', strtotime($_GET['date_to']));
			}
			$filter_procedures = null;
			if (@$_GET['Procedures_procs']) {
				$filter_procedures = $_GET['Procedures_procs'];
			}
			$filter_complications =  null;
			if (@$_GET['complications']) {
				$filter_complications = $_GET['complications'];
			}

			// ensure we don't hit PAS
			Yii::app()->event->dispatch('start_batch_mode');
			$results = $this->getOperations($surgeon, $filter_procedures, $filter_complications, $date_from, $date_to);
			Yii::app()->event->dispatch('end_batch_mode');

			$filename = 'operation_report_' . date('YmdHis') . '.csv';
			$this->sendCsvHeaders($filename);

			/*
			echo "\"Operation report for ";
			if($surgeon) {
				echo "$surgeon->first_name $surgeon->last_name";
			} else {
				echo "all surgeons";
			}
			echo " from $date_from to $date_to\"\n";
*/
			echo $this->array2Csv($results);
		} else {
			$context['surgeons'] = CHtml::listData(User::model()->findAll(array('condition' => 'is_surgeon = 1', 'order' => 'first_name,last_name')), 'id', 'fullname');
			$this->render('operation', $context);
		}
	}

	/**
	 * Generate operation report
	 * @param User $surgeon
	 * @param array $filter_procedures
	 * @param array $filter_complications
	 * @param $from_date
	 * @param $to_date
	 * @param array $appenders - list of methods to call with patient id and date to retrieve additional data for each row
	 * @return array
	 */
	protected function getOperations($surgeon = null, $filter_procedures = array(), $filter_complications = array(), $from_date, $to_date)
	{
		$filter_procedures_method = 'OR';
		$filter_complications_method = 'OR';

		$command = Yii::app()->db->createCommand()
			->select(
				"e.id, c.first_name, c.last_name, e.created_date, su.surgeon_id, su.assistant_id, su.supervising_surgeon_id, p.hos_num,p.gender, p.dob, pl.id as plid, cat.id as cat_id, eye.name AS eye"
			)
			->from("event e")
			->join("episode ep", "e.episode_id = ep.id")
			->join("patient p", "ep.patient_id = p.id")
			->join("et_ophtroperationnote_procedurelist pl", "pl.event_id = e.id")
			->join("et_ophtroperationnote_surgeon su", "su.event_id = e.id")
			->join("contact c", "p.contact_id = c.id")
			->join("eye", "eye.id = pl.eye_id")
			->leftJoin("et_ophtroperationnote_cataract cat", "cat.event_id = e.id")
			->where("e.deleted = 0 and ep.deleted = 0 and e.created_date >= :from_date and e.created_date < :to_date + interval 1 day")
			->order("p.id, e.created_date asc");
		$params = array(':from_date' => $from_date, ':to_date' => $to_date);



		if ($surgeon) {
			$command->andWhere(
				"(su.surgeon_id = :user_id or su.assistant_id = :user_id or su.supervising_surgeon_id = :user_id)"
			);
			$params[':user_id'] = $surgeon->id;
		}

		$results = array();
		$cache = array();
		foreach ($command->queryAll(true, $params) as $row) {
			set_time_limit(1);
			$complications = array();
			if ($row['cat_id']) {
				foreach (OphTrOperationnote_CataractComplication::model()->findAll('cataract_id = ?', array($row['cat_id'])) as $complication) {
					if(!isset($cache['complications'][$complication->complication_id])) {
						$cache['complications'][$complication->complication_id] = $complication->complication->name;
					}
					$complications[(string)$complication->complication_id] = $cache['complications'][$complication->complication_id];
				}
			}

			$matched_complications = 0;
			if ($filter_complications) {
				foreach ($filter_complications as $filter_complication) {
					if (isset($complications[$filter_complication])) {
						$matched_complications++;
					}
				}
				if (($filter_complications_method == 'AND' && $matched_complications < count(
							$filter_complications
						)) || !$matched_complications
				) {
					continue;
				}
			}

			$procedures = array();
			foreach (OphTrOperationnote_ProcedureListProcedureAssignment::model()->findAll('procedurelist_id = ?', array($row['plid'])) as $pa) {
				if(!isset($cache['procedures'][$pa->proc_id])) {
					$cache['procedures'][$pa->proc_id] = $pa->procedure->term;
				}
				$procedures[(string)$pa->proc_id] = $cache['procedures'][$pa->proc_id];
			}
			$matched_procedures = 0;
			if ($filter_procedures) {
				foreach ($filter_procedures as $filter_procedure) {
					if (isset($procedures[$filter_procedure])) {
						$matched_procedures++;
					}
				}
				if (($filter_procedures_method == 'AND' && $matched_procedures < count(
							$filter_procedures
						)) || !$matched_procedures
				) {
					continue;
				}
			}

			$record = array(
				"operation_date" => date('j M Y', strtotime($row['created_date'])),
				"patient_hosnum" => $row['hos_num'],
				"patient_firstname" => $row['first_name'],
				"patient_surname" => $row['last_name'],
				"patient_gender" => $row['gender'],
				"patient_dob" => date('j M Y', strtotime($row['dob'])),
				"eye" => $row['eye'],
				"procedures" => implode(', ', $procedures),
				"complications" => implode(', ', $complications),
			);

			if ($surgeon) {
				if ($row['surgeon_id'] == $surgeon->id) {
					$record['surgeon_role'] = 'Surgeon';
				} else {
					if ($row['assistant_id'] == $surgeon->id) {
						$record['surgeon_role'] = 'Assistant surgeon';
					} else {
						if ($row['supervising_surgeon_id'] == $surgeon->id) {
							$record['surgeon_role'] = 'Supervising surgeon';
						}
					}
				}
			}

			//appenders
			$this->appendPatientValues($record, $row['id']);
			$this->appendBookingValues($record, $row['id']);
			$this->appendOpNoteValues($record, $row['id']);
			$this->appendExaminationValues($record, $row['id']);

			$results[] = $record;
		}
		return $results;
	}
	protected function appendPatientValues(&$record, $event_id)
	{
		$event = Event::model()->findByPk($event_id);
		$patient = $event->episode->patient;
		if (@$_GET['patient_oph_diagnoses']) {
			$diagnoses = array();
			foreach ($patient->episodes as $ep) {
				if ($ep->diagnosis) {
					$diagnoses[] = (($ep->eye) ? $ep->eye->adjective . " " : "") . $ep->diagnosis->term;
				}
			}
			foreach ($patient->getOphthalmicDiagnoses() as $sd) {
				$diagnoses[] = $sd->eye->adjective . " " . $sd->disorder->term;
			}
			$record['patient_diagnoses'] = implode(', ', $diagnoses);
		}
	}

	protected function appendBookingValues(&$record, $event_id)
	{
		if ($api = Yii::app()->moduleAPI->get('OphTrOperationbooking')) {
			$procedure = Element_OphTrOperationnote_ProcedureList::model()->find('event_id=:event_id',array(':event_id'=>$event_id));
			$bookingEventID = $procedure['booking_event_id'];
			foreach (array('booking_diagnosis', 'theatre', 'bookingcomments','surgerydate') as $k) {
				if (@$_GET[$k]) {
					$record[$k] = '';
				}
			}
			if(isset($bookingEventID)){
				{
					$operationElement = $api->getOperationForEvent($bookingEventID);
					$latestBookingID = $operationElement['latest_booking_id'];
					$operationBooking = OphTrOperationbooking_Operation_Booking::model()->find('id=:id',array('id'=>$latestBookingID));

					if (@$_GET['booking_diagnosis']) {
						$diag_el = $operationElement->getDiagnosis();
						$disorder = $diag_el->disorder();
						if ($disorder) {
							$record['booking_diagnosis'] = $diag_el->eye->adjective  . " " . $disorder->term;
						} else {
							$record['booking_diagnosis'] = 'Unknown';
						}
					}

					if(@$_GET['theatre']) {

					$theatreName = $operationElement->site['name'].' '.$operationBooking->theatre['name'];
					$record['theatre'] = $theatreName;
					}

					if(@$_GET['bookingcomments']){
						$record['bookingcomments'] = $operationElement['comments'];
					}

					if( @$_GET['surgerydate']){
						$record['surgerydate']=$operationBooking['session_date'];
					}
				}
			}
		}
	}

	protected function appendExaminationValues(&$record, $event_id)
	{
		$event = Event::model()->with('episode')->findByPk($event_id);

		if ($api = Yii::app()->moduleAPI->get('OphCiExamination')) {

			$preOpCriteria = $this->preOperationNoteCriteria($event);
			$postOpCriteria = $this->postOperationNoteCriteria($event);

			if(@$_GET['comorbidities']) {
				$record['comorbidities'] = $this->getComorbidities($preOpCriteria);
			}

			if(@$_GET['target_refraction']) {
				$record['target_refraction']= $this->getTargetRefraction($preOpCriteria);
			}

			if(@$_GET['first_eye']) {
				$record['first_or_second_eye']=$this->getFirstEyeOrSecondEye($preOpCriteria);
			}

			if (@$_GET['va_values']) {
				$record['pre-op va'] = $this->getVaReading($preOpCriteria,$record);
				$record['most recent post-op va'] = $this->getVaReading($postOpCriteria,$record);
			}

			if (@$_GET['refraction_values']) {
				$record['pre-op refraction'] = $this->getRefractionReading($preOpCriteria,$record);
				$record['most recent post-op refraction'] = $this->getRefractionReading($postOpCriteria,$record);
			}
		}
	}

	protected function preOperationNoteCriteria($event)
	{
		return $this->operationNoteCriteria($event, true);
	}

	public function postOperationNoteCriteria($event)
	{
		return $this->operationNoteCriteria($event,false);
	}

	public function operationNoteCriteria($event, $searchBackwards)
	{
		$criteria = new CDbCriteria();
		if($searchBackwards) {
			$criteria->addCondition('event.created_date < :op_date');
		}
		else {
			$criteria->addCondition('event.created_date > :op_date');
		}
		$criteria->addCondition('event.episode_id = :episode_id');
		$criteria->params[':episode_id'] = $event->episode_id;
		$criteria->params[':op_date'] = $event->created_date;
		$criteria->order = 'event.created_date desc';
		$criteria->limit = 1;
		return $criteria;
	}

	protected function eyesCondition($record)
	{
		if (strtolower($record['eye']) == 'left') {
			$eyes = array(Eye::LEFT,Eye::BOTH);
		}
		else {
			$eyes = array(Eye::RIGHT, Eye::BOTH);
		}
		return $eyes;
	}

	protected function getComorbidities($criteria)
	{
		$comorbiditiesElement = Element_OphCiExamination_Comorbidities::model()->with(array('event'))->find($criteria);

		$comorbidities = array();
		if(isset($comorbiditiesElement->items)) {
			foreach($comorbiditiesElement->items as $comorbiditity){
				$comorbidities[] = $comorbiditity['name'];
			}
		return implode(',', $comorbidities);
		}
	}

	protected function getTargetRefraction($criteria)
	{
		$cataractManagementElement = Element_OphCiExamination_CataractSurgicalManagement::model()->with(array('event'))->find($criteria);
		if($cataractManagementElement ){
		return $cataractManagementElement['target_postop_refraction'];
		}
	}

	public function getFirstEyeOrSecondEye($criteria)
	{
		$cataractManagementElement = Element_OphCiExamination_CataractSurgicalManagement::model()->with(array('event'))->find($criteria);
		if($cataractManagementElement ){
		return $cataractManagementElement->eye['name'];
		}
	}

	public function getVAReading($criteria,$record)
	{
		$criteria->addInCondition('eye_id', $this->eyesCondition($record));
		$va = Element_OphCiExamination_VisualAcuity::model()->with(array('event'))->find($criteria);
		$reading = null;
		$sides = array(strtolower($record['eye']));
		if ($sides[0] == 'both') {
			$sides = array('left', 'right');
		}

		if ($va) {
			$res = '';
			foreach ($sides as $side) {
				$reading = $va->getBestReading($side);
				if ($res) {
					$res .= " ";
				}
				if ($reading) {
					$res .= ucfirst($side) . ": " . $reading->convertTo($reading->value, $va->unit_id) . ' (' . $reading->method->name . ')';
				}
				else {
					$res .= ucfirst($side) . ": Unknown";
				}
			}
			return $res;
		}
		return "Unknown";
	}

	public function getRefractionReading($criteria,$record)
	{
		$criteria->addInCondition('eye_id', $this->eyesCondition($record));
		$refraction = Element_OphCiExamination_Refraction::model()->with('event')->find($criteria);
		if ($refraction) {
			return $refraction->getCombined(strtolower($record['eye']));
		}
		else {
			return 'Unknown';
		}
	}

	protected function appendOpNoteValues(&$record, $event_id)
	{
		$anaesthetic=Element_OphTrOperationnote_Anaesthetic::model()->find('event_id = :event_id',array(':event_id'=>$event_id));

		if (@$_GET['anaesthetic_type']) {
			$record['anaesthetic_type']=$anaesthetic->anaesthetic_type['name'];
		}

		if (@$_GET['anaesthetic_delivery']) {
			$record['anaesthetic_delivery']=$anaesthetic->anaesthetic_delivery['name'];
		}

		if (@$_GET['anaesthetic_comments']) {
			$record['anaesthetic_comments']=$anaesthetic['anaesthetic_comment'];
		}

		if (@$_GET['anaesthetic_complications']) {
			$complications = array();
			if(isset($anaesthetic->anaesthetic_complications))
			{
			foreach($anaesthetic->anaesthetic_complications as $complication)
			{
			$complications[] = $complication['name'];
			}
			$record['anaesthetic_complications']=implode(',',$complications);
		}

		}

		if (@$_GET['cataract_report']) {
			foreach (array('cataract_report', 'cataract_predicted_refraction', 'cataract_iol_type', 'cataract_iol_power') as $k) {
				$record[$k] = '';
			}
			if ($cataract_element = Element_OphTrOperationnote_Cataract::model()->find('event_id = :event_id',array(':event_id'=>$event_id))) {
				$record['cataract_report']=	trim(preg_replace('/\s\s+/', ' ', $cataract_element['report']));
				$record['cataract_predicted_refraction'] = $cataract_element->predicted_refraction;
				if ($cataract_element->iol_type) {
					$record['cataract_iol_type'] = $cataract_element->iol_type->name;
				}
				else {
					$record['cataract_iol_type'] = 'None';
				}
				$record['cataract_iol_power'] = $cataract_element->iol_power;
			}
		}

		if (@$_GET['tamponade_used']) {
			if ($tamponade_element = Element_OphTrOperationnote_Tamponade::model()->find('event_id = :event_id', array(':event_id'=>$event_id))) {
				$record['tamponade_used'] = $tamponade_element->gas_type->name;
			}
			else {
				$record['tamponade_used'] = 'None';
			}
		}

		if (@$_GET['surgeon'] || @$_GET['surgeon_role'] || @$_GET['assistant'] || @$_GET['assistant_role'] || @$_GET['supervising_surgeon'] || @$_GET['supervising_surgeon_role']) {
			$surgeon_element = Element_OphTrOperationnote_Surgeon::model()->findByAttributes(array('event_id' => $event_id));

			foreach (array('surgeon', 'assistant', 'supervising_surgeon') as $surgeon_type) {
				if (@$_GET[$surgeon_type] || @$_GET["{$surgeon_type}_role"]) {
					$surgeon = $surgeon_element->{$surgeon_type};
					if (@$_GET[$surgeon_type]) $record[$surgeon_type] = $surgeon ? $surgeon->getFullName() : 'None';
					if (@$_GET["{$surgeon_type}_role"]) $record["{$surgeon_type}_role"] = $surgeon ? $surgeon->role : 'None';
				}
			}
		}

		if (@$_GET['opnote_comments']) {
			$comments=Element_OphTrOperationnote_Comments::model()->find('event_id = :event_id',array(':event_id'=>$event_id));
			$record['opnote_comments']=	trim(preg_replace('/\s\s+/', ' ', $comments['comments']));
		}
	}


	/**
	 * Generates a cataract outcomes report
	 *
	 * inputs (all optional):
	 * - firm_id
	 * - surgeon_id
	 * - assistant_id
	 * - supervising_surgeon_id
	 * - date_from
	 * - date_to
	 *
	 * outputs:
	 * - number of cataracts (number)
	 * - age of patients (mean and range)
	 * - eyes (numbers and percentage for left/right)
	 * - final visual acuity (mean and range)
	 * - pc ruptures (number and percentage)
	 * - complications (number and percentage)
	 *
	 * @param array $params
	 * @return array
	 */
	public	function reportCataractOperations(
		$params
	) {
		$data = array();

		$where = '';

		@$params['firm_id'] and $where .= " and f.id = {$params['firm_id']}";

		$surgeon_where = '';
		foreach (array('surgeon_id', 'assistant_id', 'supervising_surgeon_id') as $field) {
			if (@$params[$field]) {
				if ($surgeon_where) {
					$surgeon_where .= ' or ';
				}
				$surgeon_where .= "s.$field = {$params[$field]}";
			}
		}

		$surgeon_where and $where .= " and ($surgeon_where)";

		if (preg_match('/^[0-9]+[\s\-][a-zA-Z]{3}[\s\-][0-9]{4}$/', @$params['date_from'])) {
			$params['date_from'] = Helper::convertNHS2MySQL($params['date_from']);
		}
		if (preg_match('/^[0-9]+[\s\-][a-zA-Z]{3}[\s\-][0-9]{4}$/', @$params['date_to'])) {
			$params['date_to'] = Helper::convertNHS2MySQL($params['date_to']);
		}
		@$params['date_from'] and $where .= " and e.created_date >= '{$params['date_from']}'";
		@$params['date_to'] and $where .= " and e.created_date <= '{$params['date_to']}'";

		$data['cataracts'] = 0;
		$data['eyes'] = array(
			'left' => array(
				'number' => 0,
			),
			'right' => array(
				'number' => 0,
			),
		);
		$data['age']['from'] = 200; // wonder if this will ever need to be changed..
		$data['age']['to'] = 0;
		$data['final_visual_acuity'] = array(
			'from' => 0,
			'to' => 0,
			'mean' => 0,
		);
		$data['pc_ruptures']['number'] = 0;
		$data['complications']['number'] = 0;

		$ages = array();

		if (!($db = Yii::app()->params['report_db'])) {
			$db = 'db';
		}

		foreach (Yii::app()->$db->createCommand()
			->select("pl.eye_id, p.dob, p.date_of_death, comp.id as comp_id, pc.id as pc_id")
			->from("et_ophtroperationnote_procedurelist pl")
			->join("et_ophtroperationnote_cataract c","pl.event_id = c.event_id")
			->join("event e","c.event_id = e.id")
			->join("et_ophtroperationnote_surgeon s","s.event_id = e.id")
			->join("episode ep","e.episode_id = ep.id")
			->join("firm f","ep.firm_id = f.id")
			->join("patient p","ep.patient_id = p.id")
			->leftJoin("et_ophtroperationnote_cataract_complication comp","comp.cataract_id = c.id")
			->leftJoin("et_ophtroperationnote_cataract_complication pc","pc.cataract_id = c.id and pc.complication_id = 11")
			->where("pl.deleted = 0 and c.deleted = 0 and e.deleted = 0 and s.deleted = 0 and ep.deleted = 0 and f.deleted = 0 and p.deleted = 0 and (comp.id is null or comp.deleted = 0) and (pc.id is null or pc.deleted = 0) $where")
			->queryAll() as $row) {

			$data['cataracts']++;
			($row['eye_id'] == 1) ? $data['eyes']['left']['number']++ : $data['eyes']['right']['number']++;

			$age = Helper::getAge($row['dob'], $row['date_of_death']);
			$ages[] = $age; //this is taking ages

			if ($age < $data['age']['from']) {
				$data['age']['from'] = $age;
			}

			if ($age > $data['age']['to']) {
				$data['age']['to'] = $age;
			}

			$row['pc_id'] and $data['pc_ruptures']['number']++;
			$row['comp_id'] and $data['complications']['number']++;
		}

		if (count($ages) == 0) {
			$data['age']['from'] = 0;
		}

		$data['eyes']['left']['percentage'] = ($data['cataracts'] > 0) ? number_format(
			$data['eyes']['left']['number'] / ($data['cataracts'] / 100),
			2
		) : 0;
		$data['eyes']['right']['percentage'] = ($data['cataracts'] > 0) ? number_format(
			$data['eyes']['right']['number'] / ($data['cataracts'] / 100),
			2
		) : 0;
		$data['age']['mean'] = (count($ages) > 0) ? number_format(array_sum($ages) / count($ages), 2) : 0;
		$data['pc_ruptures']['percentage'] = ($data['cataracts'] > 0) ? number_format(
			$data['pc_ruptures']['number'] / ($data['cataracts'] / 100),
			2
		) : 0;
		$data['complications']['percentage'] = ($data['cataracts'] > 0) ? number_format(
			$data['complications']['number'] / ($data['cataracts'] / 100),
			2
		) : 0;
		$data['pc_rupture_average']['number'] = 0;
		$data['complication_average']['number'] = 0;

		if (!($db = Yii::app()->params['report_db'])) {
			$db = 'db';
		}

		foreach (Yii::app()->$db->createCommand()
			->select("pl.eye_id, p.dob, p.date_of_death, comp.id as comp_id, pc.id as pc_id")
			->from("et_ophtroperationnote_procedurelist pl")
			->join("et_ophtroperationnote_cataract c","pl.event_id = c.event_id")
			->join("event e","c.event_id = e.id")
			->join("et_ophtroperationnote_surgeon s","s.event_id = e.id")
			->join("episode ep","e.episode_id = ep.id")
			->join("firm f","ep.firm_id = f.id")
			->join("patient p","ep.patient_id = p.id")
			->leftJoin("et_ophtroperationnote_cataract_complication comp","comp.cataract_id = c.id")
			->leftJoin("et_ophtroperationnote_cataract_complication pc","pc.cataract_id = c.id and pc.complication_id = 11")
			->where("pl.deleted = 0 and c.deleted = 0 and e.deleted = 0 and s.deleted = 0 and ep.deleted = 0 and f.deleted = 0 and p.deleted = 0 and (comp.id is null or comp.deleted = 0) and (pc.id is null or pc.deleted = 0)")
			->queryAll() as $i => $row) {

			$row['pc_id'] and $data['pc_rupture_average']['number']++;
			$row['comp_id'] and $data['complication_average']['number']++;
		}

		$i++;

		$data['pc_rupture_average']['percentage'] = number_format(
			$data['pc_rupture_average']['number'] / ($i / 100),
			2
		);
		$data['complication_average']['percentage'] = number_format(
			$data['complication_average']['number'] / ($i / 100),
			2
		);

		return $data;
	}

	public function reportOperations($params=array())
	{
		$where = '';

		if (strtotime($params['date_from'])) {
			$where .= " and e.created_date >= '".date('Y-m-d',strtotime($params['date_from']))." 00:00:00'";
		}
		if (strtotime($params['date_to'])) {
			$where .= " and e.created_date <= '".date('Y-m-d',strtotime($params['date_to']))." 23:59:59'";
		}

		if ($user = User::model()->findByPk($params['surgeon_id'])) {
			$clause = '';
			if (@$params['match_surgeon']) {
				$clause .= "s.surgeon_id = $user->id";
			}
			if (@$params['match_assistant_surgeon']) {
				if ($clause) $clause .= ' or ';
				$clause .= "s.assistant_id = $user->id";
			}
			if (@$params['match_supervising_surgeon']) {
				if ($clause) $clause .= ' or ';
				$clause .= "s.supervising_surgeon_id = $user->id";
			}
			$where .= " and ($clause)";
		}

		if (!($db = Yii::app()->params['report_db'])) {
			$db = 'db';
		}

		foreach (Yii::app()->$db->createCommand()
			->select("p.hos_num, c.first_name, c.last_name, e.created_date, s.surgeon_id, s.assistant_id, s.supervising_surgeon_id, pl.id as pl_id, e.id as event_id, cat.id as cat_id, eye.name as eye")
			->from('patient p')
			->join('contact c',"c.parent_class = 'Patient' and c.parent_id = p.id")
			->join('episode ep','ep.patient_id = p.id')
			->join('event e','e.episode_id = ep.id')
			->join('et_ophtroperationnote_procedurelist pl','pl.event_id = e.id')
			->join('eye','pl.eye_id = eye.id')
			->join('et_ophtroperationnote_surgeon s','s.event_id = e.id')
			->leftJoin('et_ophtroperationnote_cataract cat','cat.event_id = e.id')
			->where("p.deleted = 0 and c.deleted = 0 and ep.deleted = 0 and e.deleted = 0 and pl.deleted = 0 and s.deleted = 0 and (cat.id is null or cat.deleted = 0) $where")
			->order('e.created_date asc')
			->queryAll() as $row) {

			$operations[] = array(
				'date' => date('j M Y',strtotime($row['created_date'])),
				'hos_num' => $row['hos_num'],
				'first_name' => $row['first_name'],
				'last_name' => $row['last_name'],
				'procedures' => array(),
				'complications' => array(),
				'role' => ($row['surgeon_id'] == $user->id ? 'Surgeon' : ($row['assistant_id'] == $user->id ? 'Assistant surgeon' : 'Supervising surgeon')),
			);

			foreach (OphTrOperationnote_ProcedureListProcedureAssignment::model()->findAll('procedurelist_id=?',array($row['pl_id'])) as $i => $pa) {
				$operations[count($operations)-1]['procedures'][] = array(
					'eye' => $row['eye'],
					'procedure' => $pa->procedure->term,
				);
			}

			if ($row['cat_id']) {
				foreach (OphTrOperationnote_CataractComplication::model()->findAll('cataract_id=?',array($row['cat_id'])) as $complication) {
					$operations[count($operations)-1]['complications'][] = array('complication'=>$complication->complication->name);
				}
			}
		}

		return array('operations'=>$operations);
	}
}
