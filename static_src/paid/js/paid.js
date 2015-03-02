$(document).ready(function(){
	$('#myModal').on('hidden.bs.modal', function (e){
		$("#myModalBody").html(''); //clean
		$(document).off('click.yiiGridView', $.fn.yiiGridView.settings['paid_grid_search_patients'].updateSelector);
		$(document).off('click', '#paid_grid_search_patients a.btn.btn-default.btn-block.btn-xs');
	});
	$(document).on('click', '#add_paid_modal_patient', function(){ //добавление пациента из модали
		$('#myModal').modal('hide');
		$('#add_paid_patient_button').css('display', 'inline-block');
		$('#add_paid_patient_button').animate({opacity: 1}, "slow");
//		$('#add_paid_patient_input').css('display', 'inline'); //добавляем hidden input
	});
});