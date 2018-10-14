<div id="loginform">
<form action="/login/process" method="POST" class="adminform">
	<fieldset>
	<legend><span>Login</span></legend>
		<table>
			<tr>
				<td><label for="username">Username</label></td>
				<td><input type="text" name="username" id="username" req="true" valid="username" value="<?php echo tools::retrieve('username', REMOVE); ?>" /></td>
			</tr>
			<tr>
				<td><label for="password">Password</label></td>
				<td><input type="password" name="password" id="password" req="true" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><label for="remember"><span>Remember me?</span></label>
				<input type="checkbox" checked="checked" name="remember" id="remember" /></td>
			<tr class="submitrow">
				<td colspan="2"><input type="submit" name="submitform" id="submitform" class="actionbutton" value="Login" /></td>
			</tr>
		</table>
	</fieldset>
</form>
</div>