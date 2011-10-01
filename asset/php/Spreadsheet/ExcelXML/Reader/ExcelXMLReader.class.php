<?php
/*
 * ExcelXMLReader written by Adam M. Riyadi, adam@vmt.co.id (2007/07/22)
 */

class ExcelXMLReader {
	var $xmlParser = "";
	var $handler = "";

	var $xmlString = "";

	function ExcelXMLReader($xmlString = "") {
		$this->xmlString =  $xmlString;

		$this->xmlParser =  new SAXY_Parser();
		$this->handler =& new ExcelXMLParserHandler();

		//register events
		$this->xmlParser->xml_set_element_handler(array(&$this->handler, "startElementHandler"), array(&$this->handler, "endElementHandler"));
		$this->xmlParser->xml_set_character_data_handler(array(&$this->handler, "characterHandler"));
	}

	function &read() {
		$success = $this->xmlParser->parse($this->xmlString);
		if ($success) {
			$this->handler->fetchData();

			return $this->handler->getData();
		}
		else {
		  //get error code
		  $errorCode = $this->xmlParser->xml_get_error_code();

		  //get error string
		  $errorString = $this->xmlParser->xml_error_string($errorCode);

		  //echo
		  echo "\n<br /><br />Error Code: " . $errorCode;
		  echo "\n<br />Error String: " . $errorString;
		}
	}
}

?>