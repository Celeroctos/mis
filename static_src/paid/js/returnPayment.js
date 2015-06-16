function ReturnPaymentController() {

	/**
	 * ajax success method.
	 * Insert into Yii ajaxSubmitButton.
	 * @this $.ajax({}).
	 * @param {String} html
	 */
	this.ajaxSearch = function (html) {
		$('.modalOverlayReturnPayment').animate({opacity: 0.3}, 300, function () {
			$('#Paid_Expenses_date').inputmask("mask", {"mask": "9999-99-99"});
			$('#Paid_Expenses_dateEnd').inputmask("mask", {"mask": "9999-99-99"});
			$('#modalReturnPaymentBody').html(html);
			$('.modalOverlayReturnPayment').animate({opacity: 1}, 200);
		});
	};
	
	var cleanCashFront = function () {
		$('#TotalSum').html('0'); //обнуляем ИТОГО
		$('#punchButton').prop('disabled', 'false');
		$('#punchButton').removeClass('btn-danger');
		$('#punchButton').addClass('btn-default');
		$('#CashSum').val('');
		$('#deleteOrderButton').attr('disabled', 'disabled');
		$('#selectedServicesTable tbody').empty();
		$('#selectedServicesTable tbody').append('<tr class="empty"><td colspan="7"><span>Пусто</span></td></tr>');
	};
	
	/**
	 * CallBack
	 * load bootstrap modal, if successful
	 */
	var loadModal = function () {
		
		/**
		* Ajax success method (первое модальное окно)
		* @param {mixed} result
		*/
		var ajaxSuccess = function (result) {
			$('#modalReturnPaymentBody').html(result);
			$('#modalReturnPayment').modal('show');
		};
		var url=document.location.href;
		var action=url.split('/');
		$.ajax({
			url: '/paid/cashAct/ReturnPayment/patient_id/' + action[7],
			success: ajaxSuccess
		});
	};
	
	/**
	 * Callback
	 * confirm return payment
	 */
	var returnPaymentConfirm = function () {
		
		/**
		 * @var {Number}
		 */
		var expense_number = $(this).find('.expense_number').html();
		
		/**
		 * @var {Float}
		 */
		var price = $(this).find('.price').html();
		
		$('#modalReturnPaymentConfirm .price').html(price); //чистим цену
		$('#modalReturnPaymentConfirm').modal('show');
		
		$('#returnPaymentConfirm').on('click', function () {
			
			/**
			 * GridView id
			 * @var {Number}
			 */
			var gridId=$('#modalReturnPayment').find('.grid-view').prop('id');
			
			/**
			 * ajax success method
			 */
			var ajaxSuccess = function (result) {
				$('#modalReturnPaymentConfirm').modal('hide');
			};
	
			$.ajax({
				url: '/paid/cashAct/ReturnPaymentConfirm/expense_number/' + expense_number,
				success: ajaxSuccess,
				complete: function () {
					$('#' + gridId).yiiGridView('update');
				}
			});
		});
	};
	
	this.handlerButton = function () {
		
		/**
		 * Загрузка модали по нажатию на кнопку "Возврат оплаты"
		 */
		$(document).on('click', '#returnPayment', loadModal);
		
		/**
		 * Обработка нажатий на строку грида в самой модали
		 */
		$(document).on('click', '.gridReturnPayment tbody tr', returnPaymentConfirm);
	};
	
	this.handlerModal = function () {
		
		/**
		 * Чистим модаль после её скрытия
		 */
		$(document).on('hidden.bs.modal', '#modalReturnPayment', function () {
			$('#modalReturnPaymentBody').empty();
		});

		/**
		 * Операции с модалью после её скрытия
		 */
		$(document).on('hidden.bs.modal', '#modalReturnPaymentConfirm', function () {
			$('#returnPaymentConfirm').off('click');
			$('#modalReturnPaymentConfirm .price').empty(); //чистим цену
		});
		
		/**
		 * Чистим фронт кассира перед тем, как пользователь увидит модаль
		 */
		$(document).on('show.bs.modal', '#modalReturnPayment', function () {
			cleanCashFront();
		});
	};
	
	this.init = function () {
		this.handlerButton();
		this.handlerModal();
	};
}
ReturnPaymentController.prototype = new Controller;

var returnPayment=new ReturnPaymentController();
returnPayment.init();