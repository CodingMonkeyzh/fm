$(function(){
	$('form').submit(function(e) {
		var validated = 1;
		$.each($('input'), function() {
			if($(this).val() == '') {
				$(this).parent().addClass('error');
				validated = 0;
			}
		});
		if(!validated) {
			e.preventDefault();
		}
	});
	$('input').focus(function() {
		if($(this).parent().hasClass('error')) {
			$(this).parent().removeClass('error');
		}
	})
});