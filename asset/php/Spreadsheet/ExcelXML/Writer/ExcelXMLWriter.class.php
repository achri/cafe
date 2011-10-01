<?
/*
 * ExcelXMLWriter written by Adam M. Riyadi, adam@vmt.co.id (2007/07/22)
 */

class ExcelXMLWriter {
	var $workbook = "";
	var $style = array();
	var $worksheet = array();
	var $author = "";

	function ExcelXMLWriter() {
	}

	function &addFormat($id) {
		$this->style[$id] =& new ExcelXMLFormat($id);

		return $this->style[$id];
	}

	function &addWorksheet($name) {
		$this->worksheet[$name] =& new ExcelXMLWorksheet($name);

		return $this->worksheet[$name];
	}
	
	function &setAuthor($name = "") {
		$this->author = $name;
	}

	function &renderXML() {
		$this->workbook = '
			<?xml version="1.0"?>
			<?mso-application progid="Excel.Sheet"?>
			<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
			 xmlns:o="urn:schemas-microsoft-com:office:office"
			 xmlns:x="urn:schemas-microsoft-com:office:excel"
			 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
			 xmlns:html="http://www.w3.org/TR/REC-html40">
				 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
					  <Author>'.$this->author.'</Author>
					  <Created>'.date("Y-m-d").'</Created>
					  <Version>11.8132</Version>
				 </DocumentProperties>

				 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
					  <WindowHeight>9135</WindowHeight>
					  <WindowWidth>15195</WindowWidth>
					  <WindowTopX>480</WindowTopX>
					  <WindowTopY>135</WindowTopY>
					  <ProtectStructure>False</ProtectStructure>
					  <ProtectWindows>False</ProtectWindows>
				 </ExcelWorkbook>

				 <Styles>
					  <Style ss:ID="Default" ss:Name="Normal">
						   <Alignment ss:Vertical="Bottom"/>
						   <Borders/>
						   <Font/>
						   <Interior/>
						   <NumberFormat/>
						   <Protection/>
					  </Style>
		';

		while (list($key,$val) = each($this->style)) {
			$this->workbook .= '
					'.$this->style[$key]->toString().'
					';
		}	
		$this->workbook .= '
				</Styles>
		';

		while (list($key,$val) = each($this->worksheet)) {
					$this->workbook .= '
						'.$this->worksheet[$key]->toString().'
						';
		}	
		$this->workbook .= '
			</Workbook>
		';
	}

	function &save($path) {
		if (!$handle = fopen($path, 'w')) {
			echo "Cannot open file ($path)";
			exit;
		}
		$this->renderXML();
		if (fwrite($handle, $this->workbook) === FALSE) {
		   echo "Cannot write to file ($filename)";
		   exit;
		}
		fclose($handle);
	}

	function &send($fileName) {
		$this->renderXML();
		header("Content-Type: application/x-msexcel; name=\"$fileName\"");
		header("Content-Disposition: inline; filename=\"$fileName\"");
		echo $this->workbook;
	}
}

?>