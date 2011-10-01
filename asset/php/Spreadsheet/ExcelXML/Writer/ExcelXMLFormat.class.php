<?php
/*
 * ExcelXMLFormat written by Adam M. Riyadi, adam@vmt.co.id (2007/07/22)
 */

class ExcelXMLFormat {
	var $HAlign = 
		array(
			"Left",
			"Center",
			"Right"
		);
	var $VAlign = 
		array(
			"Top",
			"Center",
			"Bottom"
		);
	var $BorderStyle = 
		array(
			"Continuous",
			"Double",
			"Dash",
			"DashDot",
			"DashDotDot",
			"SlantDashDot"
		);
	var $BorderPosition = 
		array(
			"Bottom",
			"Left",
			"Right",
			"Top"
		);
	var $UnderLine = 
		array(
			"Single",
			"Double"
		);
	var $Pattern = 	
		array(				
			"Solid",
			"ThinHorzStripe",
			"ThinVertStripe",
			"ThinReverseDiagStripe",
			"ThinDiagStripe",
			"ThinHorzCross",
			"ThinDiagCross",
			"HorzStripe",
			"VertStripe",
			"ReverseDiagStripe",
			"DiagStripe",
			"DiagCross",
			"ThickDiagCross",
			"Gray75",
			"Gray50",
			"Gray25",
			"Gray125",
			"Gray0625"
		);
	
	
	var $id = "";
	var $align = array();
	var $font = array();
	var $border = array();
	var $interior = array();
	var $format = array();

	var $style = "";
	var $isNumber = false; //data = Number
	var $isDateTime = false; //data = DateTime

	function &ExcelXMLFormat($id = "") {
		$this->id = $id;	
	}

	function &getId() {
		return $this->id;
	}

	//ALIGNMENT
	function &setAlign($HAlign = "Left",$VAlign = "Top") {
		$this->align[$this->id]["ss:Horizontal"] = $HAlign;	
		$this->align[$this->id]["ss:Vertical"] = $VAlign;	
	}

	function &setHAlign($HAlign = 0) {
		$this->align[$this->id]["ss:Horizontal"] = $this->HAlign[$HAlign];	
	}

	function &setVAlign($VAlign = 0) {
		$this->align[$this->id]["ss:Vertical"] = $this->VAlign[$VAlign];
	}

	function &setTextWrap($wrap = 1) {
		$this->align[$this->id]["ss:WrapText"] = $wrap;	
	}
	//ALIGNMENT


	//BORDER
	function &setBorder($style = 0,$weight = 1,$arrPosition = array()) {
		if (is_array($arrPosition) && sizeof($arrPosition) > 0) {
			while (list($key,$val) = each($arrPosition)) {
				$this->border[$this->id][$this->BorderPosition[$key]]["ss:LineStyle"] = $this->BorderStyle[$style];	
				$this->border[$this->id][$this->BorderPosition[$key]]["ss:Weight"] = $weight;	
			}
		}
		else {
			for ($i=0;$i<sizeof($this->BorderPosition);$i++) {
				$this->border[$this->id][$this->BorderPosition[$i]]["ss:LineStyle"] = $this->BorderStyle[$style];	
				$this->border[$this->id][$this->BorderPosition[$i]]["ss:Weight"] = $weight;	
			}
		}
	}
	//BORDER

	//FONT
	function &setFontFamily($Family = "Tahoma") {
		$this->font[$this->id]["ss:FontName"] = $Family;	
	}

	function &setFontSize($size = "11") {
		$this->font[$this->id]["ss:Size"] = $size;	
	}

	function &setFontColor($color = "#000000") {
		$this->font[$this->id]["ss:Color"] = $color;	
	}

	function &setFont($Family = "Tahoma",$size = 11) {
		$this->font[$this->id]["ss:FontName"] = $Family;	
		$this->font[$this->id]["ss:Size"] = $size;	
	}

	function &setBold() {
		$this->font[$this->id]["ss:Bold"] = 1;	
	}

	function &setItalic() {
		$this->font[$this->id]["ss:Italic"] = 1;	
	}
	
	// 0 = single, 1=double
	function &setUnderline($type = 0) {
		$this->font[$this->id]["ss:Underline"] = $this->UnderLine[$type];	
	}
	//FONT

	//INTERIOR
	function &setColor($color = "#FFFFFF") {
		$this->interior[$this->id]["ss:Color"] = $color;	
		if ($this->interior[$this->id]["ss:Pattern"] == "") {
			$this->interior[$this->id]["ss:Pattern"] = $this->Pattern[0];	
		}
	}

	function &setPattern($pattern = 0,$color="#000000") {
		if ($pattern != 0) {
			$this->interior[$this->id]["ss:Pattern"] = $this->Pattern[$pattern];	
			$this->interior[$this->id]["ss:PatternColor"] = $color;	
		}
		else {
			$this->interior[$this->id]["ss:Pattern"] = $this->Pattern[$pattern];	
		}
	}
	//INTERIOR

	
	//NUMBER
	/*
		$format = 0 // standard 1 // negative red // 2 (negative) //3 (negative red)
	*/
	function setNumber($format = 0,$separator = 1, $decimal = 2) {
		$strFormat = "0.";
		if ($decimal > 0) {
			for ($i=0;$i<$decimal;$i++) {
				$strFormat .= "0";
			}
		}
		else {
			$strFormat = "0";
		}
		if ($separator == 1) {
			$strFormat = "#,##".$strFormat;
		}
		switch($format) {
			case 0:
			break;
			case 1:
				$strFormat = $strFormat.';[Red]'.$strFormat;
			break;
			case 2:
				$strFormat = $strFormat.'_);\('.$strFormat.'\)';
			break;
			case 3:
				$strFormat = $strFormat.'_);[Red]\('.$strFormat.'\)';
			break;
			default:
			break;
		}
		$this->format[$this->id]["ss:Format"]=$strFormat;

		$this->isNumber = true;
		$this->isDateTime = false;
	}

	/*
		$format = 0 // standard 1 // negative red // 2 (negative) //3 (negative red)
	*/
	function setCurrency($curr = "USD",$format = 0,$separator = 1, $decimal = 2) {
		$strFormat = "0.";
		for ($i=0;$i<$decimal;$i++) {
			$strFormat .= "0";
		}
		if ($separator == 1) {
			$strFormat = "&quot;".$curr."&quot;#,##".$strFormat;
		}
		else {
			$strFormat = "&quot;".$curr."&quot;".$strFormat;
		}
		switch($format) {
			case 0:
			break;
			case 1:
				$strFormat = $strFormat.';[Red]'.$strFormat;
			break;
			case 2:
				$strFormat = $strFormat.'_);\('.$strFormat.'\)';
			break;
			case 3:
				$strFormat = $strFormat.'_);[Red]\('.$strFormat.'\)';
			break;
			default:
			break;
		}
		$this->format[$this->id]["ss:Format"]=$strFormat;

		$this->isNumber = true;
		$this->isDateTime = false;
	}

	function setAccounting($curr = "USD",$decimal = 2) {
		$zeros = "0.";
		for ($i=0;$i<$decimal;$i++) {
			$zeros .= "0";
		}
		
		$strFormat = "_(&quot;".$curr."&quot;* #,##".$zeros."_);_(&quot;".$curr."&quot;* \(#,##".$zeros."\);_(&quot;R".$curr."p&quot;* &quot;-&quot;?????_);_(@_)";
	
		$this->format[$this->id]["ss:Format"]=$strFormat;

		$this->isNumber = true;
		$this->isDateTime = false;
	}

	/*
		$format = 0 // short (hh:mm) 1 // long (hh:mm:ss)
	*/
	function setPercent($decimal = 0) {
		$strFormat = "0.";
		for ($i=0;$i<$decimal;$i++) {
			$strFormat .= "0";
		}
		$strFormat .= "%";

		$this->format[$this->id]["ss:Format"]=$strFormat;

		$this->isNumber = true;
		$this->isDateTime = false;
	}
	//NUMBER
	

	//DATETIME
	/*
		$format = 0 // short date (dd/mm/yy) 1 // short date (dd/mm/yyyy) 2 // short date (dd-mm-yyyy)  2 // long date 
	*/
	function setDate($format = 0) {
		switch ($format) {
			case 0:
				$strFormat = "dd/mm/yy;@";
			break;
			case 1:
				$strFormat = "dd/mm/yyyy;@";
			break;
			case 2:
				$strFormat = "dd-mm-yyyy;@";
			break;
			case 3:
				$strFormat = "[$-421]dd\ mmmm\ yyyy;@";
			break;
			default:
				$strFormat = "dd/mm/yyyy;@";
			break;
		}

		$this->format[$this->id]["ss:Format"]=$strFormat;

		$this->isDateTime = true;
		$this->isNumber = false;
	}

	/*
		$format = 0 // short (hh:mm) 1 // long (hh:mm:ss)
	*/
	function setTime($format = 0) {
		switch ($format) {
			case 0:
				$strFormat = "hh:mm;@";
			break;
			case 1:
				$strFormat = "hh:mm:ss;@";
			break;
			default:
				$strFormat = "hh:mm;@";
			break;
		}

		$this->format[$this->id]["ss:Format"]=$strFormat;

		$this->isDateTime = true;
		$this->isNumber = false;
	}
	//DATETIME


	function toString() {
		//begin render align
		$strAlign = "";
		if (sizeof($this->align[$this->id]) > 0) {
			$strAlign = '
				<Alignment ';
			while (list($key,$val) = each($this->align[$this->id])) {
				$strAlign .= $key.'="'.$val.'" ';
			}
			$strAlign .= '/>';
		}
		else {
			$strAlign = '<Alignment ss:Horizontal="Left" ss:Vertical="Top" />';
		}
		//end render align

		//begin render border
		$strBorder = "";
		if (sizeof($this->border[$this->id]) > 0) {
			$strBorder = '
				<Borders>';
			while (list($key,$val) = each($this->border[$this->id])) {
				$strBorder .= '
					<Border ss:Position="'.$key.'" ';
				while (list($key2,$val2) = each($val)) {
					$strBorder .= $key2.'="'.$val2.'" ';
				}
				$strBorder .= '/>';
			}
			$strBorder .= '
				</Borders> ';
		}
		//end render border

		//begin render font
		$strFont = "";
		if (sizeof($this->font[$this->id]) > 0) {
			$strFont = '
				<Font x:Family="Swiss" ';
			while (list($key,$val) = each($this->font[$this->id])) {
				$strFont .= $key.'="'.$val.'" ';
			}
			$strFont .= '/>';
		}
		else {
			$strFont = '<Font x:Family="Swiss" ss:Size="11" />';
		}
		//end render font

		//begin render interior
		$strInterior = "";
		if (sizeof($this->interior[$this->id]) > 0) {
			$strInterior = '
				<Interior ';
			while (list($key,$val) = each($this->interior[$this->id])) {
				$strInterior .= $key.'="'.$val.'" ';
			}
			$strInterior .= '/>';
		}
		//end render interior

		//begin render format
		$strFormat = "";
		if (sizeof($this->format[$this->id]) > 0) {
			$strAlign = '';
			$strFormat = '
				<NumberFormat ';
			while (list($key,$val) = each($this->format[$this->id])) {
				$strFormat .= $key.'="'.$val.'" ';
			}
			$strFormat .= '/>';
		}
		//end render format

		$this->style ='
			  <Style ss:ID="'.$this->id.'">
				   '.$strAlign.'
				   '.$strBorder.'
				   '.$strFont.'
				   '.$strInterior.'
				   '.$strFormat.'
			  </Style>
			';
		return $this->style;
	}
}

?>