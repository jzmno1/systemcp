<?php

function writer($css_file, $fopen_mode, $search_pattern, $replace_pattern) {
	$handle = fopen($css_file, $fopen_mode);
	$contents = fread($handle, filesize($css_file));
	$contents = preg_replace($search_pattern, $replace_pattern, $contents, 1);
	ftruncate($handle, 0);
	fwrite($handle, $contents);
	fclose($handle);
}

?>