function Controller () {
	
}

function JournalController () {
	
	var loadPageUrl = null; //url ajax request
	
	var ajaxRequest = function (html) {
		$('.b-content').html(html);
	};
	
	var loadPage = function () {
		$.ajax({
			url: loadPageUrl,
			success: ajaxRequest,
			method: 'post'
		});
		return false;			
	};
	
	this.handlerAllExpenses = function () {
		loadPageUrl = '/paid/journal/allExpenses';
		$(document).on('click', '#allExpenses', loadPage);
	};
	
	this.handlerNotPaidExpenses = function () {
		loadPageUrl = 'paid/journal/notPaidExpenses';
		$(document).on('click', '#notPaidExpenses', loadPage);
	};
	
	this.init = function () {
		this.handlerAllExpenses();
		this.handlerNotPaidExpenses();
	};
}
JournalController.prototype=Controller;

var journal=new JournalController();
journal.init(); // грузим экшн
