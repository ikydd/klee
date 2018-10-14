<div id="uploadform">
	<h3 class="sectionheader"><span>Upload New Picture</span></h3>
	<form action="/admin/pictures/upload" method="POST" enctype="multipart/form-data" class="adminform">
		<fieldset>
		<legend><span>Upload New Picture</span></legend>
			<table>
				<tr>
					<td class="rowheader"><label for="title">Title</label></td>
					<td><input type="text" name="title" id="title" req="true" class="inputbox" value="<?php echo tools::retrieve('title'); ?>" /></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="caption">Caption</label></td>
					<td><textarea name="caption" id="caption" class="inputbox"><?php echo tools::retrieve('caption'); ?></textarea></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="uploadfile">File</label></td>
					<td><input type="file" name="uploadfile" id="uploadfile" req="true" /></td>
				</tr>
				<tr class="submitrow">
					<td colspan="2"><input type="submit" name="submitform" id="submitform" class="actionbutton" value="Upload" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>