var LOGIN = {
	init: function() {
		$('#login-btn').on('click', function(){
			var check = true;
			$(this).parents('form').find('[required="required"]').each(function(){
				if ($(this).val() == '') {
					$(this).addClass('error');
					LOGIN.error($(this).attr('placeholder') + '不能为空');
					check = false;
					return false;
				}
			});
			if (!check) {
				return false;
			}
			$.post(URI + 'login/login', $(this).parents('form').serializeArray(), function(){
				
			});
		});
		$('#loginform .form-horizontal input').on('blur', function(){
			if ($(this).hasClass('error')) {
				$(this).removeClass('error');
				$('#login-error').remove();
			}
		});
	},
	error: function(msg) {
		$('#login-error').remove();
		$('#loginform').prepend('<div id="login-error"><div class="message">'+msg+'</div></div>');
	}
};