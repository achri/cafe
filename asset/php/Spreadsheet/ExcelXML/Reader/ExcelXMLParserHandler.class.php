<?php
/*
 * ExcelXMLParserHandler written by Adam M. Riyadi, adam@vmt.co.id (2007/07/22)
 */

class ExcelXMLParserHandler {

	var $name = "";
	var $header = "";
	var $attribut = "";
	var $raw_data = "";
	var $data = "";
	var $previous_row = -1;
	var $row = -1;
	var $cell = -1;

	function ExcelXMLParserHandler() {
	}
	
	function startElementHandler($parser, $elementName, $attrArray) {
		$this->name = strtolower($elementName);
		if ($this->name == "row" && strtolower($attrArray["id"]) == "header") {
			$this->row = 0;
		}
		elseif ($this->name == "row" && $this->row >= 0) {
			$this->row++;
		}
		if ($this->name == "cell" && $this->row >= 0) {
			if ($this->previous_row != $this->row) {
				$this->cell = 0;
			}
			else {
				if (trim($attrArray["ss:Index"]) != "") {
					$this->cell=trim($attrArray["ss:Index"]) - 1;
				}
				else {
					$this->cell++;
				}
			}
			$this->previous_row = $this->row;
		}

		if ($this->name == "data") {
			if ($this->row >= 0) {
				$this->attribut[$this->row][] = $attrArray["id"];
			}
		}
	} //startElementHandler

	function endElementHandler($parser, $elementName) {
	} //endElementHandler

	function characterHandler($parser, $text) {
		if ($this->name == "data") {
			if ($this->row > -1 && $this->cell > -1) {
				$this->raw_data[$this->row][$this->cell] = $text;
			}
		}
	} //characterHandler

	function getAttribut() {
		return $this->attribut;
	}

	function getData() {
		reset($this->data);
		return $this->data;
	}

	function fetchData() {
		$header_array = $this->attribut[0];
		while (list($key,$val) = each($header_array)) {
			if ($val == "header") {
				for ($row=1;$row<sizeof($this->raw_data);$row++) {
					for ($j=0;$j<=30;$j++) {
						$this->data[$row][$j] = $this->raw_data[$row][$j];
					}
				}
			}
		}
	}
}

?>