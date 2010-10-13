<?php
    foreach ($data as $datum)
	{
		$value = $datum['value'];
		echo "<li><a href='#' onclick=\"return false;\">$value</a></li>";
    }
?>