function JournalController () {
	
	/**
	 * ajax success method.
	 * Insert into Yii ajaxSubmitButton.
	 * @param {String} html
	 */
	this.ajaxSearch = function (html) {
		$('.b-content__journal').animate({opacity: 0.4}, 300, function () {
			$('.b-content').html(html);
		});
	};
	
	/**
	 * @private
	 */
	var expense_number;
	
	/**
	 * Функция навешивания обработчика на строку таблицы в журнале.
	 * @private
	 */
	function gridRowOnHandler() {
		
		/**
		 * @callback
		 * @param {String} html
		 */
		function success(html) {
			$('#modalSelectJournalRowBody').html(html);
			$('#printExpenseJournal').on('click', function () {
				$.ajax({
					url: '/paid/journal/chooseRow/expense_number/' + expense_number + '/isPrint/1',
					success: function (paid_order_id) {
						window.open('/paid/cashAct/printExpense/paid_order_id/' + paid_order_id, '', 'location=no, titlebar=no, toolbar=no, directories=no, width=640px, height=480px, top=250px, left=380px;');
					}
				});
			});
			$('#modalSelectJournalRow').modal('show');
		}
		
		/**
		 * Обработка нажатия на строку таблицы в журнале.
		 */
		$(document).on('click', '.gridJournalExpenses tbody tr', function () {
			expense_number = $(this).find('.expense_number').html();
			$.ajax({
				url: '/paid/journal/chooseRow/expense_number/' + expense_number,
				success: success,
				method: 'post'
			});
		});
	}
	
	/**
	 * @private
	 * @callback
	 * @param {String} html
	 */
	function ajaxSuccess(html) {
		$('.b-content').html(html);
	};
	
	/**
	 * Метод загрузки экшна
	 * @private
	 * @callback
	 * @param {Event} event
	 */
	function loadPage (event) {
		var idElem = $(this).attr('id');
		$('.b-content__journal').animate({opacity: 0.4}, 200, function () {
			$.ajax({
				url: '/paid/journal/' + event.currentTarget.id,
				success: ajaxSuccess,
				method: 'post',
				complete: function () {
					$('#' + idElem).addClass('active');
				}
			});
		});
		return false;
	};
	
	this.handlerAllExpenses = function () {
		$(document).on('click', '#allExpenses', loadPage);
	};
	
	this.handlerNotPaidExpenses = function () {
		$(document).on('click', '#notPaidExpenses', loadPage);
	};
	
	this.handlerPaidExpenses = function () {
		$(document).on('click', '#paidExpenses', loadPage);
	};
	
	this.handlerPaidReturn = function () {
		$(document).on('click', '#paidReturnExpenses', loadPage);
	};
	
	this.init = function () {
		$(document).ready(function () {
			$('#allExpenses').addClass('active');
			gridRowOnHandler();
		});
		this.handlerAllExpenses();
		this.handlerNotPaidExpenses();
		this.handlerPaidExpenses();
		this.handlerPaidReturn();
	};
}
//JournalController.prototype = new Controller;

var journal=new JournalController();
journal.init(); // грузим экшн