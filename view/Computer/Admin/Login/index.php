<div class="loginWraper">
	<div id="loginform">
		<form class="form form-horizontal">
			<div class="row cl">
				<label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
				<div class="formControls col-xs-8">
					<input id="" name="phone" type="text" placeholder="账户" class="input-text size-L" required="required">
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
				<div class="formControls col-xs-8">
					<input name="password" type="password" placeholder="密码" class="input-text size-L" required="required">
				</div>
			</div>
			<div class="row cl">
				<div class="formControls col-xs-8 col-xs-offset-3">
					<input class="input-text size-L" type="text" name="code" placeholder="验证码" style="width:150px;" required="required">
					<img id="refresh" src="<?php echo url('login/loginCode');?>" onclick="document.getElementById('refresh').src='<?php echo url('login/loginCode');?>'">
					<a href="javascript:;" onclick="document.getElementById('refresh').src='<?php echo url('login/loginCode');?>'">点击切换</a>
				</div>
			</div>
			<div class="row cl">
				<div class="formControls col-xs-8 col-xs-offset-3">
					<label for="online">
						<input type="checkbox" name="online" id="online" value="">
						使我保持登录状态</label>
				</div>
			</div>
			<div class="row cl">
				<div class="formControls col-xs-8 col-xs-offset-3">
					<button type="button" class="btn btn-success radius size-L" id="login-btn">&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;</button>
				</div>
			</div>
		</form>
	</div>
	<div class="footer">Copyright&nbsp;2020-<?php echo date('Y');?>&nbsp;<?php echo $site['name'] ?? '广州市奥创三维科技有限公司';?>&reg;&nbsp;版权所有</div>
</div>
<script type="text/javascript">
$(function(){
	LOGIN.init();
});
</script>