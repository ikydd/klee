<div class="pagenumbers">
	<?php
	if ($pagecount > 1) {
		$maxnumbers = 5; // two either side
		$lowend = 1; // left
		$highend = $pagecount + 1; // right
		if ($pagecount > $maxnumbers) {
			$lowend = ($pagenumber - floor($maxnumbers/2)) > -1 ? $pagenumber - floor($maxnumbers/2) : 0;
			$lowend = ($pagenumber + floor($maxnumbers/2)) < $pagecount ? $lowend  : $pagecount - $maxnumbers;
			$lowend++;
			$highend = $lowend + $maxnumbers;
		}
		echo "<a class='pagenumber' href='/{$pagetype}/1'>First</a>";
		for($i = $lowend; $i < $highend; $i++) {
			echo "<a class='pagenumber";
			if ($pagenumber + 1 == $i) echo " thispage";
			echo "' href='/{$pagetype}/{$i}'>{$i}</a>";
		}
		echo "<a class='pagenumber' href='/{$pagetype}/{$pagecount}'>Last</a>";
	}
	?>
</div>