$(document).ready(function(){
	$('#myModal').on('hidden.bs.modal', function (e){
		$("#myModalBody").html(''); //clean
		$(document).off('click.yiiGridView', $.fn.yiiGridView.settings['paid_grid_search_patients'].updateSelector);
		$(document).off('click', '#paid_grid_search_patients a.btn.btn-default.btn-block.btn-xs');
	});
	$(document).on('click', '#add_paid_modal_patient', function(){
		$('#myModal').modal('hide');
		$('#add_paid_patient').css('display', 'inline-block');
		$('#add_paid_patient').animate({opacity: 1}, "slow");
	});
});