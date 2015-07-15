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
				 * Сохраняем состояние контекста
				 * @var
				 * @type jQuery
				 */
				var thisService = $(this).clone();
				
				/**
				 * Код выбранной услуги
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
					 * ID выбранного доктора
					 * @var
					 */
					var doctorId = $(this).find('.doctorId').html();
					
					/**
					 * Сохраняем контекст
					 * @var
					 */
					var thisDoctor = this;
					
					/**
					 * Фамилия доктора
					 * @var
					 */
					var lastName = $(thisDoctor).find('.lastName').text();
					
					/**
					 * Имя доктора
					 * @var
					 */
					var firstName = $(thisDoctor).find('.firstName').text();
					
					/**
					 * Отчество доктора
					 * @var
					 */
					var middleName = $(thisDoctor).find('.middleName').text();
					
					modelOrder[indexOrder]=[serviceId, doctorId]; // заполняем заказ
					indexOrder++;
					
					$('#modalSelectDoctors').modal('hide');
					
					thisService.css('opacity', 0);
					thisService.append('<td>' + lastName + ' ' + firstName + ' ' + middleName + '</td><td class="b-paid__removeGrid"><span class="indexOrder">' + indexOrder +'</span><span class="b-paid__removeGridGl glyphicon glyphicon-remove" aria-hidden="true"></span></td>');
					$('#tablePrepareOrderServices tbody').append(thisService);
					thisService.animate({opacity: 1}, 200);
					
					thisService.on('click', function () {
						
						$(this).animate({opacity: 0}, 150, function () {
							$(this).detach();
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
			 * @callback
			 * @param {String} html
			 */
			function ajaxSuccess(html) {
				
				/**
				 * Сформировать заказ
				 */
				function confirmPrepareOrder() {
					
				}
				
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
				scenario = 0; // перешли в режим редактирования
			};

			if(scenario===0) { // заказ уже был создан, его нужно просто отредактировать.
				$('#modalSelectServices').modal('show'); // z-index: 1040 (default)
			} else { // иначе создаём заказ.
				/**
				 * ajax request.
				 */
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
//		function hiddenServicesModal() {
//			$('#selectServicesFilter').off('click');
//			$('#cleanSelectServicesFilter').off('click');
//			$(document).off('click', '#modalSelectServicesBody .gridSelectServices tbody tr');
//			$('#modalSelectServicesBody').empty();
//		}
		
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