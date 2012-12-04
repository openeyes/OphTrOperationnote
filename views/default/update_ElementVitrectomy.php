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
?>
<div class="element <?php echo $element->elementType->class_name?> ondemand<?php if (!$element->event_id) {?> missing<?php }?>"
	data-element-type-id="<?php echo $element->elementType->id ?>"
	data-element-type-class="<?php echo $element->elementType->class_name ?>"
	data-element-type-name="<?php echo $element->elementType->name ?>"
	data-element-display-order="<?php echo $element->elementType->display_order ?>">
	<?php if (!$element->event_id) {?>
		<span class="missingtext">This element is missing and needs to be completed</span>
	<?php }?>
	<h4 class="elementTypeName"><?php echo $element->elementType->name ?></h4>

	<div class="splitElement clearfix" style="background-color: #DAE6F1;">
		<?php
		$this->widget('application.modules.eyedraw.OEEyeDrawWidgetVitrectomy', array(
			'side'=>$element->getSelectedEye()->shortname,
			'mode'=>'edit',
			'size'=>300,
			'model'=>$element,
			'attribute'=>'eyedraw',
			'offset_x' => 10,
			'offset_y' => 10,
		));
		?>
		<?php //echo $form->hiddenInput($element, 'report', $element->report)?>
		<div class="halfHeight">
			<?php echo $form->dropDownList($element, 'gauge_id', CHtml::listData(VitrectomyGauge::model()->findAll(),'id','value'),array('empty'=>'- Please select -'))?>
			<?php echo $form->radioBoolean($element, 'pvd_induced')?>
			<?php echo $form->textArea($element, 'comments', array('rows' => 4, 'cols' => 55))?>
		</div>
	</div>
</div>
