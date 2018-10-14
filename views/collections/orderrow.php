<tr>
	<td class="title"><?php echo $collection->title; ?></td>
	<td class="position">
		<select class='small' name="coll_<?php echo $collection->collection_id; ?>">
			<?php
				for ($i = 0; $i < $amount; $i++) {
					echo "<option  value='{$i}'";
					if ($collection->position == $i) {
						echo " selected='selected'";
					}
					echo ">$i</option>";
				}
			?>
		</select>
	</td>
</tr>