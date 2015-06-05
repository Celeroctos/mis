function JournalController () {
	
	function gridRowOnHandler() {
		
		function success(html) {
			$('#modalSelectJournalRowBody').html(html);
			$('#modalSelectJournalRow').modal('show');			
		}
		
		$(document).on('click', '.gridJournalExpenses tbody tr', function () {
			var expense_number = $(this).find('.expense_number').html();
			$.ajax({
				url: '/paid/journal/chooseRow/expense_number/' + expense_number,
				success: success,
				method: 'post'
			});
		});
	}
	
	var ajaxSuccess = function (html) {
		$('.b-content').html(html);
	};
	
	var loadPage = function (event) {
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