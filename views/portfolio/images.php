<div id="portfoliopictures">
	<?php if($display->title){ echo "<h2 class='collectionheader'><span>{$display->title}</span></h2>"; }?>
	<?php if($display->description){ 
		echo "<p class='collectiondescription'><span>";
		echo html_entity_decode($display->description, ENT_QUOTES) . "</span></p>"; }?>
	<?php 
		foreach ($pictures as $picture){
			include view::show('portfolio/image');
		}
	?>
</div>
