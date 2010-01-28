<?php
require_once 'Scanner.php';
require_once 'Parser.php';

$template = '<% with DataObjectBinding %><% end_with %>';

$parser = new SS_TemplateParser($template);
$template1 = $parser->parse();

$scanner = new SS_TemplateScanner($template);
$template2 = $scanner->parse();

if ($template1 == $template2) {
	echo "Output is identical:\n\n$template1\n\n";
} else {
	echo "Output comparison failed:\n\n";
	echo $template1, "\n\n", $template2, "\n\n";
}



?>