<h1 class="badge">Reports</h1>
<div class="row">
	<div class="large-11 small-11 small-centered large-centered column">
		<div class="panel">
			<h2>Operation Report</h2>
			<form>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Surgeon', 'surgeon_id') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::dropDownList('surgeon_id', null, $surgeons, array('empty' => 'All surgeons')) ?>
					</div>
				</div>
				<?php
					$this->widget('application.widgets.ProcedureSelection',array(
									'newRecord' => true,
									'last' => true,
							));
				?>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Cataract Complications', 'cat_complications'); ?>
					</div>
					<div class="large-4 column end">
						<?php $this->widget('application.widgets.MultiSelectList', array(
								'field' => 'complications',
								'options' => CHtml::listData(OphTrOperationnote_CataractComplications::model()->findAll(), 'id', 'name'),
								'htmlOptions' => array('empty' => '- Complications -', 'multiple' => 'multiple', 'nowrapper' => true)
						)); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Date From', 'date_from') ?>
					</div>
					<div class="large-4 column end">
						<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
								'name'=>'date_from',
								'id'=>'date_from',
								'options'=>array(
									'showAnim'=>'fold',
									'dateFormat'=>Helper::NHS_DATE_FORMAT_JS,
									'maxDate'=> 0,
									'defaultDate' => "-1y"
								),
								'value'=>@$_GET['date_from']
							))?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Date To', 'date_to') ?>
					</div>
					<div class="large-4 column end">
						<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
								'name'=>'date_to',
								'id'=>'date_to',
								'options'=>array(
									'showAnim'=>'fold',
									'dateFormat'=>Helper::NHS_DATE_FORMAT_JS,
									'maxDate'=> 0,
									'defaultDate' => 0
								),
								'value'=>@$_GET['date_to']
							))?>
					</div>
				</div>
				<h3>Operation Booking</h3>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Comments', 'bookingcomments') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('booking'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Operation booking diagnosis', 'booking_diagnosis') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('booking_diagnosis'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Surgery Date', 'surgerydate') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('surgerydate'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Theatre', 'theatre') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('theatre'); ?>
					</div>
				</div>
				<h3>Examination</h3>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Comorbidities', 'comorbidities') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('comorbidities'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('First or Second Eye', 'first_eye') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('first_eye'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Refraction Values', 'refraction_values') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('refraction_values'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Target Refraction', 'target_refraction') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('target_refraction'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('VA Values', 'va_values') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('va_values'); ?>
					</div>
				</div>
				<h3>Operation Note</h3>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Cataract Report', 'cataract_report') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('cataract_report'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Tamponade Used', 'tamponade_used') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('tamponade_used'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Anaesthetic Type', 'anaesthetic_type') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('anaesthetic_type'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Anaesthetic Delivery', 'anaesthetic_delivery') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('anaesthetic_delivery'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Anaesthetic Complications', 'anaesthetic_complications') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('anaesthetic_complications'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Anaesthetic Comments', 'anaesthetic_comments') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('anaesthetic_comments'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Surgeon', 'surgeon') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('surgeon'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Surgeon role', 'surgeon_role') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('surgeon_role'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Assistant', 'assistant') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('assistant'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Assistant role', 'assistant_role') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('assistant_role'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Supervising surgeon', 'supervising_surgeon') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('supervising_surgeon'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Supervising surgeon role', 'supervising_surgeon_role') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('supervising_surgeon_role'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Operation Note Comments', 'opnote_comments') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('opnote_comments'); ?>
					</div>
				</div>
				<h3>Patient Data</h3>
				<div class="row field-row">
					<div class="large-2 column">
						<?php echo CHtml::label('Patient Ophthalmic Diagnoses', 'patient_oph_diagnoses') ?>
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::checkBox('patient_oph_diagnoses'); ?>
					</div>
				</div>
				<div class="row field-row">
					<div class="large-2 column">
						&nbsp;
					</div>
					<div class="large-4 column end">
						<?php echo CHtml::submitButton('Generate Report') ?>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
