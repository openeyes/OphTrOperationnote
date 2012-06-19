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
<div class="page">
	<div class="header">
		<div class="title middle">
			<img src="/img/_print/letterhead_seal.jpg" alt="letterhead_seal" class="seal" width="100" height="83"/>
			<h1>Operation Note</h1>
		</div>
		<div class="headerInfo">
			<div class="patientDetails">
				<strong><?php echo $this->patient->addressname?></strong>
				<br />
				<?php echo $this->patient->address->getLetterHtml()?>
				<br>
				<br>
				Hospital No: <strong><?php echo $this->patient->hos_num ?></strong>
				<br>
				NHS No: <strong><?php echo $this->patient->nhs_num ?></strong>
				<br>
				DOB: <strong><? echo Helper::convertDate2NHS($this->patient->dob) ?> (<?php echo $this->patient->getAge()?>)</strong>
			</div>
			<div class="headerDetails">
				<strong><?php echo $this->event->episode->firm->getConsultant()->contact->getFullName() ?></strong>
				<br>
				Service: <strong><?php echo $this->event->episode->firm->getSubspecialtyText() ?></strong>
			</div>
			<div class="noteDates">
				Note Created: <strong><?php echo Helper::convertDate2NHS($this->event->created_date) ?></strong>
				<br>
				Note Printed: <strong><?php echo Helper::convertDate2NHS(date('Y-m-d')) ?></strong>
			</div>
		</div>
	</div>
	
	<div class="body">
		<div class="operationMeta">
			<div class="detailRow leftAlign">
				<div class="label">
					Operation(s) Performed:
				</div>
				<div class="value pronounced">
					<?php
						$operations_perf = ElementProcedureList::model()->find("event_id = ?", array($this->event->id));
						foreach($operations_perf->procedures as $procedure){
							echo "<strong>{$operations_perf->eye->name} {$procedure->term}</strong><br>";
						}
					?>
				</div>
			</div>
			<div class="surgeonList">
				<?php 
					$surgeon_element = ElementSurgeon::model()->find("event_id = ?", array($this->event->id));
					$surgeon_name = ($surgeon = Contact::model()->findByPk($surgeon_element->surgeon_id)) ? $surgeon->getFullName() : "Unknown";
					$assistant_name = ($assistant = Contact::model()->findByPk($surgeon_element->assistant_id)) ? $assistant->getFullName() : "Unknown";
					$supervising_surg_name = ($supervising_surg = Contact::model()->findByPk($surgeon_element->supervising_surgeon_id)) ? $supervising_surg->getFullName() : "Unknown";
				?>
				<div>
					First Surgeon
					<br>
					<strong><?php echo $surgeon_name ?></strong>
				</div>
				<div>
					Assistant Surgeon
					<br>
					<strong><?php echo $assistant_name ?></strong>
				</div>
				<div>
					Supervising surgeon
					<br>
					<strong><?php echo $supervising_surg_name ?></strong>
				</div>
			</div>
		</div>
		
		<h2>Operation Details</h2>
		<div class="operationDetails details">
			<?php $this->renderPartial('print_OperationDetails') ?>
		</div>
		
		<h2>Anaesthetic Details</h2>
		<?php
			$anaesthetic_element = ElementAnaesthetic::model()->find("event_id = ?", array($this->event->id));
		?>
		<div class="anaestheticDetails details">
			<div class="detailRow inline">
				<div class="label">
					Anaesthetic Type:
				</div>
				<div class="value">
					<?php echo ($anaesthetic_type = $anaesthetic_element->anaesthetic_type->name) ? $anaesthetic_type : 'Unknown' ?>
				</div>
			</div>
			<div class="detailRow inline">
				<div class="label">
					Given By:
				</div>
				<div class="value">
					<?php echo ($anaesthetist = $anaesthetic_element->anaesthetist->name) ? $anaesthetist : 'Unknown' ?>
				</div>
			</div>
			<div class="detailRow inline">
				<div class="label">
					Route Administered:
				</div>
				<div class="value">
					<?php echo ($delivery = $anaesthetic_element->anaesthetic_delivery->name) ? $delivery : 'Unknown' ?>
				</div>
			</div>
			<div class="detailRow inline">
				<div class="label">
					Anaesthetic Agents Used:
				</div>
				<div class="value">
					<?php
						foreach ($anaesthetic_element->anaesthetic_agents as $agent){
							echo "{$agent->name}<br>\n";
						}
					?>
				</div>
			</div>
			<div class="detailRow inline">
				<div class="label">
					Complications:
				</div>
				<div class="value">
					<?php
						foreach ($anaesthetic_element->anaesthetic_complications as $complication){
							echo "{$complication->name}<br>\n";
						}
					?>
				</div>
			</div>
			<div class="detailRow clearVal">
				<div class="label">
					Comments
				</div>
				<div class="value">
					<?php echo $anaesthetic_element->anaesthetic_comment ?>
				</div>
			</div>
		</div>

		<div class="detailRow leftAlign">
			<?php
				$postdrugs_element = ElementPostOpDrugs::model()->find("event_id = ?", array($this->event->id));
			?>
			<div class="label">
				Per-op Drugs:
			</div>
			<div class="value">
				<?php foreach ($postdrugs_element->drugs as $drug) {?>
					<?php echo $drug->name ?>
				<?php }?>
			</div>
		</div>
		<div class="detailRow clearVal">
			<?php
				$comments_element = ElementComments::model()->find("event_id = ?", array($this->event->id));
			?>
			<div class="label">
				Post-op Instructions
			</div>
			<div class="value">
				<?php echo $comments_element->postop_instructions ?>
			</div>
		</div>
		<div class="detailRow clearVal">
			<div class="label">
				Comments
			</div>
			<div class="value">
				<?php echo $comments_element->comments ?>
			</div>
		</div>
		<div class="footer">
			Created by <strong><?php echo ($created_user = User::model()->findByPk($this->event->created_user_id)) ? $created_user->getFullName() .' '.$created_user->qualifications : 'Unknown' ?></strong>
		</div>
	</div>
</div>