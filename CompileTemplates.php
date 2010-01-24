<?php
require_once 'SSViewer.php';

// use the legacy parser to compile sample templates to PHP
foreach (glob("templates/*.ss") as $source) {
	$target = str_replace('.ss', '.php', $source);
	echo "compiling: $source to $target\n";
	$contents = file_get_contents($source);
	$out = SSViewer::parseTemplateContent($contents);
	file_put_contents($target, $out);
}

?>