<div id="editform">
	<h3 class="sectionheader"><span>Edit Picture Specifics</span></h3>
	<form action='/admin/pictures/processedit' method="POST" class="adminform">
		<fieldset>
		<legend><span>Edit Picture</span></legend>
			<table>
				<tr>	
					<td colspan="2" id="imagecell">
						<a href='/public/portfolio/<?php echo $picture->filename; ?>' rel="lightbox" title="<?php echo $picture->title; ?>">
							<img class="thumbnail" src='/public/portfolio/<?php echo rawurlencode($picture->filename); ?>' />
						</a>
						<input type="hidden" name="picture_id" value="<?php echo $picture->picture_id; ?>" />
					</td>
				</tr>
				<tr>
					<td class="rowheader"><label for="title">Title</label></td>
					<td><input type="text" name="title" id="title" class="inputbox" value="<?php echo $picture->title; ?>" /></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="picturecaption">Caption</label></td>
					<td><textarea name="picturecaption" id="picturecaption" class="inputbox"><?php echo $picture->caption; ?></textarea></td>
				</tr>
				<tr class="submitrow">
					<td colspan="2">
						<a href='/admin/pictures/delete/<?php echo $picture->picture_id; ?>' class="actionbutton">Delete Picture</a>
						<input type="submit" name="submitform" id="submitform" class="actionbutton" value="Save Changes" />
					</td>
				</tr>
			</table>
		</legend>
	</form>
</div>