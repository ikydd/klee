<tr>
	<td class="title"><span><?php echo $picture->title; ?></span></td>
	<td class="picture"><img class="thumbnail" src='/public/portfolio/thumbs/<?php echo  rawurlencode($picture->filename); ?>' /></td>
	<td class="caption"><span><?php echo $picture->caption; ?></span></td>
	<td class="edit"><a href='/admin/pictures/edit/<?php echo $picture->picture_id; ?>' class="actionbutton">Edit</a></td>
	<td class="delete"><a href='/admin/pictures/delete/<?php echo $picture->picture_id; ?>' class="actionbutton">Delete</a></td>
</tr>
	