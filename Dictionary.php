<?php

class SS_TemplateTagDictionary {
	
	/**
	 * Should this come from the class manifest, instead?
	 */
	private $control_tag_list = array(
		"if" => 'SS_IfBlock',
		"else" => "SS_ElseBlock",
		"else_if" => "SS_ElseIfBlock",
		"end_if" => "SS_EndBlock",
		"control" => "SS_ControlBlock",
		"end_control" => "SS_EndBlock",
		"base_tag" => "SS_BaseTag",
		"include" => "SS_IncludeTag"
	);
	
	/**
	 * Return a control tag object from the dictionary lookup.
	 */
	public function get($control_tag) {
		if (array_key_exists($control_tag, $this->control_tag_list)) {
			$control = $this->control_tag_list[$control_tag];
			return new $control();
		} else {
			throw new Exception("<% $control_tag %> tag does not exist");
		}
	}
	
}

class SS_IfBlock {
	
	function write() {
		return "[if]";
	}
	
}

class SS_ElseBlock {
	
	function write() {
		return "[else]";
	}
	
}

class SS_ElseIfBlock {
	
	function write() {
		return "[else_if]";
	}
	
}

class SS_EndBlock {
	
	function write() {
		return "[end]";
	}
	
}

class SS_BaseTag {
	
	function write() {
		return "[base_tag]";
	}
	
}

class SS_ControlBlock {

	function write() {
		return "[control]";
	}
	
}

class SS_IncludeTag {
	
	function write() {
		return "[include]";
	}
	
}

?>