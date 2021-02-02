var LOGIN = {
	init: function() {
		$('#login-btn').on('click', function(){
			if ($(this).hasClass('loading')) return false;
			$(this).addClass('loading')
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
			var _thisobj = $(this);
			$.post(URI + 'login/login', $(this).parents('form').serializeArray(), function(res){
				if (res.code == 200) {
					window.location.href = res.data.url;
				} else {
					_thisobj.removeClass('loading');
					LOGIN.error(res.message);
				}
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