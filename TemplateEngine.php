<?php
require_once 'Scanner.php';

/**
 * This isn't an interface, just a stub for testing.
 */
class SSViewer_TemplateEngine {
	
	function parseTemplateContent($content) {
		$scanner = new SS_TemplateScanner($content);
		return $scanner->parse();
	}
}

?>