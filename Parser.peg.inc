<?php
require_once 'PEG/Parser.php' ;
require_once 'State.php';

class SS_TemplateParser extends Parser {

/*Parser:SS_TemplateParser

Template: TemplateTag TemplateTag

TemplateTag: "<%" > TagExpression > "%>"

TagExpression: YieldBlockScope | YieldIfScope | EndScope | RenderTag

YieldBlockScope: BlockExpression > ViewableDataValue

BlockExpression: "control" | "with" | "loop"

YieldIfScope: "if" | "unless" > ViewableDataCompare

EndScope: "end" | "end_with" | "end_control" | "end_if" | "end_loop"

ViewableDataValue: (/[A-Za-z]/)*

ViewableDataCompare: (/[A-Za-z]/)*

RenderTag: (/[a-z]/)*

*/

	function __construct($string) {
		$this->template = new SS_TemplateStateBuffer();
		parent::__construct($string);
	}
	
	function TagExpression_EndScope($scope, $token) {
		$this->template->emitBlockScope($token['text']);
	}
	
	function TagExpression_RenderTag($scope, $token) {
		$this->template->emitBlockScope($token['text']);
	}
	
	function YieldBlockScope_BlockExpression($scope, $token) {
		$this->template->emitBlockScope($token['text']);
	}
	
	function YieldBlockScope_ViewableDataValue($scope, $token) {
		$this->template->emitViewableDataObj($token['text']);
	}
	
	function TemplateTag_TagExpression() {
		$this->template->emitBlockScopeYield();
	}

	function parse() {
		$this->match_Template();
		return $this->template->getBuffer();
	}

}
