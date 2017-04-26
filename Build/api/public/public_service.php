<?php

$app->post('/getSiteName', function() use ($app)  {
    echoSuccess("تهران ری");
});

$app->post('/signInUser', function() use ($app)  {
    require_once '../passwordHash.php';
    $r = json_decode($app->request->getBody());
    $response = array();

    $password = $r->Password;
    $email = $r->Username;

    $user = $app->db->getOneRecord("select user.UserID,FullName,Email,Password,Username,SignupDate,file_storage.FullPath as Image from 
    user LEFT JOIN 
    file_storage ON file_storage.FileStorageID=user.AvatarID where Email='$email' OR user.Username = '$email'");

    if ($user != NULL) {
        if(passwordHash::check_password($user['Password'],$password)){
            $IsAdmin=false;
            $admin = $app->db->getOneRecord("select * from admin left join admin_permission on admin_permission.AdminPermissionID =admin.PermissionID
             where admin.UserID=".$user['UserID']);

            if($admin){
                $IsAdmin=true;
            }else
            {
                echoError("not admin");
            }

            $sessionID = generateSessionID(100);
            $resSessionIDQ = $app->db->insertToTable('user_session',"UserID,SessionID,LoginDate,DeviceName,IP","'"
                .$user['UserID']."','$sessionID',NOW(),'".getOperatingSystem()."','".getIPAddress()."'");



            $response['Status'] = "success";
            $response['SSN'] = $sessionID;
            $response['FullName'] = $user['FullName'];
            $response['Email'] = $user['Email'];
            $response['IsAdmin'] = $IsAdmin;
            $response['UserID'] = $user['UserID'];
            $response['Image'] = $user['Image'];
            $response['SignupDate'] = $user['SignupDate'];
            if($IsAdmin){
                $response['AdminID'] = $admin['AdminID'];
                $response['AdminPermissionLevel'] = $admin['PermissionLevel'];
                $response['AdminPermission'] = $admin['Permission'];
            }

            if (!isset($_SESSION)) {
                session_start();
            }

            $cookiePath = '/';
            $cookieTime = time() + 914748364;

            setcookie("SSN", $sessionID, $cookieTime ,$cookiePath);
            setcookie("FullName", $user['FullName'],$cookieTime,$cookiePath);
            setcookie("IsAdmin", ($IsAdmin)?1:0, $cookieTime,$cookiePath);
            setcookie("Email", $email, $cookieTime,$cookiePath);
            setcookie("UserID", $user['UserID'], $cookieTime,$cookiePath);
            setcookie("SignupDate", $user['SignupDate'], $cookieTime,$cookiePath);
            setcookie("Image", $user['Image'],$cookieTime,$cookiePath);

            $_SESSION['Status'] = "success";
            $_SESSION['SSN'] = $sessionID;
            $_SESSION['FullName'] = $user['FullName'];
            $_SESSION['IsAdmin'] = $IsAdmin;
            $_SESSION['Email'] = $email;
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['SignupDate'] = $user['SignupDate'];
            $_SESSION['Image'] = $user['Image'];

            if($IsAdmin){
                $_SESSION['AdminID'] = $admin['AdminID'];
                $_SESSION['AdminPermissionLevel'] = $admin['PermissionLevel'];
                $_SESSION['AdminPermission'] = $admin['Permission'];

                setcookie("AdminID", $admin['AdminID'], $cookieTime,$cookiePath);
                setcookie("AdminPermissionLevel", $admin['PermissionLevel'], $cookieTime,$cookiePath);
                setcookie("AdminPermission", $admin['Permission'],$cookieTime,$cookiePath);
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

$app->post('/signInPhone', function() use ($app)  {
    require_once '../passwordHash.php';
    $r = json_decode($app->request->getBody());
    $response = array();

    $password = $r->Password;
    $email = $r->Username;

    $user = $app->db->getOneRecord("select phone.PhoneID,Password,Username,SignupDate from 
    phone where Username='$email'");

    if ($user != NULL) {
        if(passwordHash::check_password($user['Password'],$password)){

            $sessionID = generateSessionID(100);
            $resSessionIDQ = $app->db->insertToTable('phone_session',"PhoneID,SessionID,LoginDate,DeviceName,IP","'"
                .$user['PhoneID']."','$sessionID',NOW(),'".getOperatingSystem()."','".getIPAddress()."'");



            $response['Status'] = "success";
            $response['SSN'] = $sessionID;
            $response['PhoneID'] = $user['PhoneID'];
            $response['Username'] = $user['Username'];
            $response['SignupDate'] = $user['SignupDate'];

            if (!isset($_SESSION)) {
                session_start();
            }

            $cookiePath = '/';
            $cookieTime = time() + 914748364;

            setcookie("SSN", $sessionID, $cookieTime ,$cookiePath);
            setcookie("PhoneID", $user['PhoneID'], $cookieTime,$cookiePath);
            setcookie("Username", $user['Username'], $cookieTime,$cookiePath);
            setcookie("SignupDate", $user['SignupDate'], $cookieTime,$cookiePath);

            $_SESSION['SSN'] = $sessionID;
            $_SESSION['PhoneID'] = $user['PhoneID'];
            $_SESSION['SignupDate'] = $user['SignupDate'];
            $_SESSION['Username'] = $user['Username'];

        } else {
            $response['Status'] = "error";
            $response['Message'] = '‍‍‍پسورد شما اشتباه است';
        }
    }else {
        $response['Status'] = "error";
        $response['Message'] = 'کاربری با این نام وجود ندارد';
    }
    echoResponse(200, $response);
});

$app->post('/logoutPhone', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    if(isset($data->SSN)){
        checkValidUser($app->db , $data->SSN);
        $app->db->deleteFromTable('phone_session',"SessionID='$data->SSN'");
        echoSuccess("کاربر خارج شد");
    }else
        echoError("شما هنوز به سیستم وارد نشده اید");
});

?>