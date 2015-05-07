function UpdatePatient () {
	
	/**
	 * @private
	 * @callback UpdatePatient~loadModal
	 * Load boostrap modal for update patient
	 */
	function loadModal() {
		
		/**
		 * @private
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
		};
		
		$.ajax({
			method: 'post',
			url: url,
			success: ajaxSuccess
		});
	}
	
	this.handlerUpdatePatient = function () {
		$(document).on('click', '#updatePatient', loadModal);
	};
	
	this.init = function () {
		this.handlerUpdatePatient();
	};
}

var updatePatient = new UpdatePatient();
updatePatient.init();