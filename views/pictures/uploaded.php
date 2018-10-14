<?php 
$uploaded = tools::retrieve('uploaded', tools::REMOVE);
if ($uploaded->picture_id) { 
	$picture = $uploaded; ?>
	<div id="uploaded">
		<table class="show">
		<caption>Just uploaded</caption>
		<tr>
			<th id="title"><span class="showheader">Title</span></th>
			<th id="picture"><span class="showheader">Picture</span></th>
			<th id="caption"><span class="showheader">Caption</span></th>
			<th></th>
			<th></th>
		</tr>
		<?php include view::show('pictures/row'); ?>
		</table>
	</div>
<?php } ?>