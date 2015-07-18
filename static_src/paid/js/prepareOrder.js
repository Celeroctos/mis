/**
 * @version 1.0
 * @module prepare order
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
		var parseUrl = url.split('/');
		
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
					
					modelOrder.push({'serviceId': serviceId, 'doctorId': doctorId}); // заполняем заказ
					
					$('#modalSelectDoctors').modal('hide');
					
					thisService.css('opacity', 0);
					tagRemove = $('<td class="b-paid__removeGrid"><span class="indexOrder">' + indexOrder + '</span><span class="b-paid__removeGridGl glyphicon glyphicon-remove" aria-hidden="true"></span></td>');
					thisService.append('<td><div class="doctorId">' + doctorId + '</div>' + lastName + ' ' + firstName + ' ' + middleName + '</td>').append(tagRemove);
					$('#tablePrepareOrderServices tbody').append(thisService);
					thisService.animate({opacity: 1}, 200);
//					indexOrder++; // следующая строка заказа
					tagRemove.on('click', function () {
						
						thisService.animate({opacity: 0}, 150, function () {
							
							var index=0;
							var j=0;
							var modelTemp=[];
							
							for(var i=0; i<modelOrder.length; i++) {
								if(modelOrder[i]!==undefined) { // алгоритм обновления массива заказанных услуг при удалении из него услуги
									if(modelOrder[i].serviceId!==serviceId && modelOrder[i].doctorId!==doctorId && j===0) {
										modelTemp[index]=modelOrder[i];
										j++; // аналог DISTINCT
										index++;
									}
								}
							}
							
							modelOrder=modelTemp;
							thisService.detach();
			
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

				/**
				 * @callback
				 * @param {Number} id #ID заказа
				 */
				function ajaxOrderSuccess(id) {
					orderId=id;
					scenario = 0; // перевод на редактирование
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
			
			/**
			 * Начинаем подготовку услуг для заказа.
			 */
			$(document).on('click ', '#beginPrepareOrder', loadServicesModal);
			
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