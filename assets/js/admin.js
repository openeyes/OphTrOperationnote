$(document).ready(function() {
	$('#et_add').click(function(e) {
		var last = $('div.sortable').children('li:last').attr('class');
		var row = (last == 'even' ? 'odd' : 'even');
		$('div.sortable').append('<li class="'+row+'" data-attr-id="NONE"> <span class="column_name"><input type="text" name="newdrug" class="newdrug" value="" /></span> <span class="column_deleted"><a class="deleteDrugItem" href="#" rel="NONE">delete</a></span> </li>');
		$('input.newdrug').focus();
		e.preventDefault();
	});

	$('input.newdrug').die('blur').live('blur',function(e) {
		if ($(this).val().length <1) {
			$(this).parent().parent().remove();
		} else {
			if (!no_update) {
				addNewDrug($(this),false);
			}
		}
	});

	$('input.newdrug').die('keypress').live('keypress',function(e) {
		if (e.keyCode == 13) {
			no_update = true;
			addNewDrug($(this),true);
			e.preventDefault();
		}
	});

	$('input.newdrug').die('keyup').live('keyup',function(e) {
		if (e.keyCode == 27) {
			$(this).parent().parent().remove();
		}
	});

	$('#et_delete').click(function() {
		$.ajax({
			'type': 'POST',
			'url': baseUrl+'/OphTrOperationnote/admin/deletePostOpDrug',
			'data': $('#drugs').serialize()+"&YII_CSRF_TOKEN="+YII_CSRF_TOKEN,
			'success': function(data) {
				$('input[name="drugs[]"]:checked').map(function() {
					$(this).parent().next('span').next('span').html('Yes');
				});
			}
		});
	});

	$('#et_undelete').click(function() {
		$.ajax({
			'type': 'POST',
			'url': baseUrl+'/OphTrOperationnote/admin/undeletePostOpDrug',
			'data': $('#drugs').serialize()+"&YII_CSRF_TOKEN="+YII_CSRF_TOKEN,
			'success': function(data) {
				$('input[name="drugs[]"]:checked').map(function() {
					$(this).parent().next('span').next('span').html('No');
				});
			}
		});
	});

	$('a.drugItem').die('click').live('click',function(e) {
		no_update = false;
		var p = $(this).parent();
		p.html('<input type="text" name="editdrug" class="editdrug" value="'+$(this).html()+'" />');
		postopdrugs_editing_text = $(this).html();
		postopdrugs_editing_value = $(this).attr('rel');
		p.children('input').focus().val(p.children('input').val());
		e.preventDefault();
	});

	$('input.editdrug').die('blur').live('blur',function(e) {
		if (!no_update) {
			updateDrug($(this));
		}
	});

	$('input.editdrug').die('keypress').live('keypress',function(e) {
		if (e.keyCode == 13) {
			updateDrug($(this));
			e.preventDefault();
		}
	});

	$('input.editdrug').die('keyup').live('keyup',function(e) {
		if (e.keyCode == 27) {
			no_update = true;
			var p = $(this).parent();
			p.html('<a class="drugItem" href="#" rel="'+postopdrugs_editing_value+'">'+postopdrugs_editing_text+'</a>');
		}
	});

	$('.sortable').sortable({
		stop: function() {
			var ids = [];
			$('div.sortable').children('li').map(function() {
				ids.push($(this).attr('data-attr-id'));
			});
			$.ajax({
				'type': 'POST',
				'url': baseUrl+'/OphTrOperationnote/admin/sortPostOpDrugs',
				'data': {order: ids, 'YII_CSRF_TOKEN': YII_CSRF_TOKEN},
				'success': function(data) {
				}
			});
		}
	});

	$('a.deleteDrugItem').die('click').live('click',function(e) {
		var obj = $(this);

		$.ajax({
			'type': 'POST',
			'url': baseUrl+'/OphTrOperationnote/admin/deletePostOpDrug/'+obj.attr('rel'),
			'data': 'YII_CSRF_TOKEN='+YII_CSRF_TOKEN,
			'success': function(html) {
				if (html != "1") {
					new OpenEyes.Dialog.Alert({
						content: "Unable to delete drug, please contact support."
					}).open();
				} else {
					obj.parent().parent().remove();
				}
			}
		});

		e.preventDefault();
	});
});

function updateDrug(obj) {
	if (obj.val().length <1) {
		var p = obj.parent();
		p.html('<a class="drugItem" href="#" rel="'+postopdrugs_editing_value+'">'+postopdrugs_editing_text+'</a>');
	} else {
		var p = obj.parent();
		var value = obj.val();

		if (value != postopdrugs_editing_text) {
			$.ajax({
				'type': 'POST',
				'url': baseUrl+'/OphTrOperationnote/admin/updatePostOpDrug',
				'data': {"id": postopdrugs_editing_value, "name": value, "YII_CSRF_TOKEN": YII_CSRF_TOKEN},
				'dataType': 'json',
				'success': function(data) {

					var errors = data['errors'];
					var error;
					(function alertMessage() {
						setTimeout(function() {
							if (error = data['errors'].shift()) {
								new OpenEyes.Dialog.Alert({
									content: error,
									onClose: alertMessage
								}).open();
							}
						});
					}());

					if (data['errors'].length == 0) {
						p.html('<a class="drugItem" href="#" rel="'+postopdrugs_editing_value+'">'+value+'</a>');
					} else {
						obj.select().focus();
					}
				}
			});
		} else {
			p.html('<a class="drugItem" href="#" rel="'+postopdrugs_editing_value+'">'+value+'</a>');
		}
	}
}

function addNewDrug(obj, another) {
	$.ajax({
		'type': 'POST',
		'url': baseUrl+'/OphTrOperationnote/admin/createPostOpDrug',
		'data': {"name": obj.val(), 'YII_CSRF_TOKEN': YII_CSRF_TOKEN},
		'dataType': 'json',
		'success': function(data) {
			var errors = data['errors'];
			var error;
			(function alertMessage() {
				setTimeout(function() {
					if (error = data['errors'].shift()) {
						new OpenEyes.Dialog.Alert({
							content: error,
							onClose: alertMessage
						}).open();
					} else {
						obj.select().focus();
					}
				});
			}());
			if (data['errors'].length == 0) {
				var li = obj.parent().parent();
				obj.parent().html('<a class="drugItem" href="#" rel="'+data['id']+'">'+obj.val()+'</a>');
				obj.parent().next('span').children('a').attr('rel',obj.val());
				li.attr('data-attr-id',data['id']);
				if (another) {
					$('#et_add').click();
				}
			}
		}
	});
}

var postopdrugs_editing_text = '';
var postopdrugs_editing_value = '';
var no_update = false;
