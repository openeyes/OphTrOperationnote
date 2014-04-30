<?php
/**
 * (C) OpenEyes Foundation, 2014
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (C) 2014, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class Element_OphTrOperationnote_Trabeculectomy extends Element_OnDemand
{
	public function tableName()
	{
		return 'et_ophtroperationnote_trabeculectomy';
	}

	public function rules()
	{
		return array(
			'eyedraw, conjunctival_flap_type_id, stay_suture, site_id, size_id, sclerostomy_type_id, 27_guage_needle, ac_maintainer, viscoelastic_type_id, viscoelastic_removed, viscoelastic_flow_id, report, difficulty_other, complication_other', 'safe'
		);
	}

	public function relations()
	{
		return array(
			'conjunctival_flap_type' => array(self::BELONGS_TO, 'Ophtroperationnote_Trabeculectomy_Conjunctival_Flap_Type', 'conjunctival_flap_type_id'),
			'site' => array(self::BELONGS_TO, 'Ophtroperationnote_Trabeculectomy_Site', 'site_id'),
			'size' => array(self::BELONGS_TO, 'Ophtroperationnote_Trabeculectomy_Site', 'size_id'),
			'sclerostomy_type' => array(self::BELONGS_TO, 'Ophtroperationnote_Trabeculectomy_Sclerostomy_Type', 'sclerostomy_type_id'),
			'viscoelastic_type' => array(self::BELONGS_TO, 'Ophtroperationnote_Trabeculectomy_Viscoelastic_Type', 'viscoelastic_type_id'),
			'viscoelastic_flow' => array(self::BELONGS_TO, 'Ophtroperationnote_Trabeculectomy_Viscoelastic_Flow', 'viscoelastic_flow_id'),
			'difficulties' => array(self::MANY_MANY, 'Ophtroperationnote_Trabeculectomy_Difficulty', 'ophtroperationnote_trabeculectomy_difficulties (element_id, difficulty_id)'),
			'complications' => array(self::MANY_MANY, 'Ophtroperationnote_Trabeculectomy_Complication', 'ophtroperationnote_trabeculectomy_complications (element_id, complication_id)'),
		);
	}
}

