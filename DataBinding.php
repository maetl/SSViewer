<?php
/**
 * A method call binding in an expression chain.
 */
class SS_ViewableDataBinding {
	
	function write() {
		return "[call->]";
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
		return "[obj({$this->identifier})]";
	}
	
}

/**
 * Parameter to apply to object call
 */
class SS_ViewableDataParameter {
	
	function __construct($parameter) {
		$this->parameter = $parameter;
	}
	
	function write() {
		return "[arg({$this->parameter})]";
	}
	
}

/**
 * Variable write scope
 */
class SS_VariableWriteScope {
	
	function write() {
		return "[write]";
	}
	
}

?>