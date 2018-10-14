<div class="portfoliopicture">
	<?php if ($picture->title){ echo "<h3 class='picturetitle'>{$picture->title}</h3>";} ?>
	<a href="/public/portfolio/<?php echo rawurlencode($picture->filename); ?>" rel="lightbox[portfolio]" title="<?php echo $picture->title; ?>">
		<img src="/public/portfolio/screens/<?php echo rawurlencode($picture->filename); ?>" class="pictureimage" />
	</a>
	<?php if ($picture->caption) { 
		echo "<p class='picturecaption'><span>";
		echo html_entity_decode($picture->caption, ENT_QUOTES);
		echo "</span></p>"; 
	} ?>
</div>
