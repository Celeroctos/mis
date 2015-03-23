$(document).ready(function(){
//for reload page
	(function(){
		var url=document.location.href;
		var action=url.split('/');
		$(".b-paidNav__li").each(function(){
			$(this).removeClass('active');
			var nameHref=$(this).children(".b-paidNav__href").attr("href");

			if(nameHref.indexOf(action[5])!==-1)
			{
				$(this).addClass('active');
			}
		});
	})();
	(function(){
		var url=document.location.href;
		var action=url.split('/');
		var group_id=action[7];
		$('.b-paid__serviceItemGroup').each(function() {
			$(this).removeClass('active');
			var nameHref=$(this).children('.b-paid__addSubGroup').attr('id');
		
			if(group_id!==undefined && group_id.indexOf(nameHref)!==-1)
			{
				$(this).addClass('active');
			}
		});
	})();
//for no reload page
//	$('.b-paidNav__li').on('click', function() {
//		$('.b-paidNav__li').each(function() {
//			$(this).removeClass('active');
//		});
//		$(this).addClass('active');
////	});
	$('#callModalAddGroup').on('click', function(){
		$('#modalAddGroup').modal('show'); //добавление группы без родителя с кнопки
	});
	$('#modalAddGroup').on('click', function() {
		//TODO редирект на эту же страницу
	});
	
	$(".b-paid__addSubGroup").popover({
		title : 'Выберите действие',
		trigger: 'focus',
		html: true,
		content: '<button class="btn btn-block btn-primary btn-xs" id="popoverButtonAddService">Услугу</button>\n\
				  <button class="btn btn-block btn-primary btn-xs" id="popoverButtonAddGroup">Подгруппу</button>'
    });
	$('.b-paid__addSubGroup').on('shown.bs.popover', function(e) {
		var valueP_id=$(this).attr('id'); //id группы
		$('#popoverButtonAddGroup').on('click', function(e) {
			$("#modalAddGroup").modal("show");
			$("#Paid_Service_Groups_p_id").attr('value', valueP_id); //для поля hidden
		});
		$('#popoverButtonAddService').on('click', function() {
			$('#modalAddServices').modal('show');
			$("#Paid_Services_paid_service_group_id").attr('value', valueP_id);
		});
	});

	$('#paid_cash_index-modalSearchGrid').on('show.bs.modal', function(e){ //просто при нажатии
		$(".b-paid__errorFormPatient").html('');
		$(".b-paid__errorFormPatient").css('display', 'none');
	});
	$('#paid_cash_index-modalSearchGrid').on('hidden.bs.modal', function (e){
		$("#paid_cash_index-modalSearchGridBody").html(''); //clean
		$(document).off('click.yiiGridView', $.fn.yiiGridView.settings['paid_cash_search-gridPatients'].updateSelector);
		$(document).off('click', '#paid_cash_search-gridPatients a.btn.btn-default.btn-block.btn-xs');
	});
	$(document).on('click', '#add_paid_modal_patient', function(e){ //добавление пациента из модали
		$('#paid_cash_index-modalSearchGrid').modal('hide');
		$('#add_paid_patient_button').css('display', 'inline-block');
		$('#add_paid_patient_button').animate({opacity: 1}, "slow");
//		$('#add_paid_patient_input').css('display', 'inline'); //добавляем hidden input
	});
});