$(document).ready(function() {
	var obj=[];
	var i=0;
	$(document).on('click', '#add_elem', function(){
		i++;
			obj[i]=$('<input class="form-control input-sm">');
				$('.ajax_f').append(obj[i]);

					obj[i].on('click', function(){
//						obj[i].detach();
						alert(i);
					});
});
});