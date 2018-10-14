<tr class="valuerow">
	<td class="title"><?php echo $collection->title; ?></td>
	<td class="description"><?php echo substr($collection->description, 0, 100) . "..." ?></td>
	<td class="slug"><?php echo $collection->slug; ?></td>
	<td class="edit"><a href='/admin/collections/edit/<?php echo $collection->collection_id; ?>' class="actionbutton">Edit</a></td>
	<td class="delete"><a href='/admin/collections/delete/<?php echo $collection->collection_id; ?>' class="actionbutton">Delete</a></td>
</tr>
<tr class="picturerow">
		<td class="rowheader"><span class="showheader">Pictures >></span></td>
		<td class="pictures" colspan="2">
		<?php 
			$pics = explode(":", $collection->pictures);
			foreach ($pics as $picture) {
				$picture = new picture($picture);
				include view::show('collections/picture');
			}
		?>
		</td>
</tr>