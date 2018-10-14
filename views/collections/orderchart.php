<div id="orderchart">
	<h3 class="sectionheader"><span>Re-order Collections</span></h3>
	<p class="ordertip">0 will come first, higher numbers come later.</p>
	<form action="/collections/processreorder" method="POST" class="adminform">
		<fieldset>
		<legend>Reorder Collections</legend>
			<table class="show">
				<tr>
					<th id="title"><span class="showheader">Title</span></th>
					<th id="position"><span class="showheader">Position</span></th>
				</tr>
				<?php
					$empty = true;
					foreach ($collections as $collection){
						$empty = false;
						include view::show('collections/orderrow');
					}			
					if ($empty){include view::show('collections/none');}
				?>
				<tr>
					<td colspan="2" class="submitrow"><input type="submit" name="submitform" id="submitform" class="actionbutton" value="Save New Order" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>