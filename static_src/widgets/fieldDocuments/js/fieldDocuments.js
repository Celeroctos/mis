(function(){
	var i = 1;
	$(document).ready(function() {
		$('#b-documents__add').on('click', function() {
			var row=$('<div class="row">');
			var inputType=$('<div class="col-xs-3">\n\
								<select class="form-control input-sm" name="Patient_Documents[type]['+ i +']">\n\
								<option value="1">Паспорт</option>\n\
								<option value="2">Свидетельство о рождении</option>\n\
								<option value="3">Вид на жительство</option>\n\
								<option value="4">Паспорт иностранного гражданина</option>\n\
								<option value="5">Удостоверение личности</option>\n\
								<option value="6">Другой документ</option>\n\
								</select>\n\
							</div>');	
			var inputSerie=$('<div class="col-xs-3"><input class="form-control input-sm" type="text" name="Patient_Documents[serie]['+ i +']"></div>');
			var inputNumber=$('<div class="col-xs-3"><input class="form-control input-sm" type="text" name="Patient_Documents[number]['+ i +']"></div>');
			var deleteButtonObj=$('<div class="col-xs-3"><span class="b-documents__spanDelete glyphicon glyphicon-minus" aria-hidden="true"></span></div>');
		
			$('.b-documents').append(row);
			row.append(inputType);
			row.append(inputSerie);
			row.append(inputNumber);
			row.append(deleteButtonObj);
			
			deleteButtonObj.on('click', function() {
				$(this).parent().detach();
			});
		});
	i++;
	});
})();