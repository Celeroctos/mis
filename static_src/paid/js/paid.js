$(document).ready(function(){
	$('#myModal').on('hidden.bs.modal', function (e){
		$("#myModalBody").html(''); //clean
		$(document).off('click.yiiGridView', $.fn.yiiGridView.settings['yw0'].updateSelector);
		$(document).off('click', '#yw0 a.btn.btn-default.btn-block.btn-xs');
	});
});