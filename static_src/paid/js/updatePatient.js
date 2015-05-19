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
			var count=$('.b-contactUpdate .b-paid__contactUpdatePatient').length;
			if(count>1) {
				$(this).parent().detach();
			} else {
				alert('Нельзя удалить последний контакт пациента.');
			}
		});
	}
	
	/**
	 * Удаление поля с документов.
	 * @callback UpdatePatient~deleteInputDocument
	 * @this jQuery - $('.b-documentUpdate .b-documentUpdate__delete')
	 * @private
	 */	
	function deleteInputDocument () {
		$(this).on('click', function () {
			var count=$('.b-documentUpdate .b-documentUpdate__delete').length;
			if(count>1) {
				$(this).parent().detach();
			} else {
				alert('Нельзя удалить последний документ пациента.');
			}
		});
	}
	
	/**
	 * Добавление полей с документами пациента
	 * @callback UpdatePatient~createInputDocument
	 * @this jQuery - $('.b-documentUpdate .b-documentUpdate__delete')
	 * @private
	 */	
	function createInputDocument () {
		var rowTag = $('<div class="row b-documentUpdate__row">' +
							'<div class="col-xs-4">' +
								'<select class="form-control input-sm" id="'+ Math.floor((Math.random() * 5000) + 1) + '" name="Patient_Documents[type][]">' +
									'<option value="" selected="selected"></option>' +
									'<option value="1">Паспорт</option>' +
									'<option value="2">Свидетельство о рождении</option>' +
									'<option value="3">Вид на жительство</option>' +
									'<option value="4">Паспорт иностранного гражданина</option>' +
									'<option value="5">Удостоверение личности</option>' +
									'<option value="6">Другой документ</option>' +
								'</select>' +
							'</div>' +
							'<div class="col-xs-4">' +
								'<input class="form-control input-sm" id="'+ Math.floor((Math.random() * 5000) + 1) + '" type="text" name="Patient_Documents[serie][]">' +
							'</div>' +
							'<div class="col-xs-3">' +
								'<input class="form-control input-sm" id="'+ Math.floor((Math.random() * 5000) + 1) + '" type="text" name="Patient_Documents[number][]">' +		
							'</div>' +
						'</div>');
		var spanTag=$('<div class="col-xs-1 b-documentUpdate__delete">' +
						  '<span class="b-documentUpdate__spanMinus glyphicon glyphicon-minus" aria-hidden="true"></span>' +
					  '</div>');
		rowTag.append(spanTag);
		console.log(rowTag);
		$('.b-documentUpdate').append(rowTag);
		
		/**
		 * Кнопка на удаление документа,
		 * который был сгенерирован выше
		 */
		spanTag.on('click', function () {
			var count=$('.b-documentUpdate .b-documentUpdate__delete').length;
			if(count>1) {
				$(this).parent().detach();
			} else {
				alert('Нельзя удалить последний документ пациента.');
			}
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
		
		/**
		 * кнопка удаления контакта
		 */
		spanTag.on('click', function () {
			var count=$('.b-contactUpdate .b-paid__contactUpdatePatient').length;
			if(count>1) {
				$(this).parent().detach();
			} else {
				alert('Нельзя удалить последний контакт пациента.');
			}
		});
	}
	
	/**
	 * @private
	 * @callback UpdatePatient~loadModal
	 * Load boostrap modal for update patient
	 */
	function loadModal() {
		
		/**
		 * @type Number
		 */
		var patient_id=document.location.href.split('/');
		
		/**
		 * Using in ajax
		 * @type String
		 */
		var url='/paid/cash/updatePatient/patient_id/' + patient_id[7];
		
		/**
		 * @param {mixed} result
		 * @callback UpdatePatient~ajaxSuccess
		 * @type function
		 */
		var ajaxSuccess = function (result) {
			$('#modalUpdatePatientBody').html(result);
			$('#modalUpdatePatient').modal('show');
			
			$('#Patients_birthday').inputmask("mask", {"mask": "9999-99-99"});
			$('#Patients_snils').inputmask("mask", {"mask": "999-999-999-99"});
			$('.b-paid__contactUpdatePatient .form-control').inputmask("mask", {"mask": "+7 (999) 999-99-99"});
			
			$('.b-contactUpdate .b-phones__spanPlus').on('click', createInputContact);
			$('.b-documentUpdate .b-documentUpdate__spanPlus').on('click', createInputDocument);
			
			$('.b-contactUpdate .b-phones__spanDelete').each(deleteInputContact);
			$('.b-documentUpdate .b-documentUpdate__delete').each(deleteInputDocument);
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