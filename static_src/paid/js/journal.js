function JournalController () {
	
	/**
	 * ajax success method.
	 * Insert into Yii ajaxSubmitButton.
	 * @this $.ajax({}).
	 * @param {String} html
	 */
	this.ajaxSearch = function (html) {
		var url=this.url.split('/');
		$('.b-content__journal').animate({opacity: 0.4}, 300, function () {
			$('.b-content').html(html);
				var obj=$('.b-paidNavJ').find('#' + url[3]);
				obj.addClass('active');
				addInputDateMask();
		});
	};
	
	/**
	 * @private
	 */
	var expense_number;
	
	/**
	 * @param {JSON} json_referrals
	 */
	function printReferrals(json_referrals) {
		var referrals=$.parseJSON(json_referrals);
		
		for(var i=0; i<referrals.length; i++) {
			window.open('/paid/cashAct/printReferral/paid_referral_id/' + referrals[i], '', 'location=no, titlebar=no, toolbar=no, directories=no, width=640px, height=480px, top=250px, left=380px;');
		}
	}
	
	/**
	 * Функция навешивания обработчика на строку таблицы в журнале.
	 * @private
	 */
	function gridRowOnHandler() {
		/**
		 * @callback
		 * @param {String} html
		 */
		function successPrintExpense(html) {
			$('#modalSelectJournalRowBody').html(html);
			
			$('#printExpenseJournal').on('click', function () {
				$.ajax({
					url: '/paid/journal/chooseRow/expense_number/' + expense_number + '/isPrint/1',
					success: function (paid_order_id) {
						window.open('/paid/cashAct/printExpense/paid_order_id/' + paid_order_id, '', 'location=no, titlebar=no, toolbar=no, directories=no, width=640px, height=480px, top=250px, left=380px;');
					}
				});
			});
			
			$('#printReferralsJournal').on('click', function () {
				$.ajax({
					url: '/paid/journal/returnReferrals/expense_number/' + expense_number,
					success: function (json_referrals) {
						printReferrals(json_referrals);
					} 
				});
			});
			
			$('#printContractJournal').on('click', function () {
				$.ajax({
					url: '/paid/journal/returnOrder/expense_number/' + expense_number,
					success: function (paid_order_id) {
						window.open('/paid/cashAct/printContract/order_id/' + paid_order_id, '', 'location=no, titlebar=no, toolbar=no, directories=no, width=640px, height=480px, top=250px, left=380px;');
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
				success: successPrintExpense,
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
					addInputDateMask();
				}
			});
		});
		return false;
	};
	
	/**
	 * Add inputmask for input fields
	 * @private
	 */
	function addInputDateMask() {
		$('#Paid_Expenses_date').inputmask("mask", {"mask": "9999-99-99"});
		$('#Paid_Expenses_dateEnd').inputmask("mask", {"mask": "9999-99-99"});
	}
	
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
			addInputDateMask();
			gridRowOnHandler();
		});
		
		$(document).on('click', '#printGridJournal', function () {
			window.print();
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