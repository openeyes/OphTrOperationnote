<?php		$this->breadcrumbs=array($this->module->id);
	$this->header();
	$assetpath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.OphTrOperationbooking.assets')).'/';
?>
<h3 class="withEventIcon" style="background:transparent url(<?php echo $this->assetPath?>/img/medium.png) center left no-repeat;"><?php echo $this->event_type->name ?></h3>

<div>
	<?php			$form = $this->beginWidget('BaseEventTypeCActiveForm', array(
			'id'=>'clinical-create',
			'enableAjaxValidation'=>false,
			'htmlOptions' => array('class'=>'sliding'),
			// 'focus'=>'#procedure_id'
		));
	?>
	<?php  $this->displayErrors($errors)?>

	<h4>Create Operation note</h4>
	<h3 class="sectiondivider">
		<?php if (count($bookings) >0) {?>
			Please indicate whether this operation note relates to a booking or an unbooked emergency:
		<?php }else{?>
			There are no open bookings in the current episode so only an emergency operation note can be created.
		<?php }?>
	</h3>

	<div class="edetail">
		<div class="label">Select:</div>
		<div class="data">
			<table class="grid nodivider valignmiddle">
				<tbody>
					<?php foreach ($bookings as $booking) {?>
						<tr class="odd clickable">
							<td><input type="radio" value="booking<?php echo $booking->operation->event_id?>" name="SelectBooking" /></td>
							<td><img src="<?php echo Yii::app()->createUrl($assetpath.'img/small.png')?>" alt="op" width="19" height="19" /></td>
							<td><?php echo $booking->operation->event->NHSDate('datetime')?></td>
							<td>Operation</td>
							<td>
								<?php foreach ($booking->operation->procedures as $i => $procedure) {
									if ($i >0) { echo "<br/>"; }
									echo $procedure->term;
								}?>
							</td>
						</tr>
					<?php }?>
					<tr class="odd clickable">
						<td><input type="radio" value="emergency" name="SelectBooking" <?php if (count($bookings)==0) {?>checked="checked" <?php }?>/></td>
						<td></td>
						<td colspan="3">Emergency</td>
					</tr>
				</tbody>
			</table>
			<div class="btngroup padtop">
				<button type="submit" class="classy green mini" id="et_save" name="save"><span class="btn green">Create Operation note</span></button>
				<button type="submit" class="classy red mini" id="et_cancel" name="cancel"><span class="button-span button-span-red">Cancel</span></button>
				&nbsp;
				<img class="loader" style="display: none;" src="/img/ajax-loader.gif" alt="loading..." />
			</div>
		</div>
	</div>

	<?php  $this->displayErrors($errors)?>
	<?php  $this->endWidget(); ?></div>

<?php  $this->footer(); ?>
