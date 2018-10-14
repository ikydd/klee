<div id="editform">
	<h3 class="sectionheader"><span>Edit Profile</span></h3>
	<form action="/admin/profile/processedit" method="POST" class="adminform">
		<fieldset>
		<legend><span>Edit Profile</span></legend>
			<table>
				<tr>
					<td class="rowheader"><label for="username">Username</label></td>
					<td><input type="text" name="username" id="username" req="true" valid="username" class="inputbox" value="<?php echo $user->username; ?>" /></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="old_password">Old Password</label></td>
					<td><input type="password" name="old_password" id="old_password" req="true" class="inputbox" /></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="new_password">New Password</label></td>
					<td><input type="password" name="new_password" id="new_password" valid="password" class="inputbox" /></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="conf_password">Confirm New Password</label></td>
					<td><input type="password" name="conf_password" id="conf_password" valid="password" class="inputbox" /></td>
				</tr>
				<tr  class="submitrow">
					<td colspan="2"><input type="submit" name="submitform" id="submitform" class="actionbutton" value="Save Changes" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>