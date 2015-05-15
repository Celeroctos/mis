function Controller () {
	
}

var ERROR_LOGIN = 'ERROR_LOGIN';
/**
 * Если пользователь не авторизован, то перенаправляем его.
 * @returns {undefined}
 */
function redirectToLogin(){
	location.href='/service/auth/login';
	return;
}

/**
 * insert function into Yii handler
 */
function afterDeleteService(link, success, data)
{
	if(success && data==0)
		alert("Удаление невозможно, т.к. у услуги имеются связи.");
}

/**
 * insert function into Yii handler
 */
function updateService() { //add to onlick
    $.ajax({'success': function (html) {
		$('#modalServiceGroupsBody').html(html);
		$('#modalServiceGroups').modal('show');
		$('#Paid_Services_price').inputmask("9{2,9}.9{2}");
	},
			'url': $(this).attr('href')
    });
    return false;
}

/**
 * Класс для работы с выбором счёта
 * into Yii handlers ajaxButton
 */
function classChooseExpenses() {
	var data={};
	callBackSuccess=function (result) {
		$('#modalSelectExpenseServicesBody').html(result);
		$('._expense_number').html(data.expense_number);
		$('#modalSelectExpenseServices').modal('show');
	};
	
	callBackHiddenModalExpenses=function () {
		$('#modalSelectExpensesBody').empty();
	};
	
	callBackHiddenModalExpenseServices=function () {
		$('#modalSelectExpenseServicesBody').empty();
		var gridChooseExpensesId=$('#modalSelectExpensesBody').find('.grid-view').attr('id'); //считываем id грида
		$('#'+gridChooseExpensesId).yiiGridView('update');
	};
	
	callBackClickTr=function () {
		data.expense_number=$(this).find(".expense_number").html();

		$.ajax({"url": "/paid/cashAct/ChooseExpenseServices", 
				"data": data,
				"success": callBackSuccess
		});
	};
	
	/**
	 * callback success into Yii CHtml::ajaxSubmitButton.
	 * @see gridChooseExpenses.php
	 */
	var ajaxResponseSearch = function (gridContent) {
		$("#modalSelectExpensesBody").html(gridContent);
		$('.modalOverlay').animate({opacity: 1}, 120);
		$('#Paid_Expenses_date').inputmask("mask", {"mask": "9999-99-99"});
		$('#Paid_Expenses_dateEnd').inputmask("mask", {"mask": "9999-99-99"});
	};
	
	/**
	 * callback afterValidate Yii handler
	 * @param {jQuery} form is the jquery representation of the form object
	 * @param {JSON} data is the JSON response from the server-side validation
	 * @param {boolean} hasError is a boolean value indicating whether there is any validation error
	 * If the return value of this function is NOT true, the normal form submission will be cancelled
	 * @see CActiveForm
	 */
	this.afterValidateSearchExp = function (form, data, hasError) {
		$('.modalOverlay').animate({opacity: 0.3}, 50);
		if(!hasError) {
			var url=document.location.href;
			var action=url.split('/');
			
			$.ajax({'data': $('#formSearchExpenses').serialize(),
					'url': '/paid/cashAct/chooseExpenses/patient_id/' + action[7],
					'type': 'POST',
					'success': ajaxResponseSearch
			});
		}
		return false; //не нужно отправлять submit
	};
	
	/**
	* Insert function into Yii handler (ajaxButton)
	* success method for ajax request
	* @param {mixed} html ответ от сервера
	*/
	this.selectExpenses=function(html) {
		
		if(html===ERROR_LOGIN) 
		{ //пришёл ответ от сервера в виде: echo "ERROR_LOGIN"
			redirectToLogin();
			return;
		}
		
		/**TODO */
		//чистим это всё только когда есть выбранный пациент!!! TODODODODO
		
		var i=0;
		$('#selectedServicesTable tbody tr').each(function () {
			if ($(this).attr('class') !== 'empty')
			{
				$(this).detach();
			}
			else
			{ //php PSR style..
				i++;
			}
		});
		
		if(i===0)
		{ // в таблице selectedServicesTable нету тега tr с классом empty
			$('#selectedServicesTable tbody').append('<tr class="empty"><td colspan="7"><span>Пусто</span></td></tr>');
		}
		
		$('#selectedServicesTable tbody .empty').css('display', 'table-row');
		$('#deleteOrderButton, #punchButton').off('click');		
		$('#deleteOrderButton, #punchButton').attr('disabled', 'disabled');
		$('#CashSum').val('');
		$('#punchButton').removeClass('btn-danger');
		$('#punchButton').addClass('btn-default');
		$('#TotalSum').html(0);
		/* TODODODODO */
		
		$('#modalSelectExpensesBody').html(html);
		$('#Paid_Expenses_date').inputmask("mask", {"mask": "9999-99-99"});
		$('#Paid_Expenses_dateEnd').inputmask("mask", {"mask": "9999-99-99"});
		$('#modalSelectExpenses').modal('show');
	};
	
	this.initHandlers=function () {
		$(document).on('hidden.bs.modal', '#modalSelectExpenses', callBackHiddenModalExpenses);
		$(document).on('click', '.gridChooseExpenses tbody tr', callBackClickTr);
		$(document).on('hidden.bs.modal', '#modalSelectExpenseServices', callBackHiddenModalExpenseServices);
	};
}
var chooseExpenses=new classChooseExpenses();
chooseExpenses.initHandlers();

/**
 * insert function into Yii handler
 * success method for ajax request
 * @param html {mixed} success response
 */
function selectServices(html) {
	
	if(html===ERROR_LOGIN) {
		redirectToLogin();
		return;
	}
	$('#punchButton').removeClass('btn-danger');
	$('#punchButton').addClass('btn-default');
	$('#modalSelectServicesBody').html(html);
    $('#modalSelectServices').modal('show');
}

/**
 * Конструктор модели
 * @param {string} code
 * @param {integer} paid_service_group_id
 * @param {string} name
 * @returns {modelPaid_Services}
 */
function modelPaid_Services(code, paid_service_group_id, name) {
    this.code=code;
    this.paid_service_group_id=paid_service_group_id;
    this.name=name;
}

function classSelectServices() {
	var i=0; //for echo empty row
	var obj;
	var doctorTdTag;
	var price = 0;
	var arr={};
	var prepareOrder=false; //по умолчанию нужно создавать заказ
	var expense_number;
	
	var _callBackSuccessHandlerPunch=function (paid_order_id) {
		if(Number(paid_order_id) > 0)
		{ //если заказ id корректный
			$('#punchButton').off('click');
			$('#deleteOrderButton').off('click');

			$('#CashSum').val('');
			$('#TotalSum').html(arr.priceSum.toFixed(2));
			$('#punchButton').removeAttr('disabled');
			$('#punchButton').on('click', function () {
				/**
				 * см. inputMaskComplete
				 */
				alert(Number($('#CashSum').val()));
				if( Number( $('#CashSum').val() )*100 >= Number( $('#TotalSum').html() )*100 ) //если сдача получилось больше нуля, то можно пробить чек
				{
					$('#punchButton').removeClass('btn-danger');
					$('#punchButton').addClass('btn-default');
					$.ajax({
						'url': '/paid/cashAct/punch/paid_order_id/' + paid_order_id + '/patient_id/' + arr.patient_id,
						'success': function (print_refferals) {
							//TODO провели платёж, закрыли счёт, создали направления
							//TODO печатаем направления
							window.open('http://www.w3schools.com', '','location=no, titlebar=no, toolbar=no, width=500px, height=500px, top=250px, left=350px;');
//							location.reload();
						}
					});
				}
				else {
					$('#punchButton').removeClass('btn-default'); //если денег дали меньше чем ИТОГО
					$('#punchButton').addClass('btn-danger');
				}
			});

			$('#deleteOrderButton').removeAttr('disabled');
			$('#deleteOrderButton').on('click', function () {
				$.ajax({
					'url': '/paid/cashAct/DeleteOrderForm/paid_order_id/' + paid_order_id,
					'success': function (html) {
						if(html==='success')
						{ //после того, как удалили заказ.
							location.reload();
						}
					}
				});
			});
		}
	};
	
	/**
	 * Метод, используется когда нужно формировать заказ и счёт на оплату в хранилище
	 */
	var _createOrder=function() {
		$.ajax({
			'success': _callBackSuccessHandlerPunch,
			'data': arr, //отправляем codeService-doctorId связки
			'type': 'post',
			'url': '/paid/cashAct/orderForm'
		});
		price=0;	
	};
	
	/**
	 * Метод, когда заказ уже сформирован, нужно только
	 * навесить обработчики пробивки чека.
	 */
	var _prepareOrder = function() {
		var data={};
		data.expense_number=expense_number;
		$.ajax({
			"success": _callBackSuccessHandlerPunch,
			"url": '/paid/cashAct/prepareOrder',
			"data": data
		});
		price=0;
	};

	var _punch=function () { //private method
		$("#selectedServicesTable tbody .priceService").each(function () {
			price+=Number($(this).html());
		});
		if(price>0) {
			arr.orderForm={};
			var i=0;
			$('#selectedServicesTable tbody tr').each(function () {
				arr.orderForm[i]={};
				arr.orderForm[i].serviceId=$(this).find('.serviceId').html();
				arr.orderForm[i].doctorId=$(this).find('.doctorId').html();
				arr.priceSum=price;
				var url=document.location.href;
				var action=url.split('/');
				arr.patient_id=action[7]; //patient_id сохраняем в заказ
				i++;
			});
			if(!prepareOrder) //если заказ не подготовлен
			{
				_createOrder();
			}
			else
			{
				_prepareOrder();
			}
		}
		else if(price<=0) {
//					console.log('ERROR');
			$('#TotalSum').html('0'); //обнуляем ИТОГО
			$('#punchButton').attr('disabled', 'disabled');
			$('#punchButton').removeClass('btn-danger');
			$('#punchButton').addClass('btn-default');
			$('#CashSum').val('');
			$('#deleteOrderButton').attr('disabled', 'disabled');
			price=0;
		}
	};
	
	/**
	 * Обработчик клика переноса заказа во фронт кассира (no save in DB)
	 */
	var _callBackPrepareClick = function () {
		tbody=$('.gridChooseExpenseServices tbody').clone();
		if(tbody.length===0)
		{ //не найден
			alert('Услуги отсутствуют');
			return;
		}

		$('#selectedServicesTable tbody').remove();
		$("#selectedServicesTable table").append(tbody);
		$("#selectedServicesTable tbody tr .button-column").each(function () {
			$(this).remove();
		});

		expense_number=$('._expense_number').html();
		prepareOrder=true; //заказ уже был сформирован, создавать его не нужно.
		_punch(); //no save in DB

		//чистим и прячем открытые модали
		$('#modalSelectExpenseServicesBody').empty();
		$('#modalSelectExpenseServices').modal('hide');
		$('#modalSelectExpensesBody').empty();
		$('#modalSelectExpenses').modal('hide');
	};
	
	var _callBackCreateOrderClick=function () {
		$("#selectedServicesTable tbody").remove();
		tbody=$("#tableSelectionServices tbody").clone();
		$("#selectedServicesTable table").append(tbody);
		$("#selectedServicesTable tbody tr .b-paid__removeGrid").each(function () {
			$(this).remove();
		});
		prepareOrder=false; //нужно сохранять заказ прежде чем переносить его во фронт кассира
		_punch(); //формируем заказ и счёт в том числе.	
	};

	this.initHandlers=function () {
		
		$('#chooseNoPaidExpense').on('click', _callBackPrepareClick);
		$(document).on('click', "#selectedServicesConfirm", _callBackCreateOrderClick);	

		$(document).on('click', '.gridSelectServices tbody tr', function () {
			$.ajax({'success': function (html) {
					$('#modalSelectDoctorBody').html(html);
					$('#modalSelectDoctor').modal('show');
				},
					'url': '/paid/cashAct/chooseDoctor/code/' + $(this).find('.codeService').html()
			});

			$(document).off('selectedDoctor');

			obj=$(this);
			$(document).on('selectedDoctor', function () {
				i++;
				var objTr=obj.clone();
				var objTd=$('<td class="b-paid__removeGrid"><span class="b-paid__removeGridGl glyphicon glyphicon-remove" aria-hidden="true"></span></td>');
				objTr.append(doctorTdTag);
				objTr.append(objTd);
				$("#tableSelectionServices tbody").append(objTr);
				$("#tableSelectionServices tbody .empty").css("display", "none");

				objTd.on('click', function () {
					$(this).parent().detach();
					i--;
					if(i===0)
					{
						$("#tableSelectionServices tbody .empty").css("display", "table-row");
					}
				});
				$('#modalSelectDoctorBody').html('');
			});
		});

		$(document).on('click', '.gridChooseDoctor tbody tr', function () { //выбор врача
			var doctor=$(this).clone();
			var doctorLastName=doctor.find('.lastName').html();
			var doctorId=doctor.find('.doctorId').html(); //#ID доктора
			var doctorFirstName=doctor.find('.lastName').html().substr(0, 1) + '.';
			var doctorMiddleName=doctor.find('.middleName').html().substr(0, 1) + '.';

			doctorTdTag='<td>' + '<div class="doctorId">' + doctorId + '</div>' + doctorLastName + ' ' + doctorFirstName + doctorMiddleName + '</td>';

			$(document).trigger('selectedDoctor');
			$('#modalSelectDoctorBody').empty();
			$('#modalSelectDoctor').modal('hide');
		});

		$(document).on('mousedown', '.gridSelectServices tbody tr', function () {
			return false;
		}); //disabled select text

		$(document).on('selectstart', '.gridSelectServices tbody tr', function () {
			return false;
		}); //disabled select text for IE
	};

	this.handlerHiddenModal=function () {
		$('#modalSelectServices').on('hidden.bs.modal', function () {
			i=0; //обнуляем через замыкание
		});	
	};
}
/***********************************************************/
$(document).ready(function() {
	$.fn.modal.Constructor.prototype.enforceFocus = function() {}; //firefox fix focus modal+datetimepicker

	$('#Patient_Contacts_value').inputmask("mask", {"mask": "+7 (999) 999-99-99"});
	$('#Patients_birthday').inputmask("mask", {"mask": "9999-99-99"});
	$('#Patients_snils').inputmask("mask", {"mask": "999-999-999-99"});
	
	var inputMaskComplete=function () {
		var totalSum=$('#TotalSum').html(); //взяли сумму "ИТОГО"
		if(totalSum>0)
		{ //если ИТОГО существует
			var oddMoney=$(this).val() - totalSum;
			if(Number(oddMoney)===oddMoney)
				$('#oddMoney').html(oddMoney.toFixed(2)); //формируем сдачу
		}
	}; //используется в .inputmask, формирует сдачу пациента.
	
	$('#CashSum').inputmask("9{2,9}.9{2}", {"oncomplete": inputMaskComplete});
	
//	function classOrderForm() {
//		
//	}
	
	var selectServices=new classSelectServices();
	selectServices.initHandlers();
	selectServices.handlerHiddenModal();
	
//	function classPunchCheck() { //пробивка чека
//		var price=0;
//		this.visiblePunchButton=function () { //включаем кнопку пробивки чека, если выбраны услуги и у них есть цена >0
//			$(document).on('click', "#selectedServicesConfirm", function () {//TODO REFACTOR
//				$("#selectedServicesTable tbody .priceService").each(function () {
//					price+=Number($(this).html());
//				});
//				if(price>0) {
//					$('#punchButton').removeProp('disabled');
//					var arr={}; //замыкание для punchButton
//					arr.punchButton={};
//					var i=0;
//					$('#selectedServicesTable tbody tr').each(function () {
//						arr.punchButton[i]={};
//						arr.punchButton[i].code=$(this).find('.codeService').html();
//						arr.punchButton[i].doctorId=$(this).find('.doctorId').html();
//						i++;
//					});
//					$.ajax({'success': function (html) {
//								
//							},
//							'data': arr, //отправляем codeService-doctorId связки
//							'type': 'post',
//							'url': '/paid/cashAct/punch'
//					});
//					price=0;
//				}
//				else if(price<=0) {
//					$('#punchButton').attr('disabled', 'disabled');
//					price=0;
//				}
//			});
//		};
//	}
	
    //for reload page
    (function() {
        var url=document.location.href;
        var action=url.split('/');
        $(".b-paidNav__li").each(function() {
            $(this).removeClass('active');
        });
		$(".b-paidNav__li").each(function () {
			var nameHref=$(this).children(".b-paidNav__href").attr("href");
			if(action[5]=='patient')
			{ //первое вхождение
				$(this).addClass('active');
				$('.paidActWidget input').removeAttr('disabled'); //разблокировка act-кнопок
//				$('.b-paid__summHeader').css('color', 'black');
				return false;
			}
            else if(nameHref.indexOf(action[5])!==-1)
            {
				$(this).addClass('active');
				return false;
            }
		});
    })();
    (function() { //for groups
        var url=document.location.href;
        var action=url.split('/');
        var group_id=action[7];
        $('.b-paid__serviceItemGroup').each(function() {
            $(this).removeClass('active');
            var nameHref=$(this).children('.b-paid__serviceItemGroup-b_color').children('.b-paid__addPopover').attr('value');

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
		var group_id = null;
        this.AddService=function () { //add to onclick, modal for add service
            $.ajax({'success': function (html) {
						$('#modalServiceGroupsBody').html(html);
						$('#modalServiceGroups').modal('show');
						$('#Paid_Services_price').inputmask("9{2,9}.9{2}");
		//					$("#Paid_Services_paid_service_group_id").attr('value', this.valueP_id);
					},
					'url': '/paid/cash/addService/group_id/' + group_id
            });
        };
	
        this.AddGroup=function () { //add to onclick, 
			$.ajax({'success': function (html) {
						$('#modalServiceGroupsBody').html(html);
						$('#modalServiceGroups').modal('show');
//				$("#Paid_Service_Groups_p_id").attr('value', this.valueP_id);
					},
						'url': '/paid/cash/addGroup/group_id/' + group_id
			});
        };
	
        this.buttonAddGroup=function () { //add to onclick
			$.ajax({'success': function (html) {
						$('#modalServiceGroupsBody').html(html);
						$('#modalServiceGroups').modal('show');
					},
						'url': '/paid/cash/addGroup/group_id/0'
			});
        };
	
        this.UpdateGroup=function () {
			$.ajax({'success': function (html) {
						$('#modalServiceGroupsBody').html(html);
						$('#modalServiceGroups').modal('show');
//				$("#Paid_Service_Groups_p_id").attr('value', this.valueP_id);
					},
						'url': '/paid/cash/updateGroup/group_id/' + group_id
			});			
        };
	
        this.DeleteGroup=function () {
			if(!confirm('Вы уверены, что хотите удалить данный элемент?')) {
				return false;
			}
			$.ajax({'url': '/paid/cash/deleteGroup/group_id/' + group_id,
						'success': function (error) {
								if(error==0) {
									alert('Удаление группы невозможно, т.к. у данной группы (или её подгрупп) присутствуют услуги или врачи.');
								} 
								else if(error==1) {
									location.href='/paid/cash/groups';
								}
						}
					});
        };
        
        this.initHandlers=function () { //add popover bootstrap for add service/group
            $(".b-paid__addPopover").on('click', function () {
                group_id=$(this).attr('value');
            });
            $(".b-paid__addEditPopover").on('click', function () {
                   group_id=$(this).attr('value');
            });
            $(".b-paid__addPopover").popover({
                title : 'Выберите действие',
                trigger: 'focus',
                html: true,
                content:'<button class="btn btn-block btn-primary btn-xs" id="popoverButtonAddService">Добавить услугу</button>\n\
                                 <button class="btn btn-block btn-primary btn-xs" id="popoverButtonAddGroup">Добавить подгруппу</button>'
            });
            $(".b-paid__addEditPopover").popover({
                title : 'Выберите действие',
                trigger: 'focus',
                html: true,
                content:'<button class="btn btn-block btn-primary btn-xs" id="popoverButtonEditGroup">Редактировать группу</button>\n\
                                 <button class="btn btn-block btn-primary btn-xs" id="popoverButtonDeleteGroup">Удалить группу</button>'
            });
			$(document).on('click', '#popoverButtonAddService', this.AddService);
            $(document).on('click', '#popoverButtonAddGroup', this.AddGroup);
            $(document).on('click', '#buttonAddGroup', this.buttonAddGroup);
            $(document).on('click', '#popoverButtonEditGroup', this.UpdateGroup);
            $(document).on('click', '#popoverButtonDeleteGroup', this.DeleteGroup);
        };
        
        this.handlerHiddenModal=function () {
            $('#modalServiceGroups').on('hidden.bs.modal', function () {
                $('#Paid_Services_since_date').unbind();
                $('#Paid_Services_exp_date').unbind();
                $('#ui-datepicker-div').detach();
                $('#modalServiceGroupsBody').html('');
            });
        };
    }
    
    function searchPatient()
    {
        this.handlerHiddenModal=function () {
            $('#modalSearchPatient').on('hidden.bs.modal', function () {
                $('#modalSearchPatientBody').html('');
            });
        };
		this.handlerSearchPatient=function () {
			$(document).on('click', '#submitSearchPatient', function () {
				$(this).val('Загрузка..');
				$(this).animate({opacity: 0.6}, 250);
				$(this).parent().attr('name', 'search');
				$('#submitCreatePatient').css('display', 'none');
				$('#submitCreatePatient').animate({opacity: 0}, 250);
			});
		};
        this.handlerCreatePatient=function () {
            $(document).on('click', '#gridCreatePatient', function () {
                $('#submitCreatePatient').css('display', 'inline-block');
                $('#submitCreatePatient').animate({opacity: 1}, 250);
		$('#submitCreatePatient').parent().attr('name', 'create');
                $('#modalSearchPatient').modal('hide');
            });
        };
    }
    var modelServiceGroups=new modelServiceGroups();
    var searchPatient=new searchPatient();
    modelServiceGroups.initHandlers();
    modelServiceGroups.handlerHiddenModal();
    
    searchPatient.handlerHiddenModal();
  
    searchPatient.handlerCreatePatient();
    
    searchPatient.handlerSearchPatient();
    
//    $('#paid_cash_index-modalSearchGrid').on('show.bs.modal', function(e){ //просто при нажатии
//        $(".b-paid__errorFormPatient").html('');
//        $(".b-paid__errorFormPatient").css('display', 'none');
//    });
//
//    $('#paid_cash_index-modalSearchGrid').on('hidden.bs.modal', function (e){
//        $("#paid_cash_index-modalSearchGridBody").html(''); //clean
//        $(document).off('click.yiiGridView', $.fn.yiiGridView.settings['paid_cash_search-gridPatients'].updateSelector);
//        $(document).off('click', '#paid_cash_search-gridPatients a.btn.btn-default.btn-block.btn-xs');
//    });
//
//    $(document).on('click', '#add_paid_modal_patient', function(e){ //добавление пациента из модали
//        $('#paid_cash_index-modalSearchGrid').modal('hide');
//        $('#add_paid_patient_button').css('display', 'inline-block');
//        $('#add_paid_patient_button').animate({opacity: 1}, "slow");
////		$('#add_paid_patient_input').css('display', 'inline'); //добавляем hidden input
//    });
});