$(document).ready(function(){
//for reload page
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
//no reload page
//	$('.b-paidNav__li').on('click', function() {
//		$('.b-paidNav__li').each(function() {
//			$(this).removeClass('active');
//		});
//		$(this).addClass('active');
//	});
	$('#paid_cash_index-modalSearchGrid').on('show.bs.modal', function(e){ //просто при нажатии
		$(".b-paid__errorFormPatient").html('');
		$(".b-paid__errorFormPatient").css('display', 'none');
	});
	$('#paid_cash_index-modalSearchGrid').on('hidden.bs.modal', function (e){
		$("#paid_cash_index-modalSearchGridBody").html(''); //clean
		$(document).off('click.yiiGridView', $.fn.yiiGridView.settings['paid_cash_search-gridPatients'].updateSelector);
		$(document).off('click', '#paid_cash_search-gridPatients a.btn.btn-default.btn-block.btn-xs');
	});
	$(document).on('click', '#add_paid_modal_patient', function(){ //добавление пациента из модали
		$('#paid_cash_index-modalSearchGrid').modal('hide');
		$('#add_paid_patient_button').css('display', 'inline-block');
		$('#add_paid_patient_button').animate({opacity: 1}, "slow");
//		$('#add_paid_patient_input').css('display', 'inline'); //добавляем hidden input
	});
	$("#paid_cash_servicesList-buttonEmptyGroups").on('click', function(e){
		$("#paid_cash_servicesList-emptyGroups").modal("show");
	});
	$("#paid_cash_servicesList-emptyGroups").on("hidden.bs.modal", function(e){ //обнуляем ошибки при закрытии модали
		$(".b-paid__errorFormServicesGroup").html("");
		$(".b-paid__errorFormServicesGroup").css("display", "none");
	});
	$(".b-paid__servicesGroupPlus").popover({
		title : 'Выберите действие',
		trigger: 'click',
		html: true,
		content: '<button class="btn btn-block btn-primary btn-xs" id="paid_cash_servicesList-popoverButtonAddService">Услугу</button>\n\
				  <button class="btn btn-block btn-primary btn-xs" id="paid_cash_servicesList-popoverButtonAddGroup">Группу</button>'
    });
	$('.b-paid__servicesGroupPlus').on('shown.bs.popover', function() {
		
		var valueP_id=$(this).attr('id'); //замыкание
		
		$('#paid_cash_servicesList-popoverButtonAddGroup').on('click', function() {
			$("#paid_cash_servicesList-modalAddGroup").modal("show");
			$("#Paid_Service_Groups_p_id").attr('value', valueP_id);
		});
	});
});