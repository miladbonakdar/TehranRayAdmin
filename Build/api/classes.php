<?php

class PaginationInput
{
	public $PageIndex = 1;
	public $PageSize = 200;

	function __construct($data) {
		if($data && isset($data->pageSize) && isset($data->pageIndex)){
			$this->PageSize = $data->pageSize;
			$this->PageIndex = $data->pageIndex;
		}
	}
}
class Pagination {

    public $PageIndex = 1;
    public $PageSize = 200;

	function __construct($data = null) {
		if($data && isset($data->pageSize) && isset($data->pageIndex)){
			$this->PageSize = $data->pageSize;
			$this->PageIndex = $data->pageIndex;
		}
	}

	public function setParams($pin){
		$this->PageSize = $pin->PageSize;
		$this->PageIndex = $pin->PageIndex;
	}


	public function calculateOffset(){
		$offset = ($this->PageIndex-1) * $this->PageSize;
		return $offset;
	}

	public function getPage($db,$query){
		$countFroms = substr_count($query, 'FROM');
		if($countFroms == 1){

			$startFromStr = strstr($query, 'FROM');
			$countQ = $db->makeQuery("SELECT count(*) as Total ".$startFromStr);
			$countRes = $countQ->fetch_assoc();
			$total = $countRes['Total'];
			$offset = ($this->PageIndex-1) * $this->PageSize;

			$q = $db->makeQuery($query." LIMIT $offset, $this->PageSize");

			$items = [];
			while($r = $q->fetch_assoc()){
			    if(isset($r["Image"]))
                    $r["Image"] = base64_encode($r["Image"]);
				$items[] = $r;
			}

			$res = [];
			$res['Items'] = $items;
			$res['PageSize'] = $this->PageSize;
			$res['PageIndex'] = $this->PageIndex;
			$res['Total'] = $total;

			return $res;
		}else{

			$startFromStr = strrpos($query, 'FROM');
			$rest = substr($query,$startFromStr);
			$startOrderStr = strrpos($rest, 'ORDER');
			$restOrder = substr($rest,$startOrderStr);
			$rest = substr($rest , 0 , strlen($rest)-strlen($restOrder));
			$countQ = $db->makeQuery("SELECT count(*) as Total ".$rest);
			$countRes = $countQ->fetch_assoc();
			$total = $countRes['Total'];

			$offset = ($this->PageIndex-1) * $this->PageSize;

			$q = $db->makeQuery($query." LIMIT $offset, $this->PageSize");

			$items = [];
			while($r = $q->fetch_assoc()){
				$items[] = $r;
			}

			$res = [];
			$res['Items'] = $items;
			$res['PageSize'] = $this->PageSize;
			$res['PageIndex'] = $this->PageIndex;
			$res['Total'] = $total;

			return $res;
		}

	}

}

class SiteInfo{
	
    public $version;
	public $forceUpdateDB;
	
    function __construct() {  
    	$forceUpdateDB = FALSE;
    }
    
    function getFromFile($filePath){
		
		$myfile = fopen($filePath, "r") or die("Unable to open '".$filePath."' version file!");
		$content = fgets($myfile);
		
		//$this->version = $line;
    	$splits = split(',', $content);
    	$this->version = $splits[0];
    	$this->forceUpdateDB = ($splits[1]=='0')?FALSE:TRUE;
		fclose($myfile);
	}
}
?>