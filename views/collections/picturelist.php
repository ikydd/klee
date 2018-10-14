<table>
	<tr class="picturelistedit">
		<td>Pictures</td>
	
	<?php 
		$pics = explode(":", $collection->pictures);
		$pic_count = 0;
		foreach ($pics as $picture) {
			$picture = new picture($picture);
			include view::show('collections/picture');
			$pic_count++;
		}
	?>
	</tr>
</table>