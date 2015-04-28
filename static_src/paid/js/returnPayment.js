function ReturnPaymentController() {
	
	/**
	 * Ajax success method
	 * @param {mixed} result
	 */
	var ajaxSuccess = function (result) {
		$('#modalReturnPaymentBody').html(result);
		$('#modalReturnPayment').modal('show');		
	};
	
	/**
	 * load bootstrap modal, if successful
	 */
	var loadModal = function () {
		var url=document.location.href;
		var action=url.split('/');
		$(document).ready(function () {
			$.ajax({
				url: '/paid/cashAct/ReturnPayment/patient_id/' + action[7],
				success: ajaxSuccess
				
			});
		});
	};
	
	this.handlerButton = function () {
		$(document).on('click', '#returnPayment', loadModal);
	};
	
	this.handlerHiddenModal = function () {
		$('#modalReturnPayment').on('hidden.bs.modal', function () {
			$('#modalReturnPaymentBody').empty();
		});		
	};
	
	this.init = function () {
		this.handlerButton();
		this.handlerHiddenModal();
	};
}

returnPayment=new ReturnPaymentController();
returnPayment.init();