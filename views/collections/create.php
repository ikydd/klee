<div id="createform">
	<h3 class="sectionheader"><span>Create New Collection</span></h3>
	<form action="/admin/collections/create" method="POST" class="adminform">
		<fieldset>
		<legend><span>Create New Collection</span></legend>
			<table>
				<tr>
					<td class="rowheader"><label for="title">Title</label></td>
					<td><input type="text" name="title" id="title" req="true" class="inputbox" value="<?php echo tools::retrieve('title'); ?>" /></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="slug">Slug</label></td>
					<td><input type="text" name="slug" id="slug" req="true" valid="slug" class="inputbox" value="<?php echo tools::retrieve('slug'); ?>"/></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="caption">Description</label></td>
					<td><textarea name="description" id="description" class="inputbox"><?php echo tools::retrieve('description'); ?></textarea></td>
				</tr>
				<tr class="submitrow">
					<td colspan="2"><input type="submit" name="submitform" id="submitform" class="actionbutton" value="Create" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>