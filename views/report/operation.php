<h1 class="badge">Reports</h1>
<div class="row">
	<div class="large-11 small-11 small-centered large-centered column">
		<div class="panel">
			<h2>Operation Report</h2>
			<form>
				<?php echo CHtml::label('Surgeon','surgeon') ?>
				<?php echo CHtml::dropDownList('surgeon', '', $surgeons) ?>
				<?php echo CHtml::label('Date From','date_from') ?>
				<?php echo CHtml::textField('date_from') ?>
				<?php echo CHtml::label('Date To','date_to') ?>
				<?php echo CHtml::textField('date_to') ?>
				<?php echo CHtml::submitButton('Generate Report') ?>
			</form>
		</div>
	</div>
</div>
