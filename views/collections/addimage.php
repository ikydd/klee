<div id="addimageform">
	<h3 class="sectionheader"><span>Add Picture to Collection</span></h3>
	<form action='/admin/collections/processadd' method="POST" class="adminform">
		<fieldset>
		<legend><span>Add Picture</span></legend>
			<table>
				<tr>
					<td class="rowheader"><label for="addimage">Picture</label></td>
					<td>
						<input type="hidden" name="collection_id" value="<?php echo $collection->collection_id; ?>" />
						<select name="picture_id" id="picture_id">
							<option selected="selected" value="">Select picture</option>
						<?php
							foreach ($pictures as $picture) {
								include view::show('collections/imageoption');
							} ?>
						</select>
				
					</td>
				</tr>
				<tr>
					<td class="rowheader"><label for="insert">Insert Before</label></td>
					<td>
						<select name="insert_id" id="insert_id">
							<option selected="selected" value="">--</option>
						<?php 
						$insert_count = 0;
						foreach ($collection_pics as $picture) {
								$picture = new picture($picture);
								include view::show('collections/insertoption');
								$insert_count++;
							} ?>
						</select>
					</td>
				</tr>
				<tr class="submitrow">
					<td colspan="2"><input type="submit" name="submitform" id="submitform" class="actionbutton" value="Add Picture" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>