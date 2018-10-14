<div id="editform">
	<h3 class="sectionheader"><span>Edit Collection Specifics</span></h3>
	<form action='/admin/collections/processedit' method="POST" class="adminform">
		<fieldset>
		<legend><span>Edit Collection</span></legend>
			<table class="editcollectiondetails">
				<tr>
					<td class="rowheader"><label for="title">Title</label></td>
					<td><input type="text" name="title" id="title" req="true" class="inputbox" value="<?php echo $collection->title; ?>" />
					<input type="hidden" name="collection_id" value="<?php echo $collection->collection_id; ?>" /></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="slug">Slug</label></td>
					<td><input type="text" name="slug" id="slug" req="true" valid="slug" class="inputbox" value="<?php echo $collection->slug; ?>"/></td>
				</tr>
				<tr>
					<td class="rowheader"><label for="description">Description</label></td>
					<td>
						<textarea name="description" id="description" class="inputbox"><?php echo $collection->description; ?></textarea>
					</td>
				</tr>
				<tr class="submitrow">
					<td colspan="2">
						<a href='/admin/collections/delete/<?php echo $collection->collection_id; ?>' class="actionbutton">Delete Collection</a>
						<input type="submit" name="submitform" id="submitform" class="actionbutton" value="Save Changes" />
					</td>
				</tr>
				<tr>
					<td class="rowheader"><label>Pictures</label></td>
					<td>
						<table id="editpicturelist">
							<?php 
								$pic_count = 0;
								foreach ($collection_pics as $picture) {
									$picture = new picture($picture);
									include view::show('collections/editpicture');
									$pic_count++;
								}
								if ($pic_count == 0) include view::show('collections/nopics');
							?>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
	</form>