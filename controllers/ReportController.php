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
class ReportController extends BaseController
{

	public function accessRules()
	{
		return array(
			array('deny'),
		);
	}

	public function actionIndex()
	{
		$this->render('index');
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
			$results = $this->getOperations($surgeon, null, null, $date_from, $date_to);

			$filename = 'operation_report_'.date('YmdHis');
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$filename");
			header("Pragma: no-cache");
			header("Expires: 0");

			echo "Operation report for ";
			if($surgeon) {
				echo "$surgeon->first_name $surgeon->last_name";
			} else {
				echo "all surgeons";
			}
			echo ", from $date_from to $date_to\n";

			$header = "operation_date,patient_hosnum,patient_firstname,patient_surname,patient_dob,eye,procedures,complications";
			if ($surgeon) {
				$header .= ',surgeon_role';
			}
			echo "$header\n";

			foreach($results as $result) {
				echo '"' . implode('","', $result) . "\"\n";
			}
		} else {
			$context['surgeons'] = CHtml::listData(User::model()->findAll('is_doctor = 1'), 'id', 'fullname');
			$this->render('operation', $context);
		}
	}

	public function actionCataract()
	{
		$this->render('cataract');
	}

	public function actionDiagnosis()
	{
	}

	/**
	 * @param User $surgeon
	 * @param array $filter_procedures
	 * @param array $filter_complications
	 * @param $from_date
	 * @param $to_date
	 * @return array
	 */
	protected function getOperations($surgeon = null, $filter_procedures = array(), $filter_complications = array(), $from_date, $to_date)
	{
		$filter_procedures_method = 'OR';
		$filter_complications_method = 'OR';

		$command = Yii::app()->db->createCommand()
			->select(
				"e.id, c.first_name, c.last_name, e.created_date, su.surgeon_id, su.assistant_id, su.supervising_surgeon_id, p.hos_num, p.dob, pl.id as plid, cat.id as cat_id, eye.name AS eye"
			)
			->from("event e")
			->join("episode ep", "e.episode_id = ep.id")
			->join("patient p", "ep.patient_id = p.id")
			->join("et_ophtroperationnote_procedurelist pl", "pl.event_id = e.id")
			->join("et_ophtroperationnote_surgeon su", "su.event_id = e.id")
			->join("contact c", "p.contact_id = c.id")
			->join("eye", "eye.id = pl.eye_id")
			->leftJoin("et_ophtroperationnote_cataract cat", "cat.event_id = e.id")
			->where("e.deleted = 0 and ep.deleted = 0 and e.created_date >= :from_date and e.created_date < :to_date + interval 1 day");
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

			$complications = array();
			if ($row['cat_id']) {
				foreach (OphTrOperationnote_CataractComplication::model()->findAll('cataract_id = ?', array($row['cat_id'])) as $complication) {
					if(!isset($cache['complications'][$complication->complication_id])) {
						$cache['complications'][$complication->complication_id] = $complication->complication->name;
					}
					$complications[$cache['complications'][$complication->complication_id]] = $cache['complications'][$complication->complication_id];
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
				$procedures[$cache['procedures'][$pa->proc_id]] = $cache['procedures'][$pa->proc_id];
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
				date('j M Y', strtotime($row['created_date'])),
				$row['hos_num'],
				$row['first_name'],
				$row['last_name'],
				date('j M Y', strtotime($row['dob'])),
				$row['eye'],
				implode(', ', $procedures),
				implode(', ', $complications),
			);

			if ($surgeon) {
				if ($row['surgeon_id'] == $surgeon->id) {
					$record[] = 'Surgeon';
				} else {
					if ($row['assistant_id'] == $surgeon->id) {
						$record[] = 'Assistant surgeon';
					} else {
						if ($row['supervising_surgeon_id'] == $surgeon->id) {
							$record[] = 'Supervising surgeon';
						}
					}
				}
			}

			$results[] = $record;
		}
		return $results;
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
	public
	function reportCataractOperations(
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
					 ->join("et_ophtroperationnote_cataract c", "pl.event_id = c.event_id")
					 ->join("event e", "c.event_id = e.id")
					 ->join("et_ophtroperationnote_surgeon s", "s.event_id = e.id")
					 ->join("episode ep", "e.episode_id = ep.id")
					 ->join("firm f", "ep.firm_id = f.id")
					 ->join("patient p", "ep.patient_id = p.id")
					 ->leftJoin("et_ophtroperationnote_cataract_complication comp", "comp.cataract_id = c.id")
					 ->leftJoin(
						 "et_ophtroperationnote_cataract_complication pc",
						 "pc.cataract_id = c.id and pc.complication_id = 11"
					 )
					 ->where("e.deleted = 0 and ep.deleted = 0 $where")
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
					 ->join("et_ophtroperationnote_cataract c", "pl.event_id = c.event_id")
					 ->join("event e", "c.event_id = e.id")
					 ->join("et_ophtroperationnote_surgeon s", "s.event_id = e.id")
					 ->join("episode ep", "e.episode_id = ep.id")
					 ->join("firm f", "ep.firm_id = f.id")
					 ->join("patient p", "ep.patient_id = p.id")
					 ->leftJoin("et_ophtroperationnote_cataract_complication comp", "comp.cataract_id = c.id")
					 ->leftJoin(
						 "et_ophtroperationnote_cataract_complication pc",
						 "pc.cataract_id = c.id and pc.complication_id = 11"
					 )
					 ->where("e.deleted = 0 and ep.deleted = 0")
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

}
