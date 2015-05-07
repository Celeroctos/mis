function UpdatePatient () {
	
	/**
	 * @access private
	 * @callback UpdatePatient~loadModal
	 * Load boostrap modal for update patient
	 */
	function loadModal() {
//		$('#modalUpdatePatientBody').html();
//		$('#modalUpdatePatient').modal('show');
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