<?php
/**
 * A method call binding in an expression chain.
 */
class SS_ViewableDataBinding {
	
	function write() {
		echo "[call->]";
	}
	
}

/**
 * A ViewableData object in an expression chain.
 */
class SS_ViewableDataObj {
	
	function __construct($identifier) {
		$this->identifier = $identifier;
	}
	
	function write() {
		echo "[obj({$this->identifier})]";
	}
	
}

/**
 * Variable write scope
 */
class SS_VariableWriteScope {
	
	function write() {
		echo "[write]";
	}
	
}

/**
 * Variable write scope
 */
class SS_VariableWriteClose {
	
	function write() {
		echo "[/write]";
	}
	
}

?>