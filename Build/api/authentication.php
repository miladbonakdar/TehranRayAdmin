<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    echoResponse(200, $session);
});


$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'),$r->customer);
    $response = array();
    $db = new DbHandler();
    $password = $r->customer->password;
    $email = $r->customer->email;
    
    $user = $db->getOneRecord("select ID,LastName,FirstName,Email,Password,Username from user where Username='$email' or Email='$email'");
    
    if ($user != NULL) {
        if(passwordHash::check_password($user['Password'],$password)){
        	
        	$sessionID = generateSessionID(100);
        	$resSessionIDQ = $db->updateRecord('user',"SessionID='".$sessionID."',SessionValid=1","ID='".$user['ID']."'");
        	
            $IsAdmin=false;
            $admin = $db->getOneRecord("select * from admin where UserID=".$user['ID']);
            if($admin){
                $IsAdmin=true;
            }

            $response['Status'] = "success";
            $response['Device'] = $user['FirstName'];
            $response['SSN'] = $sessionID;
            $response['LastName'] = $user['LastName'];
            $response['FirstName'] = $user['FirstName'];
            $response['Email'] = $user['Email'];
            $response['IsAdmin'] = $IsAdmin;
            $response['UserID'] = $user['ID'];
            if($IsAdmin){
            	$response['AdminID'] = $admin['ID'];
            }
            
            if (!isset($_SESSION)) {
                session_start();
            }
            
            $_SESSION['Status'] = "success";
            $_SESSION['SSN'] = $sessionID;
            $_SESSION['LastName'] = $user['LastName'];
            $_SESSION['FirstName'] = $user['FirstName'];
            $_SESSION['IsAdmin'] = $IsAdmin;
            $_SESSION['Email'] = $email;
            $_SESSION['UserID'] = $user['ID'];
            $_SESSION['AdminID'] = $admin['ID'];
            
            if($IsAdmin){
            	$_SESSION['AdminID'] = $admin['ID'];
            }
        } else {
            $response['Status'] = "error";
            $response['Message'] = 'Login failed. Incorrect credentials';
        }
    }else {
            $response['Status'] = "error";
            $response['Message'] = 'No such user is registered';
        }
    echoResponse(200, $response);
});
$app->post('/signUp', function() use ($app) {

    $r = json_decode($app->request->getBody());
	
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$postfields = array('secret'=>'6LdFLB4TAAAAAGqRYQeB5dBPiLq-XeJJreomcQpA',
						'response'=>$r->recaptchaResponse);

	$ch = curl_init( $url );

    if (FALSE === $ch)
        throw new Exception('failed to initialize');
        
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
	
    $response = array();
	$googleResponse = curl_exec( $ch );
	if(!$googleResponse){
        $response["Status"] = "error-captcha";
        echoResponse(201, $response);
	}else{
		
    	$resG = json_decode($googleResponse);
    	if($resG->success == FALSE){
        	$response["Status"] = "error-captcha";
	        echoResponse(201, $response);
	    	return ;
		}
	}
	
    verifyRequiredParams(array('email', 'lastName' ,'firstName' , 'username', 'password'),$r->customer);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $lastName = $r->customer->lastName;
    $firstName = $r->customer->firstName;
    $email = $r->customer->email;
    $password = $r->customer->password;
    $username = $r->customer->username;

    $isUserExists = $db->getOneRecord("select 1 from user where Email='$email' or Username='$username'");
    if(!$isUserExists){
        $r->customer->password = passwordHash::hash($password);
        $tabble_name = "user";
        $column_names = array( 'Email','LastName', 'FirstName', 'Username', 'Password','IP');
        
        $object = (object) [
            'Username' => $username,
            'Email' => $email,
            'LastName' => $lastName,
            'FirstName' => $firstName,
            'Password' => $r->customer->password,
            'IP' => getIPAddress()
          ];

        $result = $db->insertIntoTable($object, $column_names, $tabble_name);
        if ($result != NULL) {
            $response["Status"] = "success";
            $response["UserID"] = $result;
            $response["FirstName"] = $firstName;
            $response["LastName"] = $lastName;
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['UserID'] = $response["UserID"];
            $_SESSION['FirstName'] = $firstName;
            $_SESSION['Username'] = $username;
            $_SESSION['LastName'] = $lastName;
            $_SESSION['Email'] = $email;
            echoResponse(200, $response);
        } else {
            $response["Status"] = "error";
            echoResponse(201, $response);
        }            
    }else{
        $response["Status"] = "error-exists";
        echoResponse(201, $response);
    }
});
$app->get('/logout', function() {
    $db = new DbHandler();
    
    $session = $db->getSession();
    $resSessionIDQ = $db->updateRecord('user',"SessionValid=0","ID='".$session['UserID']."'");
    
    $session = $db->destroySession();
    
    $response =[];
    $response["Status"] = "success";
    echoResponse(200, $response);
});

?>