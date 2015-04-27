function Controller () {
	
}

function JournalController () {
	
	var ajaxSuccess = function (html) {
		$('.b-content').html(html);
	};
	
	var loadPage = function (event) {
		$('.b-content__journal').animate({opacity: 0.4}, 200, function () {
			$.ajax({
				url: '/paid/journal/' + event.currentTarget.id,
				success: ajaxSuccess,
				method: 'post'
			});
		});
		console.log($(this));
		return false;
	};
	
	this.handlerAllExpenses = function () {
		$(document).on('click', '#allExpenses', loadPage);
	};
	
	this.handlerNotPaidExpenses = function () {
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