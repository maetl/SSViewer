<?php
require_once 'State.php';

/**
 * Experimental scanner for the SilverStripe template syntax.
 *
 * <p>Converts a template source string into a buffer and emits events, switching the parsing state
 * as it scans forward.</p>
 *
 * <p>The template grammar supports expressions, which may be composed in the template source text
 * as variable references, or evaluated as compound arguments to a control block statement.</p>
 *
 * <p>Expressions take the form of chained references to ViewableData objects, with optional arguments to customize the call.</p>
 *
 * <p>A simple expression, which emits a lookup for a Title field in the scope of the current page:</p>
 *
 * <pre>
 *  $MyVariable
 * </pre>
 *
 * <p>Traversing a DataObject chain:</p>
 *
 * <pre>
 *   $MyObject.MyField
 *   $MyObject.MyRelatedObject.MyField
 * </pre>
 *
 * <p>Customizing the ViewableData evaluation, with arguments:</p>
 *
 * <pre>
 *   $MyObject.MyCustomField('arg')
 *   $MyObject.MyOtherCustomField(1,2,3)
 * </pre>
 *
 * <p>Expressions are also evaluated by control statements, to yield the block:</p>
 *
 * <pre>
 *  <% if MyObject.MyField %>
 *     show
 *  <% end_if %>
 *
 *  <% control Menu(1) %>
 *     list
 *  <% end_control %>
 * 
 *  <% control MyObject('arg1', 'arg2') %>
 *     list
 *  <% end_control %>
 * </pre>
 *
 * @todo generic <% end %> pops all block scope
 * @todo possible uses of <% else %> tag
 * @todo support operators and logical evaluation in expressions
 */
class SS_TemplateScanner {
	private $content;
	
	function __construct($content) {
		$this->content = $content;
		$this->length = strlen($content);
		$this->cursor = 0;
		$this->line = 1;
		$this->template = new SS_TemplateStateBuffer();
	}

	function scanIdentifier() {
		return $this->scanWhilePattern("/[A-Za-z_0-9]/");
	}
	
	function scanInteger() {
		return $this->scanWhilePattern("/[0-9]/");
	}
	
	function scanString() {
		return $this->scanUntil("'");
	}
	
	function isIdentifier($char) {
		return preg_match("/[A-Za-z]/", $char);
	}
	
	function isInteger($char) {
		return preg_match("/[0-9]/", $char);
	}

	function scanUntil($string) {
		$anchor = $this->cursor;
		while ($this->cursor < $this->length && strpos($string, $this->content{$this->cursor}) === false) {
			$this->cursor++;
		}
		return substr($this->content, $anchor, $this->cursor - $anchor);
	}

	function scanWhilePattern($pattern) {
		$char = $this->scanForward();
		$token = "";
		while ($this->cursor < $this->length && preg_match($pattern, $char)) {
			$token .= $char;
			$char = $this->scanForward();
		}
		$this->cursor--;
		return $token;		
	}
	
	function scanForward() {
		if ($this->cursor < $this->length) {
			return $this->content{$this->cursor++};
		}
	}
	
	function scanBack() {
		return $this->content{$this->cursor--};
	}
	
	function enterArgumentList() {
		$char = $this->scanForward();
		if ($char == ' ' || $char == ',') {
			$this->enterArgumentList();
		} elseif ($char == ')') {
			$this->scanBack();
			$this->enterDataBindingState();
		} elseif ($char == "'") {
			$string = $this->scanString();
			$this->template->emitDataBindingParameter($string);
			
			$this->scanForward();
			$this->enterArgumentList();
		} elseif($this->isInteger($char)) {
			$this->scanBack();
			$integer = $this->scanInteger();
			$this->template->emitDataBindingParameter($integer);
			$this->enterArgumentList();
		}
	}
	
	function enterDataBindingState() {
		$char = $this->scanForward();
		if ($this->isIdentifier($char)) {
			$this->scanBack();
			$keyword = $this->scanIdentifier();
			$this->template->emitIdentifier($keyword);
			$this->enterDataBindingState();
		} elseif ($char == '.') {
			$this->template->emitObjectBinding();
			$this->enterDataBindingState();
		} elseif ($char == '(') {
			$this->enterArgumentList();
		} elseif ($char == ')') {
			$this->enterDataBindingState();
		}
	}
	
	function enterControlExpressionState() {
		$char = $this->scanForward();
		if ($char == ' ') {
			$this->enterControlExpressionState();
		} elseif ($this->isIdentifier($char)) {
			$this->scanBack();
			$this->enterDataBindingState();
		}
	}

	function enterControlTagState() {
		$char = $this->scanForward();
		if ($char == ' ') {
			$this->enterControlTagState();
		} elseif ($this->isIdentifier($char)) {
			$this->scanBack();
			$keyword = $this->scanIdentifier();
			$this->template->emitBlockScope($keyword);
			$this->enterControlExpressionState();
			$this->enterControlTagState();
		} elseif ($char == '%') {
			$next = $this->scanForward();
			if ($next != '>') {
				throw new Exception("Unclosed tag");
			}
		} elseif ($char == '-') {
			$next = $this->scanForward();
			if ($next == '-') {
				$comment = $this->scanUntil('--%>');
				$this->template->emitComment($comment);
				$this->cursor += 4;
			}
		}
	}
	
	function enterStartState() {
		$char = $this->scanForward();
		if ($char == '<') {
			$next = $this->scanForward();
			if ($next == '%') {
				$this->enterControlTagState();
			} else {
				$this->template->emitText($char.$next);
			}
		} elseif ($char == '$') {
			$next = $this->scanForward();
			if ($this->isIdentifier($next)) {
				$this->scanBack();
				$this->enterDataBindingState();
				$this->scanBack();
			}
		} elseif ($char == "\n") {
			$this->line += 1;
			$this->template->emitText($char);
		} else {
			$this->template->emitText($char);
		}
	}

	function parse() {
		do {
			$this->enterStartState();
		} while ($this->cursor < $this->length);
	}
	
}

?>