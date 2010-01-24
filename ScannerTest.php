<?php
require_once 'simpletest/autorun.php';
require_once 'Scanner.php';

// renders a visual output of the token stream only
class ScannerTest extends UnitTestCase {
	
	function testScanEmptyString() {
		$scanner = new SS_TemplateScanner("");
		$scanner->parse();
	}
	
	function testScanTemplateTag() {
		$scanner = new SS_TemplateScanner("<% base_tag %>");
		$scanner->parse();
	}
	
	function testScanTemplateTagWithPlainText() {
		$scanner = new SS_TemplateScanner("text here <% base_tag %> and here");
		$scanner->parse();
	}
	
	function testScanSSTemplateBasicHTML() {
		$content = file_get_contents(dirname(__FILE__).'/templates/Basic.ss');
		$scanner = new SS_TemplateScanner($content);
		$scanner->parse();
	}
	
}

?>