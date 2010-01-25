<?php
require_once 'Dictionary.php';
require_once 'DataBinding.php';

/**
 * Accepts tokens from the scanner state, and builds a string
 * buffer of the parsed template.
 *
 * @todo expression chain should be linked to root node not the state stack
 */
class SS_TemplateStateBuffer {
	private $buffer;
	private $dictionary;
	
	function __construct() {
		$this->buffer = "";
		$this->dictionary = new SS_TemplateTagDictionary();
		$this->expression_chain = new SS_TemplateExpressionChain();
	}
	
	function getBuffer() {
		return $this->buffer;
	}
	
	function emitText($chars) {
		$this->buffer .= $chars;
	}
	
	function emitVariablePrintStart() {
		$this->expression_chain->push(new SS_VariableWriteScope());
	}
	
	function emitVariablePrintEnd() {
		while($binding = $this->expression_chain->pop()) {
			$this->buffer .= $binding->write();
		}
	}
	
	function emitBlockScope($keyword) {
		$control = $this->dictionary->get($keyword);
		$this->expression_chain->push($control);
	}
	
	function emitBlockScopeYield() {
		while($control = $this->expression_chain->pop()) {
			$this->buffer .= $control->write();
		}
	}
	
	function emitViewableDataObj($keyword) {
		$this->expression_chain->push(new SS_ViewableDataObj($keyword));
	}
	
	function emitViewableDataBinding() {
		$this->expression_chain->push(new SS_ViewableDataBinding($keyword));
	}
	
	function emitViewableDataBindingParameter($param) {
		$this->expression_chain->push(new SS_ViewableDataParameter($param));
	}
	
	function emitComment($comment) {
		//if (SSViewer::getOption('showTemplateComments')) {
		//	$this->buffer .= "<!-- $comment -->";
		//}
	}
	
}

/**
 * Handles chained expressions in control blocks and variables.
 */
class SS_TemplateExpressionChain {
	private $elements = array();
	
	function push($element) {
		$this->elements[] = $element;
	}
	
	function pop() {
		return array_shift($this->elements);
	}
	
	function size() {
		return count($this->elements);
	}
	
}

?>