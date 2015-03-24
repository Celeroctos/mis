function updateService() { //add to onlick
	$.ajax({'success': function (html) {
			$('#modalServiceGroupsBody').html(html);
			$('#modalServiceGroups').modal('show');
		},
			'url': $(this).attr('href')
	});
	return false;
}

/***********************************************************/
$(document).ready(function() {
	//for reload page
	(function() {
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
	(function() {
		var url=document.location.href;
		var action=url.split('/');
		var group_id=action[7];
		$('.b-paid__serviceItemGroup').each(function() {
			$(this).removeClass('active');
			var nameHref=$(this).children('.b-paid__addPopover').attr('id');

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
	function modelServiceGroups() {
		this.handlerAddService=function () { //add to onclick, modal for add service
			$.ajax({'success': function (html) {
					$('#modalServiceGroupsBody').html(html);
					$('#modalServiceGroups').modal('show');
//					$("#Paid_Services_paid_service_group_id").attr('value', this.valueP_id);
				},
					'url': '/paid/cash/addService/group_id/' + this.valueP_id
			});
			return false;
		};
		
		this.addPopover=function () { //add popover bootstrap for add service/group
			$(".b-paid__addPopover").popover({
				title : 'Выберите действие',
				trigger: 'focus',
				html: true,
				content: '<button class="btn btn-block btn-primary btn-xs" id="popoverButtonAddService">Услугу</button>\n\
						  <button class="btn btn-block btn-primary btn-xs" id="popoverButtonAddGroup">Подгруппу</button>'
			});	
			return false;
		};
		
		this.handlerAddGroup=function () { //add to onclick, 
			$.ajax({'success': function (html) {
					$('#modalServiceGroupsBody').html(html);
					$('#modalServiceGroups').modal('show');
//					$("#Paid_Service_Groups_p_id").attr('value', this.valueP_id);
				},
					'url': '/paid/cash/addGroup/group_id/' + this.valueP_id
			});
			return false;
		};
		
		this.handlerShownPopover=function () {
			modelServiceGroups.valueP_id=$(this).attr('id'); //this ссылается на внешний объект jQuery .b-paid__addPopover
			$('#popoverButtonAddGroup').on('click', $.proxy(modelServiceGroups.handlerAddGroup, modelServiceGroups));
			$('#popoverButtonAddService').on('click', $.proxy(modelServiceGroups.handlerAddService, modelServiceGroups));			
		};
		
		this.shownEventPopover=function () {
			$('.b-paid__addPopover').on('shown.bs.popover', modelServiceGroups.handlerShownPopover);
		};
		
		this.hiddenModal=function () {
			$('#modalServiceGroups').on('hidden.bs.modal', function () {
				$('#Paid_Services_since_date').unbind();
				$('#Paid_Services_exp_date').unbind();
				$('#ui-datepicker-div').detach();
				$('#modalServiceGroupsBody').html('');
			});
		};
	}
	
	var modelServiceGroups=new modelServiceGroups();
	modelServiceGroups.addPopover();
	modelServiceGroups.shownEventPopover();
	modelServiceGroups.hiddenModal();
	
//	$('#callModalAddGroup').on('click', function(){
//		$('#modalAddGroup').modal('show'); //добавление группы без родителя с кнопки
//	});
//	
//	$('#modalAddGroup').on('click', function() {
//		//TODO редирект на эту же страницу
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
	
	$(document).on('click', '#add_paid_modal_patient', function(e){ //добавление пациента из модали
		$('#paid_cash_index-modalSearchGrid').modal('hide');
		$('#add_paid_patient_button').css('display', 'inline-block');
		$('#add_paid_patient_button').animate({opacity: 1}, "slow");
//		$('#add_paid_patient_input').css('display', 'inline'); //добавляем hidden input
	});
});