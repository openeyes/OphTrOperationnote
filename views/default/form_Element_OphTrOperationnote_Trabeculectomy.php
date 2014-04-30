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

?>
<div class="element-fields">
	<div class="row trabeculectomy">
		<div class="column large-4">
			<?php
				$this->widget('OphTrOperationnote.widgets.OEEyeDrawWidgetTrabeculectomy',
					array(
						'model' => $element,
						'attribute' => 'eyedraw',
						'side' => $this->selectedEyeForEyedraw->shortName,
					)
				);
			?>
		</div>
		<div class="column large-4">
	        <div class="row">
			<?= $form->dropDownList($element, 'conjunctival_flap_type_id', 'Ophtroperationnote_Trabeculectomy_Conjunctival_Flap_Type', array(), false, array('label' => 6, 'field' => 6))) ?>
			</div>
		</div>
		<div class="column large-4">
	        <div class="row">
			<?= $form->textArea($element, 'report', array(), false, array(), array('label' => 12, 'field' => 12)) ?>
			</div>
		</div>
	</div>
</div>
