<div class="report curvybox white">
	<div class="reportInputs">
		<h3 class="georgia">Post-operative drugs</h3>
		<div>
			<form id="drugs">
				<ul class="grid reduceheight">
					<li class="header">
						<span class="column_checkbox"><input type="checkbox" name="selectall" id="selectall" /></span>
						<span class="column_name">Name</span>
						<span class="column_deleted">Deleted</span>
					</li>
					<div class="sortable">
						<?php foreach (PostopDrug::model()->findAll(array('order'=>'display_order')) as $i => $drug) {?>
							<li class="<?php if ($i%2 == 0) {?>even<?php }else{?>odd<?php }?>" data-attr-id="<?php echo $drug->id?>">
								<span class="column_checkbox"><input type="checkbox" name="drugs[]" value="<?php echo $drug->id?>" /></span>
								<span class="column_name"><a class="drugItem" href="#" rel="<?php echo $drug->id?>"><?php echo $drug->name?></a></span>
								<span class="column_deleted"><?php echo $drug->deleted ? 'Yes' : 'No'?></span>
							</li>
						<?php }?>
					</div>
				</ul>
			</form>
		</div>
	</div>
</div>
<div>
	<?php echo EventAction::button('Add', 'add', array('colour' => 'blue'))->toHtml()?>
	<?php echo EventAction::button('Delete', 'delete', array('colour' => 'blue'))->toHtml()?>
	<?php echo EventAction::button('Undelete', 'undelete', array('colour' => 'blue'))->toHtml()?>
</div>
