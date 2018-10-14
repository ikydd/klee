<div id="show">
	<h3 class="sectionheader"><span>List of Collections</span></h3>
	<a href="/admin/collections/reorder" class="actionbutton">Re-order Collections</a>
	<table class="show">
		<tr>
			<th id="title"><span class="showheader">Title</span></th>
			<th id="description"><span class="showheader">Description</span></th>
			<th id="slug"><span class="showheader">URL Slug</span></th>
			<th></th>
			<th></th>
		</tr>
		<?php
			$empty = true;
			foreach ($collections as $collection){
				$empty = false;
				include view::show('collections/row');
			}
			if ($empty){include view::show('collections/none');}
		?>
	</table>
</div>