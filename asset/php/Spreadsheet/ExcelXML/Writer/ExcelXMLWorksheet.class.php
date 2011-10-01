<?php
/*
 * ExcelXMLWorksheet written by Adam M. Riyadi, adam@vmt.co.id (2007/07/22)
 */

class ExcelXMLWorksheet {
	var $name = "";
	var $worksheet = "";
	var $table = "";
	var $data = array();
	var $height = array();
	var $width = array();
	var $ros = "";
	var $columns = "";
	var $mergeCell = "";
	var $freezePanes = "";
	var $dataSources = "";
	var $dataValidation = "";
	var $rowHeader = array();
	var $columnHeader = array();
	var $hiddenCell = array();

	function &ExcelXMLWorksheet($name="") {
		$this->name = $name;
	}

	function &setHeight($row,$height) {
		$this->height[$row] = $height;
	}

	function &setWidth($col,$width) {
		$this->width[$col] = $width;
	}

	function &write($row,$col,$data,$format = "") {
		$this->data[$row][$col]["type"] = "String";
		if ($format->isNumber) {
			$this->data[$row][$col]["type"] = "Number";
		}
		elseif ($format->isDateTime) {
			$this->data[$row][$col]["type"] = "DateTime";
		}
		$this->data[$row][$col]["format"] = $format->getId();
		$this->data[$row][$col]["data"] = $data;
	}

	function &addHiddenCell($col) {
		if ($col > 0) {
			$this->hiddenCell[] = $col;
		}
	}

	function &setMerge($row,$col,$across = 0,$down = 0) {
		if ($across > 0) {
			$this->mergeCell[$row][$col][0] = $across;
		}
		if ($down > 0) {
			$this->mergeCell[$row][$col][1] = $down;
		}
	}

	function &setFreezePanes($left,$top) {
		$this->freezePanes = '';
		if ($left > 0) {
			$this->freezePanes .= '
			   <SplitHorizontal>'.$left.'</SplitHorizontal>
			   <TopRowBottomPane>'.$left.'</TopRowBottomPane>
			';
		}
		if ($top > 0) {
			$this->freezePanes .= '
			   <SplitVertical>'.$top.'</SplitVertical>
			   <LeftColumnRightPane>'.$top.'</LeftColumnRightPane>
			';
		}
	}

	function &setHeader($x1,$y1,$x2,$y2) {
		for ($i=$x1;$i<=$x2;$i++) {
			$this->rowHeader[$i]=' id="header"';
			for ($j=$y1;$j<=$y2;$j++) {
				$this->columnHeader[$i][$j]=' id="header"';
			}
		}
	}
	
	function &addDataSource($row,$col,$data = array(),$title = "",$inputMessage="",$errorTitle = "",$errorMessage = "") {
		if ($errorTitle == "" && $title != "") {
			$errorTitle = "ERROR: ".$title;
		}
		if ($errorMessage == "" && $inputMessage != "") {
			$errorMessage = "ERROR: ".$inputMessage;
		}
		$array_data["data"]=$data;
		$array_data["title"]=$title;
		$array_data["inputMessage"]=$inputMessage;
		$array_data["errorTitle"]=$errorTitle;
		$array_data["errorMessage"]=$errorMessage;
		$array_data["row"]=$row;
		$array_data["col"]=$col;
		$this->dataSources[]=$array_data;
	}
	function &toString() {
		$arrCol = array();
		$dsIdx = 0;
		$maxDs = 0;
		$this->rows = '';
		$this->columns = '';
		$this->dataValidation = '';
		$this->worksheet = '';
		while (list($key,$val) = each($this->data)) {
			if ($this->height[$key] == "" && $this->height[0] != "") {$this->height[$key] = $this->height[0];}
			if ($this->height[$key] != "") {
				$this->rows .= ' 
			<Row ss:Index="'.$key.'" ss:AutoFitHeight="0" ss:Height="'.$this->height[$key].'"'.$this->rowHeader[$key].'>
				';
			}
			else {
				$this->rows .= ' 
			<Row ss:Index="'.$key.'" ss:AutoFitHeight="1"'.$this->rowHeader[$key].'>
				';
			}
			while (list($key2,$val2) = each($val)) {
				if ($this->width[$key2] == "" && $this->width[0] != "") {$this->width[$key2] = $this->width[0];}
				if ($this->width[$key2] != "" && !in_array($key2,$arrCol)) {
					$arrCol[] = $key2;
					if (in_array($key2,$this->hiddenCell)) {
						$this->columns .= '
						<Column ss:Index="'.$key2.'" ss:Hidden="1" ss:AutoFitWidth="0"/>';
					}
					else {
						$this->columns .= '
						<Column ss:Index="'.$key2.'" ss:Width="'.$this->width[$key2].'"/>';
					}
				}
				$strMerge = "";
				if (is_array($this->mergeCell)) {
					if ($this->mergeCell[$key][$key2][0] != "") {
						$strMerge .= ' ss:MergeAcross="'.$this->mergeCell[$key][$key2][0].'"';
					}
					if ($this->mergeCell[$key][$key2][1] != "") {
						$strMerge .= ' ss:MergeDown="'.$this->mergeCell[$key][$key2][1].'"';
					}
				}
				if ($val2["data"] != "") {
						if ($val2["format"] != "") {
							$this->rows .= ' 
								<Cell '.$strMerge.' ss:Index="'.$key2.'" ss:StyleID="'.$val2["format"].'">
									<Data ss:Type="'.$val2["type"].'"'.$this->rowHeader[$key].'>'.$val2["data"].'</Data>
								</Cell>';
						}
						else {
							$this->rows .= ' 
								<Cell '.$strMerge.' ss:Index="'.$key2.'">
									<Data ss:Type="'.$val2["type"].'"'.$this->rowHeader[$key].'>'.$val2["data"].'</Data>
								</Cell>';
						}
				}
			}
			if (sizeof($this->dataSources) > 0 && is_array($this->dataSources)) {
				
				for ($ds=0;$ds<sizeof($this->dataSources);$ds++) {
					$dataSource = $this->dataSources[$ds];
					if ($dataSource["data"][$key - 1] != "") {
						if ($maxDs < sizeof($dataSource["data"])) {
							$maxDs = sizeof($dataSource["data"]);
						}
						$this->rows .= ' 
							<Cell ss:Index="'.intval(256 - (sizeof($this->dataSources) - $ds - 1)).'"><Data ss:Type="String">'.$dataSource["data"][$key - 1].'</Data></Cell>';
					}
				}
			}
			$dsIdx = $key;
			$this->rows .= '
			</Row>';
		}
		$dsIdx++;
		for ($rowDs=$dsIdx;$rowDs<=$maxDs;$rowDs++) {
			$this->rows .= ' 
			<Row ss:Index="'.$rowDs.'" ss:AutoFitHeight="1">
				';
			for ($ds=0;$ds<sizeof($this->dataSources);$ds++) {
				$dataSource = $this->dataSources[$ds];
				if ($dataSource["data"][$rowDs - 1] != "") {
					$this->rows .= ' 
						<Cell ss:Index="'.intval(256 - (sizeof($this->dataSources) - $ds - 1)).'"><Data ss:Type="String">'.$dataSource["data"][$rowDs - 1].'</Data></Cell>';
				}
			}
			$this->rows .= '
			</Row>';
		}

		if (sizeof($this->dataSources) > 0 && is_array($this->dataSources)) {
			for ($i=0;$i<sizeof($this->dataSources);$i++) {
				/*if (sizeof($this->dataSources) == 1) {
				$this->columns .= '
						<Column ss:Index="'.intval(256 - (sizeof($this->dataSources) - $i)).'" ss:Hidden="1" ss:AutoFitWidth="0"/>
				   ';
					$this->dataValidation .= '
					<DataValidation xmlns="urn:schemas-microsoft-com:office:excel">
					   <Range>R'.$this->dataSources[$i]["row"].'C'.$this->dataSources[$i]["col"].':R65534C'.$this->dataSources[$i]["col"].'</Range>
					   <Type>List</Type>
					   <Value>C['.intval(256 - $this->dataSources[$i]["col"] + $i).']</Value>
					   <InputTitle>'.$this->dataSources[$i]["title"].'</InputTitle>
					   <InputMessage>'.$this->dataSources[$i]["inputMessage"].'</InputMessage>
					   <ErrorStyle>Warn</ErrorStyle>
					   <ErrorMessage>'.$this->dataSources[$i]["errorMessage"].'</ErrorMessage>
					   <ErrorTitle>'.$this->dataSources[$i]["errorTitle"].'</ErrorTitle>
				  </DataValidation>
					';
				}
				else {*/
				$this->columns .= '
						<Column ss:Index="'.intval(256 - (sizeof($this->dataSources) - $i - 1)).'" ss:Hidden="1" ss:AutoFitWidth="0"/>
				   ';
					$this->dataValidation .= '
					<DataValidation xmlns="urn:schemas-microsoft-com:office:excel">
					   <Range>R'.$this->dataSources[$i]["row"].'C'.$this->dataSources[$i]["col"].':R65534C'.$this->dataSources[$i]["col"].'</Range>
					   <Type>List</Type>
					   <Value>C['.intval(256 - $this->dataSources[$i]["col"]).']</Value>
					   <InputTitle>'.$this->dataSources[$i]["title"].'</InputTitle>
					   <InputMessage>'.$this->dataSources[$i]["inputMessage"].'</InputMessage>
					   <ErrorStyle>Warn</ErrorStyle>
					   <ErrorMessage>'.$this->dataSources[$i]["errorMessage"].'</ErrorMessage>
					   <ErrorTitle>'.$this->dataSources[$i]["errorTitle"].'</ErrorTitle>
				  </DataValidation>
					';
				//}
			}
		}
		$this->worksheet = '
			<Worksheet ss:Name="'.$this->name.'"> ';

		$this->table = 
			'
		<Table x:FullColumns="1" x:FullRows="1">
			';
		$this->table .= $this->columns;
		$this->table .= $this->rows;
		$this->table .= '
		</Table>';
		
		$this->worksheet .= $this->table;
		$this->worksheet .= '
			<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
			   <Print/>
			   <Selected/>
			   <FreezePanes/>
			   <FrozenNoSplit/>
		';
		if ($this->freezePanes != '') {
			$this->worksheet .= $this->freezePanes;
		}
		$this->worksheet .= '
			   <Panes/>
			   <ProtectObjects>False</ProtectObjects>
			   <ProtectScenarios>False</ProtectScenarios>
		  </WorksheetOptions>';
		
		$this->worksheet .= $this->dataValidation;
		$this->worksheet .= '
			</Worksheet>';
		return $this->worksheet;
	}
}

?>