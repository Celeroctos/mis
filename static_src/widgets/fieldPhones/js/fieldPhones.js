(function(){
	var i = 1;
	$(document).ready(function() {
		$('#b-phones__add').on('click', function() {
			blockObj=$('<div class="b-phones__input input-group"></div>');
			inputObj=$('<input style="opacity: 1;" type="text" class="form-control input-sm" name=Patient_Contact[value][' + i + ']>');
			deleteButtonObj=$('<span class="b-phones__spanDelete input-group-addon glyphicon glyphicon-remove-circle" aria-hidden="true"></span>');

			$('.b-phones').append(blockObj);
			blockObj.append(inputObj);
			blockObj.append(deleteButtonObj);
			i++; //из замыкания
			
			deleteButtonObj.on('click', function() {
//				$(this).parent().animate({opacity: 0}, 'slow', function(){
				$(this).parent().detach();
//				});
			});
		});
	});
})();