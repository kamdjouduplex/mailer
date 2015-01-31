(function($){
	$(document).ready (function () {
		$('form').on ('submit', function (e) {
			e.preventDefault();
            
			var $form = $(this);
			var $data = $form.find ('input[type="text"], input[type="hidden"], input[type="checkbox"]:checked, input[type="radio"]:checked, textarea, select');
			var action = $form.attr ('action');
			
			$.ajax({
				url: action,
				type: 'post',
				data: $data,
				dataType: 'json',
				success: function (data) {
					$form.find('input[type="text"]').attr('style', 'background-color:white');
					if (data['success']) {
						alert(data['success']);
					} else {
						msg = 'Проверьте правильность заполнения полей:\n';
						for (key in data['errors']) {
							$form.find('input[name="' + key + '"]').attr('style', 'background-color: pink');
							msg += data['errors'][key] + '\n';
						}
						alert(msg);
					}
				}
			});
		});
	});
})(jQuery);