
function callbackAddProcedure(procedure_id) {
	var eye = $('input[name="ElementProcedureList\[eye_id\]"]:checked').val();

	$.ajax({
		'type': 'GET',
		'url': baseUrl+'/OphTrOperationnote/Default/loadElementByProcedure?procedure_id='+procedure_id+'&eye='+eye,
		'success': function(html) {
			if (html.length >0) {
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

	$('div[data-element-type-class="ElementCataract"]').undelegate('#ElementCataract_incision_site_id','change').delegate('#ElementCataract_incision_site_id','change',function(e) {
		e.preventDefault();

		magic.setDoodleParameter('PhakoIncision', 'incisionSite', $(this).children('option:selected').text());

		if ($('#ElementCataract_length').val() > 9.9) {
			$('#ElementCataract_length').val(9.9);
		}

		return false;
	});

	$('div[data-element-type-class="ElementCataract"]').undelegate('#ElementCataract_incision_type_id','change').delegate('#ElementCataract_incision_type_id','change',function(e) {
		e.preventDefault();

		magic.setDoodleParameter('PhakoIncision', 'incisionType', $(this).children('option:selected').text());

		return false;
	});

	$('div[data-element-type-class="ElementProcedureList"]').undelegate('input[name="ElementProcedureList\[eye_id\]"]','change').delegate('input[name="ElementProcedureList\[eye_id\]"]','change',function() {
		if ($('#typeProcedure').is(':hidden')) {
			$('#typeProcedure').slideToggle('fast');
		}

		magic.eye_changed($(this).val());
	});

	$('div[data-element-type-class="ElementAnaesthetic"]').undelegate('input[name="ElementAnaesthetic\[anaesthetic_type_id\]"]','click').delegate('input[name="ElementAnaesthetic\[anaesthetic_type_id\]"]','click',function(e) {
		anaestheticSlide.handleEvent($(this));
	});

	$('div[data-element-type-class="ElementCataract"]').undelegate('input[name="ElementAnaesthetic\[anaesthetist_id\]"]','click').delegate('input[name="ElementAnaesthetic\[anaesthetist_id\]"]','click',function(e) {
		anaestheticGivenBySlide.handleEvent($(this));
	});

	$('div[data-element-type-class="ElementCataract"]').undelegate('#ElementCataract_meridian','change').delegate('#ElementCataract_meridian','change',function() {
		if (doodle = magic.getDoodle('PhakoIncision')) {
			if (doodle.getParameter('incisionMeridian') != $(this).val()) {
				doodle.setParameterWithAnimation('incisionMeridian',$(this).val());
				magic.followSurgeon = false;
			}
		}
	});

	$('div[data-element-type-class="ElementCataract"]').undelegate('#ElementCataract_meridian','keypress').delegate('#ElementCataract_meridian','keypress',function(e) {
		if (e.keyCode == 13) {
			if (doodle = magic.getDoodle('PhakoIncision')) {
				if (doodle.getParameter('incisionMeridian') != $(this).val()) {
					doodle.setParameterWithAnimation('incisionMeridian',$(this).val());
					magic.followSurgeon = false;
				}
			}
			return false;
		}
	});

	$('div[data-element-type-class="ElementCataract"]').undelegate('#ElementCataract_length','change').delegate('#ElementCataract_length','change',function() {
		if (doodle = magic.getDoodle('PhakoIncision')) {
			if (parseFloat($(this).val()) > 9.9) {
				$(this).val(9.9);
			} else if (parseFloat($(this).val()) < 0.1) {
				$(this).val(0.1);
			}
			doodle.setParameterWithAnimation('incisionLength',$(this).val());
		}
	});

	$('div[data-element-type-class="ElementCataract"]').undelegate('#ElementCataract_length','keypress').delegate('#ElementCataract_length','keypress',function(e) {
		if (e.keyCode == 13) {
			if (doodle = magic.getDoodle('PhakoIncision')) {
				if (parseFloat($(this).val()) > 9.9) {
					$(this).val(9.9);
				} else if (parseFloat($(this).val()) < 0.1) {
					$(this).val(0.1);
				}
				doodle.setParameterWithAnimation('incisionLength',$(this).val());
			}
			$('#ElementCataract_meridian').select().focus();
			return false;
		}
	});

	$('div[data-element-type-class="ElementCataract"]').undelegate('#ElementCataract_iol_type_id','change').delegate('#ElementCataract_iol_type_id','change',function() {
		if ($(this).children('option:selected').text() == 'MTA3UO' || $(this).children('option:selected').text() == 'MTA4UO') {
			$('#ElementCataract_iol_position_id').val(4);
		}
	});
});

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
