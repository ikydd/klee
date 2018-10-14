<div id="pictures">
	<h3 class="sectionheader"><span>List of Pictures</span></h3>
	<?php include view::show('standard/pagenumbers'); ?>
	<table class="show">
		<tr>
			<th id="title"><span class="showheader">Title</span></th>
			<th id="picture"><span class="showheader">Picture</span></th>
			<th id="caption"><span class="showheader">Caption</span></th>
			<th></th>
			<th></th>
		</tr>
	<?php
	
	if (count($pictures)){
		foreach ($pictures as $picture){
			include view::show('pictures/row');
		}
	}
	else {
		include view::show('pictures/none');
	}
	?>
	</table>
	<?php include view::show('standard/pagenumbers'); ?>
</div>