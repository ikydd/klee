<div id="show">
	<h3 class="sectionheader"><span>Your Profile</span></h3>
	<table class="show">

			<tr>
				<th id="username"><span class="showheader">Username</span></th>
				<th id="password"><span class="showheader">Password</span></th>
			</tr>
			<tr>
				<td class="username"><?php echo $user->username; ?></td>
				<td class="password">***********</td>
			</tr>
	</table>
	<div id="actions">
		<a href="/admin/profile/edit" class="actionbutton">Change Details</a>
	</div>
</div>