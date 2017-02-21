<?php

class Session {

	public $Valid;

	public $Status;
	public $FullName;
	public $Email;
	public $SSN;
	public $UserID;
	public $IsAdmin;
	public $AdminID;
	public $AdminPermission;
	public $AdminPermissionLevel;
	public $SignupDate;
	public $Image;

	function __construct() {
		if (!isset($_SESSION)) {
			session_start();
		}
		if(isset($_SESSION['UserID'])){
			$this->Valid = true;
			$this->UserID = $_SESSION['UserID'];
			$this->FullName = $_SESSION['FullName'];
			$this->Email = $_SESSION['Email'];
			$this->SSN = $_SESSION['SSN'];
			$this->IsAdmin = $_SESSION['IsAdmin'];
			$this->SignupDate = $_SESSION['SignupDate'];
			$this->Image = $_SESSION['Image'];

			if($this->IsAdmin){
				$this->AdminPermissionLevel = $_SESSION['AdminPermissionLevel'];
				$this->AdminPermission = $_SESSION['AdminPermission'];
				$this->AdminID = $_SESSION['AdminID'];
			}
		}
		else
		{
			$this->Valid = false;
		}
	}

	public function updateImage($value){
		if (!isset($_SESSION)) {
			session_start();
		}
		$_SESSION['Image'] = $value;
	}

	public function updateFullName($fullName){
		if (!isset($_SESSION)) {
			session_start();
		}
		$_SESSION['FullName'] = $fullName;
	}

	public function destroySession(){

		$res = [];
	    if (!isset($_SESSION)) {
            ini_set('session.gc_maxlifetime', 30*6000);
	    session_start();
	    }

		if(isSet($_SESSION['UserID']))
		{
			$cookiePath = '/';
			$cookieTime = time()-1000;

			setcookie("SSN", '', $cookieTime ,$cookiePath);
			setcookie("FullName",'',$cookieTime,$cookiePath);
			setcookie("IsAdmin", '', $cookieTime,$cookiePath);
			setcookie("Email", '', $cookieTime,$cookiePath);
			setcookie("UserID", '', $cookieTime,$cookiePath);
			setcookie("SignupDate", '', $cookieTime,$cookiePath);
			setcookie("Image", '',$cookieTime,$cookiePath);
		}

	    if(isSet($_SESSION['UserID']))
	    {
			unset($_SESSION['UserID']);
			unset($_SESSION['FullName']);
			unset($_SESSION['Email']);
			unset($_SESSION['SSN']);
			unset($_SESSION['IsAdmin']);
			unset($_SESSION['AdminID']);
			unset($_SESSION['AdminPermission']);
			unset($_SESSION['AdminPermissionLevel']);
			unset($_SESSION['SignupDate']);
			unset($_SESSION['Image']);
			unset($_SESSION['FirstName']);

			$res['Status'] = 'success';
	    }
	    else
	    {
			$res['Status'] = 'error';
	    }

	    return $res;
	}
 
}

?>
