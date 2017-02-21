<?php
/**
 * Created by PhpStorm.
 * User: -MR-
 * Date: 10/05/2016
 * Time: 12:06 PM
 */

function getAllUserActiveSessions($db,$uid){

    $resQ = $db->makeQuery("SELECT user_session.UserSessionID,user_session.IP, user_session.LoginDate , user_session.DeviceName
  FROM user_session where UserID='$uid' order by user_session.LoginDate desc");

    $arr = [];
    while($r = $resQ->fetch_assoc())
        $arr[] = $r;

    return $arr;
}

?>