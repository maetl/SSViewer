<?php
require_once 'simpletest/autorun.php';
require_once 'Scanner.php';

class ScannerTest extends UnitTestCase {
	
	function testScanEmptyString() {
		$scanner = new SS_TemplateScanner("");
		$scanner->parse();
	}
	
	function testScanSSTemplateBasicHTML() {
		$content = file_get_contents(dirname(__FILE__).'/templates/Basic.ss');
		$scanner = new SS_TemplateScanner($content);
		$scanner->parse();
	}
	
}

?>