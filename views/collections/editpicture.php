<tr>
	<td class="titlerow"><?php echo $picture->title; ?></td>
	<td class="editrow"><a href='/admin/pictures/edit/<?php echo $picture->picture_id; ?>' class="actionbutton">Edit</a></td>
	<td class="removerow"><a href='<?php echo "/admin/collections/remove/{$collection->collection_id}/{$pic_count}"; ?>' class="actionbutton">Remove</a></td>
</tr>