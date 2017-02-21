<?php

$app->post('/logout', function() use ($app)  {
    $sess = $app->session;
    $app->db->deleteFromTable('user_session',"UserID='$sess->UserID' AND SessionID='$sess->SSN'");
    $res = $sess->destroySession();
    echoResponse(200, $res);
});

?>