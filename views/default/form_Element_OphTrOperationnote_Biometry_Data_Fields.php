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
?>

<div class="element-data">
	<div class="row data-row">
		<input type="hidden" id="Element_OphTrOperationnote_Biometry_id_hidden" name="Element_OphTrOperationnote_Biometry[id]" value="<?php echo $element->id; ?>">
		<div class="large-4 column"><div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('lens_id_'.$side))?></div></div>
		<div class="large-8 column end"><div class="data-value" id="lens_<?php echo $side?>"><?php echo $element->{'lens_'.$side} ? $element->{'lens_'.$side} : 'None'?></div></div>
	</div>
	<div class="row field-row">
		<div class="large-4 column">
			<div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('lens_description_'.$side))?></div>
		</div>
		<div class="large-8 column">
			<div class="data-value" id="type_<?php echo $side?>"><?php echo $element->{'lens_description_'.$side} ? $element->{'lens_description_'.$side} : 'None'?></div>
		</div>
	</div>
	<div class="row field-row">
		<div class="large-4 column">
			<div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('lens_acon_'.$side))?></div>
		</div>
		<div class="large-8 column">
			<div class="data-value" id="acon_<?php echo $side?>"><?php echo $element->{'lens_acon_'.$side} ? $element->{'lens_acon_'.$side} : 'None'?></div>
		</div>
	</div>
	<div class="row field-row">
		<div class="large-4 column">
			<div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('k1_'.$side))?></div>
		</div>
		<div class="large-8 column">
			<div class="data-value" id="k1_<?php echo $side?>"><?php echo CHtml::encode($element->{'k1_'.$side}) ?></div>
		</div>
	</div>
	<div class="row field-row">
		<div class="large-4 column">
			<div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('k2_'.$side))?></div>
		</div>
		<div class="large-8 column">
			<div class="data-value" id="k2_<?php echo $side?>"><?php echo CHtml::encode($element->{'k2_'.$side}) ?></div>
		</div>
	</div>
	<div class="row field-row">
		<div class="large-4 column">
			<div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('axis_k1_'.$side))?></div>
		</div>
		<div class="large-8 column">
			<div class="data-value" id="axis_k1_<?php echo $side?>"><?php echo CHtml::encode($element->{'axis_k1_'.$side}) ?></div>
		</div>
	</div>
	<div class="row field-row">
		<div class="large-4 column">
			<div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('axial_length_'.$side))?></div>
		</div>
		<div class="large-8 column">
			<div class="data-value" id="axial_length_<?php echo $side?>"><?php echo CHtml::encode($element->{'axial_length_'.$side}) ?></div>
		</div>
	</div>
	<div class="row field-row">
		<div class="large-4 column">
			<div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('snr_'.$side))?></div>
		</div>
		<div class="large-8 column">
			<div class="data-value" id="snr_<?php echo $side?>"><?php echo CHtml::encode($element->{'snr_'.$side}) ?></div>
		</div>
	</div>
	<div class="row data-row">
		<div class="large-4 column"><div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('iol_power_'.$side))?></div></div>
		<div class="large-8 column end"><div class="iolDisplay"><?php echo CHtml::encode($element->{'iol_power_'.$side})?></div></div>
	</div>
	<div class="row data-row">
		<div class="large-4 column"><div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('predicted_refraction_'.$side))?></div></div>
		<div class="large-8 column end"><div class="data-value predictedRefraction" id="tr_<?php echo $side?>"><?php echo CHtml::encode($element->{'predicted_refraction_'.$side})?></div></div>
	</div>
	<div class="row field-row">
		<div class="large-4 column">
			<div class="data-label"><?php echo CHtml::encode($element->getAttributeLabel('target_refraction_'.$side))?></div>
		</div>
		<div class="large-8 column">
			<div class="data-value" id="snr_<?php echo $side?>"><?php echo CHtml::encode($element->{'target_refraction_'.$side}) ?></div>
		</div>
	</div>
</div>
