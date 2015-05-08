/**
 * Creates new UpdatePatient
 * @class
 */
function UpdatePatient () {
	
	/**
	 * Удаление поля с телефоном пациента.
	 * @callback UpdatePatient~deleteInputContact
	 * @this jQuery - $('.b-contactUpdate .b-phones__spanDelete')
	 * @private
	 */
	function deleteInputContact () {
		$(this).on('click', function () {
			$(this).parent().detach();
		});
	}
	
	/**
	 * Добавление поля с телефоном пациента
	 * @callback UpdatePatient~createInputContact
	 * @this jQuery - $('.b-contactUpdate .b-phones__spanPlus')
	 * @private
	 */
	function createInputContact () {
		var divTag = $('<div class="b-paid__contactUpdatePatient input-group"></div>');
		var inputTag = $('<input class="form-control input-sm" id=' + Math.floor((Math.random() * 100) + 1) + ' type="text" name="Patient_Contacts[]">');
		var spanTag = $('<span class="b-phones__spanDelete input-group-addon glyphicon glyphicon-remove-circle" aria-hidden="true"></span>');
		divTag.append(inputTag);
		divTag.append(spanTag);
		$(this).parent().append(divTag);
		
		spanTag.on('click', function () {
			$(this).parent().detach();
		});
	}
	
	/**
	 * @private
	 * @callback UpdatePatient~loadModal
	 * Load boostrap modal for update patient
	 */
	function loadModal() {
		
		/**
		 * @private
		 * @type Number
		 */
		var patient_id=document.location.href.split('/');
		
		/**
		 * @private
		 * @type String
		 */
		var url='/paid/cash/updatePatient/patient_id/' + patient_id[7];
		
		/**
		 * @private
		 * @param {mixed} result
		 * @callback UpdatePatient~ajaxSuccess
		 * @type function
		 */
		var ajaxSuccess = function (result) {
			$('#modalUpdatePatientBody').html(result);
			$('#modalUpdatePatient').modal('show');
			
			$('.b-contactUpdate .b-phones__spanPlus').on('click', createInputContact);
			
			$('.b-contactUpdate .b-phones__spanDelete').each(deleteInputContact);
		};
		
		$.ajax({
			method: 'post',
			url: url,
			success: ajaxSuccess
		});
	}
	
	/**
	 * @private
	 */
	function handlerUpdatePatient() {
		$(document).on('click', '#updatePatient', loadModal);
	}
	
	/**
	 * @private
	 */	
	function handlerHiddenModal() {
		$(document).on('hidden.bs.modal', '#modalUpdatePatient', function () {
			$('#modalUpdatePatientBody').empty();
		});
	}
	
	this.init = function () {
		handlerUpdatePatient();
		handlerHiddenModal();
	};
}

var updatePatient = new UpdatePatient();
updatePatient.init();