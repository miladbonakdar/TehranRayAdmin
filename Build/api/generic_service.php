<?php

$app->post('/logout', function() use ($app)  {
//    if(isset($_SESSION['UserID'])){unset($_SESSION['UserID']);}
//    if(isset($_SESSION['FullName'])){unset($_SESSION['FullName']);}
//    if(isset($_SESSION['Email'])){unset($_SESSION['Email']);}
//    if(isset($_SESSION['SSN'])){unset($_SESSION['SSN']);}
//    if(isset($_SESSION['IsAdmin'])){unset($_SESSION['IsAdmin']);}
//    if(isset($_SESSION['AdminID'])){unset($_SESSION['AdminID']);}
//    if(isset($_SESSION['AdminPermission'])){unset($_SESSION['AdminPermission']);}
//    if(isset($_SESSION['AdminPermissionLevel'])){unset($_SESSION['AdminPermissionLevel']);}
//    if(isset($_SESSION['SignupDate'])){unset($_SESSION['SignupDate']);}
//    if(isset($_SESSION['Image'])){unset($_SESSION['Image']);}
//    if(isset($_SESSION['FirstName'])){unset($_SESSION['FirstName']);}
//    echoSuccess("here");
//
    $sess = $app->session;
    $app->db->deleteFromTable('user_session',"UserID='$sess->UserID' AND SessionID='$sess->SSN'");
    $res = $sess->destroySession();
    echoResponse(200, $res);
});

?>