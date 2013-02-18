
function callbackAddProcedure(procedure_id) {
	var eye = $('input[name="ElementProcedureList\[eye_id\]"]:checked').val();

	$.ajax({
		'type': 'GET',
		'url': baseUrl+'/OphTrOperationnote/Default/loadElementByProcedure?procedure_id='+procedure_id+'&eye='+eye,
		'success': function(html) {
			if (html.length >0) {
				if (html.match(/must-select-eye/)) {
					$('div.procedureItem').map(function(e) {
						var r = new RegExp('<input type="hidden" value="'+procedure_id+'" name="Procedures');
						if ($(this).html().match(r)) {
							$(this).remove();
						}
					});
					if ($('div.procedureItem').length == 0) {
						$('#procedureList').hide();
					}
					alert("You must select either the right or the left eye to add this procedure.");
				} else {
					var m = html.match(/data-element-type-class="(Element.*?)"/);
					if (m) {
						m[1] = m[1].replace(/ .*$/,'');

						if ($('div.'+m[1]).length <1) {
							$('div.ElementAnaesthetic').before(html);
							$('div.'+m[1]).attr('style','display: none;');
							$('div.'+m[1]).removeClass('hidden');
							$('div.'+m[1]).slideToggle('fast');
						}
					}
				}
			}
		}
	});
}

/*
 * Post the removed operation_id and an array of ElementType class names currently in the DOM
 * This should return any ElementType classes that we should remove.
 */

function callbackRemoveProcedure(procedure_id) {
	var procedures = '';

	$('input[name="Procedures[]"]').map(function() {
		if (procedures.length >0) {
			procedures += ',';
		}
		procedures += $(this).val();
	});

	$.ajax({
		'type': 'POST',
		'url': baseUrl+'/OphTrOperationnote/Default/getElementsToDelete',
		'data': "remaining_procedures="+procedures+"&procedure_id="+procedure_id,
		'dataType': 'json',
		'success': function(data) {
			$.each(data, function(key, val) {
				$('div.'+val).slideToggle('fast',function() {
					$('div.'+val).remove();
				});
			});
		}
	});
}

function setCataractSelectInput(key, value) {
	$('#ElementCataract_'+key+'_id').children('option').map(function() {
		if ($(this).text() == value) {
			$('#ElementCataract_'+key+'_id').val($(this).val());
		}
	});
}

function setCataractInput(key, value) {
	$('#ElementCataract_'+key).val(value);
}

$(document).ready(function() {
	$('#et_save').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();

			if ($('#ElementBuckle_report').length >0) {
				$('#ElementBuckle_report').val(ed_drawing_edit_Buckle.report());
			}
			if ($('#ElementCataract_report2').length >0) {
				$('#ElementCataract_report2').val(ed_drawing_edit_Cataract.report());
			}

			return true;
		}
		return false;
	});

	$('#et_cancel').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();

			if (m = window.location.href.match(/\/update\/[0-9]+/)) {
				window.location.href = window.location.href.replace('/update/','/view/');
			} else {
				window.location.href = baseUrl+'/patient/episodes/'+et_patient_id;
			}
		}
		return false;
	});

	$('#et_deleteevent').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();
			return true;
		}
		return false;
	});

	$('#et_canceldelete').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();

			if (m = window.location.href.match(/\/delete\/[0-9]+/)) {
				window.location.href = window.location.href.replace('/delete/','/view/');
			} else {
				window.location.href = baseUrl+'/patient/episodes/'+et_patient_id;
			}
		}
		return false;
	});

	$('#et_print').unbind('click').click(function() {
		window.print_iframe.print();
		return false;
	});

	var last_ElementProcedureList_eye_id = null;

	$('div[data-element-type-class="ElementProcedureList"]').undelegate('input[name="ElementProcedureList\[eye_id\]"]','change').delegate('input[name="ElementProcedureList\[eye_id\]"]','change',function() {
		var element = $(this);

		if ($(this).val() == 3) {
			var i = 0;
			var procs = '';
			$('input[name="Procedures[]"]').map(function() {
				if (procs.length >0) {
					procs += '&';
				}
				procs += 'proc'+i+'='+$(this).val();
				i += 1;
			});

			if (procs.length >0) {
				$.ajax({
					'type': 'GET',
					'url': baseUrl+'/OphTrOperationnote/default/verifyprocedure',
					'data': procs,
					'success': function(result) {
						if (result != 'yes') {
							$('#ElementProcedureList_eye_id_'+last_ElementProcedureList_eye_id).attr('checked','checked');
							if (parseInt(result.split("\n").length) == 1) {
								alert("The following procedure requires a specific eye selection and cannot be entered for both eyes at once:\n\n"+result);
							} else {
								alert("The following procedures require a specific eye selection and cannot be entered for both eyes at once:\n\n"+result);
							}
							return false;
						} else {
							if ($('#typeProcedure').is(':hidden')) {
								$('#typeProcedure').slideToggle('fast');
							}

							changeEye();
							last_ElementProcedureList_eye_id = element.val();

							return true;
						}
					}
				});
			} else {
				if ($('#typeProcedure').is(':hidden')) {
					$('#typeProcedure').slideToggle('fast');
				}

				changeEye();

				last_ElementProcedureList_eye_id = $(this).val();

				return true;
			}

			return false;
		} else {
			if ($('#typeProcedure').is(':hidden')) {
				$('#typeProcedure').slideToggle('fast');
			}

			changeEye();	
			last_ElementProcedureList_eye_id = $(this).val();

			return true;
		}
	});

	$('div[data-element-type-class="ElementAnaesthetic"]').undelegate('input[name="ElementAnaesthetic\[anaesthetic_type_id\]"]','click').delegate('input[name="ElementAnaesthetic\[anaesthetic_type_id\]"]','click',function(e) {
		anaestheticSlide.handleEvent($(this));
	});

	$('div[data-element-type-class="ElementCataract"]').undelegate('input[name="ElementAnaesthetic\[anaesthetist_id\]"]','click').delegate('input[name="ElementAnaesthetic\[anaesthetist_id\]"]','click',function(e) {
		anaestheticGivenBySlide.handleEvent($(this));
	});

	$('#ElementCataract_iol_type_id').die('change').live('change',function() {
		if ($(this).children('optgroup').children('option:selected').text() == 'MTA3UO' || $(this).children('option:selected').text() == 'MTA4UO') {
			$('#ElementCataract_iol_position_id').val(4);
		}
	});

	$('#ElementCataract_iol_power').die('keypress').live('keypress',function(e) {
		if (e.keyCode == 13) {
			return false;
		}
		return true;
	});
});

function callbackVerifyAddProcedure(proc_name,durations,short_version,callback) {
	var eye = $('input[name="ElementProcedureList\[eye_id\]"]:checked').val();

	if (eye != 3) {
		callback(true);
		return;
	}

	$.ajax({
		'type': 'GET',
		'url': baseUrl+'/OphTrOperationnote/Default/verifyprocedure?name='+proc_name+'&durations='+durations+'short_version='+short_version,
		'success': function(result) {
			if (result == 'yes') {
				callback(true);
			} else {
				alert("You must select either the right or the left eye before adding this procedure.");
				callback(false);
			}
		}
	});
}

function AnaestheticSlide() {if (this.init) this.init.apply(this, arguments); }

AnaestheticSlide.prototype = {
	init : function(params) {
		this.anaestheticTypeSliding = false;
	},
	handleEvent : function(e) {
		var slide = false;

		if (!this.anaestheticTypeSliding) {
			if (e.val() == 5 && !$('#ElementAnaesthetic_anaesthetist_id').is(':hidden')) {
				this.slide(true);
			} else if (e.val() != 5 && $('#ElementAnaesthetic_anaesthetist_id').is(':hidden')) {
				this.slide(false);
			}
		}

		// If topical anaesthetic type is selected, select topical delivery
		if (e.val() == 1) {
			$('#ElementAnaesthetic_anaesthetic_delivery_id_5').click();
		}
	},
	slide : function(hide) {
		this.anaestheticTypeSliding = true;
		$('#ElementAnaesthetic_anaesthetist_id').slideToggle('fast');
		if (hide) {
			if (!$('#div_ElementAnaesthetic_anaesthetic_witness_id').is(':hidden')) {
				$('#div_ElementAnaesthetic_anaesthetic_witness_id').slideToggle('fast');
			}
		} else {
			if ($('#ElementAnaesthetic_anaesthetist_id_3').is(':checked') && $('#div_ElementAnaesthetic_anaesthetic_witness_id').is(':hidden')) {
				$('#div_ElementAnaesthetic_anaesthetic_witness_id').slideToggle('fast');
			}
		}

		$('#ElementAnaesthetic_anaesthetic_delivery_id').slideToggle('fast');
		$('#div_ElementAnaesthetic_Agents').slideToggle('fast');
		$('#div_ElementAnaesthetic_Complications').slideToggle('fast');
		$('#div_ElementAnaesthetic_anaesthetic_comment').slideToggle('fast',function() {
			anaestheticSlide.anaestheticTypeSliding = false;
		});
	}
}

function AnaestheticGivenBySlide() {if (this.init) this.init.apply(this, arguments); }

AnaestheticGivenBySlide.prototype = {
	init : function(params) {
		this.anaestheticTypeWitnessSliding = false;
	},
	handleEvent : function(e) {
		var slide = false;

		// if Fife mode is enabled
		if ($('#div_ElementAnaesthetic_anaesthetic_witness_id')) {
			// If nurse is selected, show the witness field
			if (!this.anaestheticTypeWitnessSliding) {
				if ((e.val() == 3 && $('#div_ElementAnaesthetic_anaesthetic_witness_id').is(':hidden')) ||
					(e.val() != 3 && !$('#div_ElementAnaesthetic_anaesthetic_witness_id').is(':hidden'))) {
					this.slide();
				}
			}
		}
	},
	slide : function() {
		this.anaestheticTypeWitnessSliding = true;
		$('#div_ElementAnaesthetic_anaesthetic_witness_id').slideToggle('fast',function() {
			anaestheticGivenBySlide.anaestheticTypeWitnessSliding = false;
		});
	}
}

var anaestheticSlide = new AnaestheticSlide;
var anaestheticGivenBySlide = new AnaestheticGivenBySlide;

function sidePortController(_drawing)
{
	var phakoIncision;
	var sidePort1;
	var sidePort2;
	
	// Register controller for notifications
	_drawing.registerForNotifications(this, 'notificationHandler', ['ready', 'parameterChanged']);
	
	// Method called for notification
	this.notificationHandler = function(_messageArray)
	{
		switch (_messageArray['eventName'])
		{
			// Ready notification
			case 'ready':
				// Get reference to the phakoIncision
				phakoIncision = _drawing.firstDoodleOfClass('PhakoIncision');
				
				// If this is a newly created drawing, add two sideports
				if (_drawing.isNew)
				{
					sidePort1 = _drawing.addDoodle('SidePort', {rotation:0});
					sidePort2 = _drawing.addDoodle('SidePort', {rotation:Math.PI});
					_drawing.deselectDoodles();
				}
				// Else cancel sync for an updated drawing
				else
				{
					phakoIncision.willSync = false;
				}
				break;
			
			// Parameter change notification
			case 'parameterChanged':
				// Only sync for new drawings
				if (_drawing.isNew)
				{
					// Get rotation value of surgeon doodle
					var surgeonDrawing = window['ed_drawing_edit_Position'];
					var surgeonRotation = surgeonDrawing.firstDoodleOfClass('Surgeon').rotation;

					// Get doodle that has moved in opnote drawing
					var masterDoodle = _messageArray['object'].doodle;

					// Stop syncing if PhakoIncision or a SidePort is changed
					if (masterDoodle.drawing.isActive && (masterDoodle.className == 'PhakoIncision' || masterDoodle.className == 'SidePort'))
					{
						phakoIncision.willSync = false;
					}
			
					// Keep sideports in sync with PhakoIncision while surgeon is still syncing with it
					if (masterDoodle.className == "PhakoIncision" && masterDoodle.willSync)
					{
						if (typeof(sidePort1) != 'undefined')
						{
							sidePort1.setSimpleParameter('rotation', (surgeonRotation + Math.PI/2)%(2* Math.PI));
						}
						if (typeof(sidePort2) != 'undefined')
						{
							sidePort2.setSimpleParameter('rotation', (surgeonRotation - Math.PI/2)%(2* Math.PI));
						}
					}
				}
				break;
		}
	}
}

function changeEye() {
	// Swap side of each drawing
	var drawingEdit1 = window['ed_drawing_edit_Position'];
	var drawingEdit2 = window['ed_drawing_edit_Cataract'];

	if (drawingEdit1.eye == ED.eye.Right) drawingEdit1.eye = ED.eye.Left;
	else drawingEdit1.eye = ED.eye.Right;
	if (drawingEdit2.eye == ED.eye.Right) drawingEdit2.eye = ED.eye.Left;
	else drawingEdit2.eye = ED.eye.Right;
	
	// Set surgeon position to temporal side
	var doodle = drawingEdit1.firstDoodleOfClass('Surgeon');
	doodle.setParameterWithAnimation('surgeonPosition', 'Temporal');
}
