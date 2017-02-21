<?php
/**
 * Created by PhpStorm.
 * User: -MR-
 * Date: 13/05/2016
 * Time: 07:33 PM
 */

function hasInfo(){
    return isset($_SESSION['UserID']) || isset($_COOKIE['UserID']) ;
}

function generateSessionAsJavascriptVariable()
{
    if (!isset($_SESSION)) {
        session_start();
    }

    if(isset($_SESSION["UserID"])){
        $adminScript= '';
        if(isset($_SESSION["IsAdmin"]) && $_SESSION["IsAdmin"] == 1){
            $adminScript = "session.AdminID = '".$_SESSION["AdminID"]."';".
                            "session.AdminPermissionLevel = '".$_SESSION["AdminPermissionLevel"]."';".
                            "session.AdminPermission = '".$_SESSION["AdminPermission"]."';";
        }

        echo "<script>".
            "var session = {};".
            "session.UserID = '".$_SESSION["UserID"]."';".
            "session.FullName = '".$_SESSION["FullName"]."';".
            "session.Email = '".$_SESSION["Email"]."';".
            "session.SSN = '".$_SESSION["SSN"]."';".
            "session.IsAdmin = '".$_SESSION["IsAdmin"]."';".
            "session.Image = '".$_SESSION["Image"]."';".
            "session.SignupDate = '".$_SESSION["SignupDate"]."';".
             $adminScript.
            "</script>";
    }else{
        if(isset($_COOKIE['UserID'])){

            $_SESSION['SSN'] = $_COOKIE['SSN'];
            $_SESSION['FullName'] = $_COOKIE['FullName'];
            $_SESSION['IsAdmin'] = $_COOKIE['IsAdmin'];
            $_SESSION['Email'] = $_COOKIE['Email'];
            $_SESSION['UserID'] = $_COOKIE['UserID'];
            $_SESSION['SignupDate'] = $_COOKIE['SignupDate'];
            $_SESSION['Image'] = $_COOKIE['Image'];

            if($_SESSION['IsAdmin']){
                $_SESSION['AdminID'] = $_COOKIE['AdminID'];
                $_SESSION['AdminPermissionLevel'] = $_COOKIE['AdminPermissionLevel'];
                $_SESSION['AdminPermission'] = $_COOKIE['AdminPermission'];
            }

            generateSessionAsJavascriptVariable();
        }else{

            echo "<script>".
                "var session = {};".
                "</script>";
        }

    }
}
?>
