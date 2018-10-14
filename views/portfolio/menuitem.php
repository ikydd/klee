<?php

echo "<a href='/portfolio/{$collection->slug}'";
if ($selection == $collection->slug){echo " class='portfolioselected'";}
echo ">{$collection->title}</a>";

?>