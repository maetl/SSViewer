<?php
require_once 'Scanner.php';

$content = file_get_contents(dirname(__FILE__).'/templates/Basic.ss');
$scanner = new SS_TemplateScanner($content);
echo $scanner->parse();

?>