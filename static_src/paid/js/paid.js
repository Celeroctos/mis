$(document).ready(function(){
	$('#myModal').on('show.bs.modal', function(e){
		$(".b-paid__errorFormPatient").html('');
		$(".b-paid__errorFormPatient").css('display', 'none');
	});
	$('#myModal').on('hidden.bs.modal', function (e){
		$("#myModalBody").html(''); //clean
		$(document).off('click.yiiGridView', $.fn.yiiGridView.settings['paid_cash_search-gridPatients'].updateSelector);
		$(document).off('click', '#paid_cash_search-gridPatients a.btn.btn-default.btn-block.btn-xs');
	});
	$(document).on('click', '#add_paid_modal_patient', function(){ //добавление пациента из модали
		$('#myModal').modal('hide');
		$('#add_paid_patient_button').css('display', 'inline-block');
		$('#add_paid_patient_button').animate({opacity: 1}, "slow");
//		$('#add_paid_patient_input').css('display', 'inline'); //добавляем hidden input
	});
});