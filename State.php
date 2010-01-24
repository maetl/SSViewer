<?php
/**
 * Accepts tokens from the scanner state, and builds a string
 * buffer of the parsed template.
 */
class SS_TemplateStateBuffer {
	
	function __construct() {
		$this->buffer = "";
	}
	
	function emitText($chars) {
		echo $chars;
	}
	
	function emitBlockScope($keyword) {
		echo "[$keyword]";
	}
	
	function emitIdentifier($keyword) {
		echo "Obj:[$keyword]";
	}
	
	function emitObjectBinding() {
		echo "Call:->";
	}
	
	function emitDataBindingParameter($param) {
		echo "Arg:[$param]";
	}
	
	function emitComment($comment) {
		echo "Comment:[$comment]";
	}
	
}

/**
 * Tracks state for nested block scopes.
 */
class SS_TemplateStateStack {
	
	function construct() {
		
	}
	
	function push() {
		
	}
	
	function pop() {
		
	}
	
}

?>