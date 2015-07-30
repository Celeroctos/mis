/**
 * @version 1.0
 * @module prepare order
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
(function() {
	/**
	 * Creates a new prepareOrder.
	 * @class
	 */
	function PrepareOrder() {
		
		/**
		 * URL страницы.
		 */
		var url = document.location.href;
		
		/**
		 * Парсим url, для дальнейшего извлечения patient_id
		 */
		var parseUrl = url.split('/'); // parseUrl[7] = patientId
		
		/**
		 * Возможные сценарии: создание и редактирование (1 и 0 соответственно).
		 * @default
		 */
		var scenario = 1;
		
		/**
		 * Заказ.
		 */
		var modelOrder = [];
		
		/**
		 * 
		 * @var
		 * @type Number
		 */
		var indexOrder = 0;
		
		/**
		 * #ID сфоромированного заказа.
		 * @type Number
		 */
		var orderId = -1;
		
		/**
		 * Общая сумма заказа
		 * @type Number
		 */
		var totalSum = 0;
		
		/**
		 * Номер счёта
		 * @type Number
		 */
		var expenseNumber;

		function deleteOrder() {
			$.ajax({
				url: '/paid/cashAct/DeleteOrderForm/paid_order_id/' + orderId,
				method: 'get',
				success: function () {
					location.reload();
				}
			});
		}
		
		/**
		 * "Итого"
		 * @private
		 */
		function setTotalSum() {
			$.ajax({
				url: '/paid/cashAct/GetTotalSum/order_id/' + orderId,
				success: function (html) {
					$('#TotalSum').text(html);
					totalSum=$('#TotalSum').text();
					$('#CashSum').val('');
					$('#oddMoney').text('');
				}
			});
		}
		
		/**
		 * @callback
		 * @private
		 * Пробить чек
		 */
		function punch() {
			
			var cashSum = parseFloat($('#CashSum').val());
			var totalSum = parseFloat($('#TotalSum').text());

			if(orderId>0 && !isNaN(cashSum) && !isNaN(totalSum) && cashSum>=totalSum) {
				
				$('#punchButton').removeClass('btn-danger');
				$('#punchButton').addClass('btn-default');
				
				function ajaxSuccessJsonReferrals(jsonResponse) {
					var referrals=$.parseJSON(jsonResponse);
					for(var i=0; i<referrals.length; i++) { // печатаем все направления
						window.open('/paid/cashAct/printReferral/paid_referral_id/' + referrals[i], '', 'location=no, titlebar=no, toolbar=no, directories=no, width=640px, height=480px, top=250px, left=380px;');
					}
					location.reload();				
				}

				$.ajax({
					url: '/paid/cashAct/punch/paid_order_id/' + orderId + '/patient_id/' + parseUrl[7],
					success: ajaxSuccessJsonReferrals
				});
			} else {
				$('#punchButton').removeClass('btn-default');
				$('#punchButton').addClass('btn-danger');
			}
		}
		
		/**
		 * Обработка события click на кнопку "Выбрать счёт"
		 * @callback
		 * @private
		 */
		function loadExpensesModal() {
			
			/**
			 * ajax success method
			 * @callback
			 */
			function ajaxSuccess(html) {
				$('#modalSelectExpensesBody').html(html);
				$('#Paid_Expenses_date').inputmask("mask", {"mask": "9999-99-99"});
				$('#Paid_Expenses_dateEnd').inputmask("mask", {"mask": "9999-99-99"});
				$('#modalSelectExpenses').modal('show');				
			}
			
			$.ajax({
				url: '/paid/cashAct/ChooseExpenses/patient_id/' + parseUrl[7], // parseUrl[7] == patientId
				success: ajaxSuccess
			});
		}
		
		/**
		 * This callback uses in handler onclick.
		 * @private
		 * @callback
		 */
		function loadServicesModal() {
			
			/**
			 * При нажатии на строку таблицы.
			 * @this onclick elem service
			 */
			function selectedService() {
				
				/**
				 * Сохраняем состояние контекста.
				 * @var
				 * @type jQuery
				 */
				var thisService = $(this).clone();
				
				/**
				 * <td></td> тег, на удаление строки таблицы.
				 * @type jQuery
				 */
				var tagRemove;
				
				/**
				 * Код выбранной услуги.
				 * @var
				 */
				var codeService = $(this).find('.codeService').html();
				
				/**
				 * #ID услуги
				 * @var
				 */
				var serviceId = $(this).find('.serviceId').html();
				
				/**
				 * При нажатии на строку таблицы докторов.
				 * @this onclick elem doctor
				 */
				function selectedDoctor() {
					
					/**
					 * ID выбранного доктора.
					 * @var
					 */
					var doctorId = $(this).find('.doctorId').html();
					
					/**
					 * Сохраняем контекст.
					 * @var
					 */
					var thisDoctor = this;
					
					/**
					 * Фамилия доктора.
					 * @var
					 */
					var lastName = $(thisDoctor).find('.lastName').text();
					
					/**
					 * Имя доктора.
					 * @var
					 */
					var firstName = $(thisDoctor).find('.firstName').text();
					
					/**
					 * Отчество доктора.
					 * @var
					 */
					var middleName = $(thisDoctor).find('.middleName').text();
					
					modelOrder.push({serviceId: serviceId, doctorId: doctorId}); // заполняем заказ
					indexOrder++;
					$('#modalSelectDoctors').modal('hide');
					
					thisService.css('opacity', 0);
					tagRemove = $('<td class="b-paid__removeGrid"><span class="b-paid__removeGridGl glyphicon glyphicon-remove" aria-hidden="true"></span></td>');
					thisService.append('<td>' + lastName + ' ' + firstName + ' ' + middleName + '</td>').append(tagRemove);
					$('#tablePrepareOrderServices tbody').append(thisService);
					thisService.animate({opacity: 1}, 200);
					tagRemove.on('click', function () {
						thisService.animate({opacity: 0}, 150, function () {
							
							var index=0;
							var j=0;
							var modelTemp=[];
							for(var i=0; i<modelOrder.length; i++) {
								if(modelOrder[i].serviceId==serviceId && modelOrder[i].doctorId==doctorId && j===0) { //пропускаем и не добавляем на кликнутый элемент
									j++; // если есть повторяющиеся кортежи (1-1, 1-1). Удаляем только первый.
									continue;
								}
								modelTemp[index]={};
								modelTemp[index].serviceId=modelOrder[i].serviceId;
								modelTemp[index].doctorId=modelOrder[i].doctorId;
								index++;
							}
							modelOrder=modelTemp; // обновление модели заказа
//							console.log(modelOrder);
							thisService.detach();
							indexOrder--;
						});
					});
				}
				
				/**
				 * @callback
				 * @param {String} html
				 */
				function ajaxSuccess(html) {
					$('#modalSelectDoctorsBody').html(html);
					$('#modalSelectDoctors').modal('show');
					
					/**
					 * Нажатие на строку грида с докторами.
					 */
					$(document).on('click', '#modalSelectDoctorsBody .gridSelectDoctors tbody tr', selectedDoctor);
				}
				
				$.ajax({
					url: '/paid/cashAct/SelectDoctors/code/' + codeService,
					success: ajaxSuccess
				});
			}
			
			/**
			 * Сформировать заказ.
			 */
			function confirmPrepareOrder() {
				
				if(indexOrder<=0) {
					alert('Выберите хотя бы одну услугу!');
					return;
				}
				
				/**
				 * @callback
				 * @param {Number} id #ID заказа
				 */
				function ajaxOrderSuccess(id) {
					orderId=id;
					setTotalSum();
					scenario = 0; // перевод на редактирование
					
					var tableOrder=$('#tablePrepareOrderServices').clone();
					$('#selectedServicesTable').empty();
					$('#selectedServicesTable').html('<table class="table table-bordered table-striped"></table>');
					$('#selectedServicesTable').find('table').append(tableOrder.find('thead'));
					$('#selectedServicesTable').find('table').append(tableOrder.find('tbody'));
					
					window.open('/paid/cashAct/printExpense/paid_order_id/' + orderId, '', 'location=no, titlebar=no, toolbar=no, directories=no, width=640px, height=480px, top=250px, left=380px;');
					window.open('/paid/cashAct/printContract/order_id/' + orderId, '', 'location=no, titlebar=no, toolbar=no, directories=no, width=640px, height=480px, top=250px, left=380px;');		
					
					$('#punchButton').prop('disabled', false);
					$('#deleteOrderButton').prop('disabled', false);
				}
				
				var urlAjax;

				// заполняем orderId после AJAX-запроса if(scenario) создаем заказ или редактируем
				$('#modalSelectServices').modal('hide');
				$('#beginPrepareOrder').attr('value', 'Редактировать');

				if(scenario===0) { // если мы перешли на редактирование, то нужно указать заказ для его удаления, чтобы добавить другой
					urlAjax = '/paid/cashAct/orderForm/scenario/' + scenario + '/order_id/' + orderId;
				} else { // если сформировали заказ в первый раз (scenario == 1)
					urlAjax = '/paid/cashAct/orderForm/scenario/' + scenario;
				}

				$.ajax({
					url: urlAjax,
					data: {orderForm: modelOrder, patientId: parseUrl[7]},
					success: ajaxOrderSuccess,
					method: 'post'
				});
			}
			
			/**
			 * @callback
			 * @param {String} html
			 */
			function ajaxSuccess(html) {
				
				/**
				 * Заполнение модали контентом.
				 */
				$('#modalSelectServicesBody').html(html);
				
				/**
				 * Вывод модали.
				 */
				$('#modalSelectServices').modal('show'); // z-index: 1040 (default)
				
				/**
				 * Обновление грида при нажатии на "Применить фильтр".
				 */
				$('#selectServicesFilter').on('click', function () {
					$("#modalSelectServicesBody .grid-view").yiiGridView("update", {data: $("#modalSelectServicesBody form").serialize()});
				});
				
				/**
				 * Чистка фильтра поиска в гриде при нажатии на "Очистить".
				 */
				$('#cleanSelectServicesFilter').on('click', function () {
					$('input[name="Paid_Services[code]"]').val('');
					$('input[name="Paid_Services[name]"]').val('');
					$('input[name="Paid_Service_Groups[name]"]').val('');
					$("#modalSelectServicesBody .grid-view").yiiGridView("update", {data: $("#modalSelectServicesBody form").serialize()});
				});
				$(document).on('click', '#modalSelectServicesBody .gridSelectServices tbody tr', selectedService);
				$(document).on('click', '#confirmPrepareOrder', confirmPrepareOrder);
				
				// завершаем раскрытие модального окна. Переводим состояние в режим редактирования.
			};

			if(scenario===0) { // заказ уже был создан, его нужно просто отредактировать
				$('#confirmPrepareOrder').text('Редактировать заказ');
				$('#modalSelectServices').modal('show'); // z-index: 1040
			} else { // иначе создаём заказ
				hiddenServicesModal(); // удаляем обработчики, т.к. не сформировали заказ ещё.
				$.ajax({
					url: '/paid/cashAct/SelectServices',
					method: 'post',
					success: ajaxSuccess
				});
			}
		}
		
		/**
		 * @callback
		 */
		function loadPrepareOrder() {
			
			expenseNumber = $('._expense_number').text();
			scenario = 1; // сбрасываем если было редактирование на "создание"
			
			$.ajax({
				url: '/paid/cashAct/PrepareOrder/expense_number/' + expenseNumber,
				method: 'get',
				success: function (localOrderId) {
					orderId=localOrderId;
					
					if(orderId<=0) {
						alert('В счёте отсутствуют услуги!');
						return;
					}
					setTotalSum();
					
					var tableOrder=$('.gridChooseExpenseServices').clone();
					$('#selectedServicesTable').empty();
					$('#selectedServicesTable').html('<table class="table table-bordered table-striped"></table>');
					$('#selectedServicesTable').find('table').append(tableOrder.find('thead'));
					$('#selectedServicesTable').find('table').append(tableOrder.find('tbody'));
				
					$('#beginPrepareOrder').attr('value', 'Выбрать услуги');
					$('#modalSelectExpenses').modal('hide');
					$('#modalSelectExpenseServices').modal('hide');
					
					$('#punchButton').prop('disabled', false);
					$('#deleteOrderButton').prop('disabled', false);
				}
			});
		}
		
		/**
		 * to turn off events
		 * @callback
		 * @private
		 */
		function hiddenServicesModal() {
			if(scenario===1) { // если не был сформирован заказ, то модаль сбрасывается.
				$('#selectServicesFilter').off('click');
				$('#cleanSelectServicesFilter').off('click');
				$(document).off('click', '#modalSelectServicesBody .gridSelectServices tbody tr');
				$(document).off('click', '#confirmPrepareOrder');
				$('#modalSelectServicesBody').empty();
				modelOrder=[]; // чистим заказ.
				indexOrder=0;
			}
		}
		
		function hiddenDoctorsModal() {
			$('#modalSelectDoctorsBody').empty();
			$(document).off('click', '#modalSelectDoctorsBody .gridSelectDoctors tbody tr');
		}
		
		/**
		 * Initialize handlers and etc
		 * @function
		 */
		this.initialize = function() {
			
			var i = 0;
			$(document).ready(function () {
				$('#CashSum').inputmask({"mask": "[9{2,9}][.99]", greedy: false});
				$('#CashSum').on('blur', function () {
					var cashSum = parseFloat($(this).val());
					var localTotalSum = parseFloat(totalSum);
					
					var odd = cashSum.toFixed(2) - localTotalSum.toFixed(2);
					odd=odd.toFixed(2);
					if(!isNaN(odd)) {
						$('#oddMoney').text(odd);
					} else {
						$('#oddMoney').text('');
					}
				});
			});
					
			
			if(parseUrl[7]==='patient') {
				$('#beginPrepareOrder').prop('disabled', false);
			}
			
			/**
			 * Начинаем подготовку услуг для заказа.
			 */
			$(document).on('click ', '#beginPrepareOrder', loadServicesModal);
			
			/**
			 * Нажатие на кнопку "выбрать счёт". Сюда попадают уже подготовленные счета.
			 */
			$(document).on('click', '#beginPrepareExpense', loadExpensesModal);
			
			/**
			 * Выбранный счёт
			 */
			$(document).on('click', '#confirmExpenseOrder', loadPrepareOrder);
			
			/**
			 * Пробивка чека, кнопку надо разблокировать
			 */
			$(document).on('click', '#punchButton', punch);
			
			/**
			 * Отмена заказа
			 */
			$(document).on('click', '#deleteOrderButton', deleteOrder);
			
			/**
			 * Нажатие на кнопку "К поиску"
			 */
			$(document).on('click', '#searchTransitionButton', function () {
				if(orderId>0) {
					$('#transitionSearch').modal('show');
					return false;
				}
			});
			
			/**
			 * Отключаем все обработчики при скрытии модали.
			 */
//			$(document).on('hidden.bs.modal', '#modalSelectServices', hiddenServicesModal);
			$(document).on('hidden.bs.modal', '#modalSelectDoctors', hiddenDoctorsModal);
		};	
	}
	var prepareOrder=new PrepareOrder();
	prepareOrder.initialize();
})();