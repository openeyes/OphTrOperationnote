<?php 	$this->breadcrumbs=array($this->module->id);
	$this->header();
?>
<h3 class="withEventIcon" style="background:transparent url(<?php echo $this->assetPath?>/img/medium.png) center left no-repeat;"><?php echo $this->event_type->name ?></h3>

<div>
	<?php 		$form = $this->beginWidget('BaseEventTypeCActiveForm', array(
			'id'=>'clinical-create',
			'enableAjaxValidation'=>false,
			'htmlOptions' => array('class'=>'sliding'),
			// 'focus'=>'#procedure_id'
		));
	?>
	<?php  $this->displayErrors($errors)?>

	<?php if (count($bookings) <1) {?>
		<p>
			Sorry, there are no open booked operations in the current episode so you cannot create an Operation note.
		</p>
	<?php }else{?>
		<p>
			Please select the booked operation that this opnote is for:
		</p>

		<table class="select_procedures">
			<tr>
				<th class="select"></th>
				<th class="date">Date</th>
				<th class="procedures">Procedures</th>
			</tr>
			<?php foreach ($bookings as $booking) {?>
				<tr>
					<td>
						<input type="radio" name="SelectBooking" value="booking<?php echo $booking->operation->event_id?>" />
					</td>
					<td>
						<?php echo date('j M Y',strtotime($booking->session->date))?>
					</td>
					<td>
						<?php foreach ($booking->operation->procedures as $procedure) {?>
							<?php echo $procedure->term?><br/>
						<?php }?>
					</td>
				</tr>
			<?php }?>
		</table>
	<?php }?>

	<?php  $this->displayErrors($errors)?>
		<div class="cleartall"></div>
		<div class="form_button">
			<img class="loader" style="display: none;" src="/img/ajax-loader.gif" alt="loading..." />&nbsp;
			<?php if (count($bookings) >0) {?>
				<button type="submit" class="classy green venti" id="et_save" name="save"><span class="button-span button-span-green">Create operation note</span></button>
			<?php }?>
			<button type="submit" class="classy red venti" id="et_cancel" name="cancel"><span class="button-span button-span-red">Cancel</span></button>
		</div>
	<?php  $this->endWidget(); ?></div>

<?php  $this->footer(); ?>
