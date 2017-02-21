<?php

class DbHandler {

    private $conn;
    private $db;

    function __construct($session ,$userRequire = false, $adminRequire = false) {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();

        $this->conn->query('SET CHARACTER SET utf8') or die($this->conn->error.__LINE__);

        if($userRequire)
            $this->userRequire($this,$session, $adminRequire);
    }

    function userRequire($db, $session,$adminRequire = false){

        $rq =null;
        $res = [];

        if($adminRequire){
            $rq = $db->makeQuery("SELECT us.UserSessionID FROM user_session as us
inner join user on user.UserID =us.UserID
inner join admin on admin.UserID =user.UserID
where us.UserID='$session->UserID' AND us.SessionID='$session->SSN' LIMIT 1");
            $c=mysqli_num_rows($rq);
            if($c > 0){
                $db->updateRecord('user',"LastActiveTime=Now()","UserID='$session->UserID' LIMIT 1");
                return TRUE;
            }

            $session->destroySession();
            $res['AuthState'] = 'UN_AUTH';
            echoResponse(201,$res);
            die();
        }
    }

    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();
    }
    public function getOneWithImage($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result =mysqli_fetch_array($r);
    }

    public function startTransaction() {
	$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
       // $this->conn->autocommit(FALSE);
    }
    public function rollbackTransaction() {
        $this->conn->rollBack();
    }
    public function commitTransaction() {
        $this->conn->commit();
    }
    /**
     * Fetching records
     */
    public function getRecords($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        $result = array();
        while($res = $r->fetch_assoc()){
            $result[] = $res;
        }
        return $result;
    }
    /**
     * Fetching records
     */
    public function makeQuery($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        return $r;
    }
    public function execute($query) {
        $r = $this->conn->execute($query) or die($this->conn->error.__LINE__);
        return $r;
    }
    public function multiQuery($queries) {
        $r = $this->conn->multi_query($queries) or die($this->conn->error.__LINE__);
        return $r;
    }
    /**
     * Fetching records
     */
    public function prepare($query) {
        $r = $this->conn->prepare($query) or die($this->conn->error.__LINE__);
        return $r;
    }
    /**
     * Fetching records
     */
    public function getConnection() {
        return $this->conn;
    }
    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
        $query = "INSERT INTO `".$table_name."` (".trim($columns,',').") VALUES(".trim($values,',').")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
            } else {
            return NULL;
        }
    }
    public function deleteFromTable($table_name , $where) {
        
        $query = "DELETE FROM `".$table_name."` WHERE ".$where;
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            return $r;
        }
        return NULL;
    }
    public function existsRecord($table_name , $where) {
        
        $query = "SELECT count(*) as Total FROM `".$table_name."` WHERE ".$where;
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
		$res = $r->fetch_assoc();
        if ($res["Total"] > 0) {
            return TRUE;
        }
        return FALSE;
    }
    public function getCount($table_name , $where) {
        
        $query = "SELECT count(*) as Total FROM `".$table_name."` WHERE ".$where;
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
		$res = $r->fetch_assoc();
        return $res["Total"];
    }
    public function insertToTable($table_name , $col_names,$values , $return_id = false) {
        
        $query = "INSERT INTO `".$table_name."` (".$col_names.") VALUES(".$values.")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($return_id) {
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
        }

        if ($r) {
            return $r;
        }
        return NULL;
    }

    public function updateRecord($table_name , $set , $where) {
        
        $query = "UPDATE `".$table_name."` SET ".$set." WHERE ".$where;
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            return $r;
        }
        return NULL;
    }

	public function getPage($table_name,$pageSize,$pageIndex,$selects,$where,$query){
		$total = $this->getCount($table_name,$where);
    	$offset = ($pageIndex-1) * $pageSize;

		$q = $this->makeQuery("SELECT ".$selects." FROM `".$table_name."` ".$query.
		" LIMIT $offset, $pageSize");

		$items = [];
		while($r = $q->fetch_assoc()){
            $items[] = $r;
        }

		$res = [];
		$res['Items'] = $items;
		$res['PageSize'] = $pageSize;
		$res['PageIndex'] = $pageIndex;
		$res['Total'] = $total;
		return $res;
	}
}

?>
