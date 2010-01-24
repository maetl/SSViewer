<?php
require_once 'SSViewer.php';

foreach (glob("templates/*.ss") as $source) {
	$target = str_replace('.ss', '.php', $source);
	echo "compiling: $source to $target\n";
	$contents = file_get_contents($source);
	$out = SSViewer::parseTemplateContent($contents);
	file_put_contents($target, $out);
}

?>