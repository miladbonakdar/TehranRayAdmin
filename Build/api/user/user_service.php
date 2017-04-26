<?php

$app->post('/getStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    if(!isset($data->SSN) || !isset($data->StoneID))
        echoError("اطلاعات نا معتبر است");
    checkValidUser($app->db , $data->SSN);
    $stone = $app->db->getOneRecord("SELECT (SELECT ps.StoneID FROM stone as ps WHERE ps.CopeID = s.CopeID and ps.CopeNumber = (s.CopeNumber -1 ) and ps.StoneTypeID = s.StoneTypeID LIMIT 1) as PreviousStoneID ,
(SELECT ns.StoneID FROM stone as ns WHERE ns.CopeID = s.CopeID and ns.CopeNumber = (s.CopeNumber +1 ) and ns.StoneTypeID = s.StoneTypeID LIMIT 1) as NextStoneID ,
s.`StoneID`, st.`Name`, s.Height,s.Width , s.CreationDate, s.Image,
s.ImageSize, s.Description, s.Area, s.MachineNumber, s.CopeNumber AS CopeID,
s.Sold, s.`BockMachDirection`
FROM stone s inner JOIN stone_type st on st.StoneTypeID = s.StoneTypeID WHERE s.StoneID = ".$data->StoneID);
    if($stone["StoneID"]){
        $stone["Image"] = base64_encode($stone["Image"]);
        echoSuccess($stone);
    }
    else
        echoError("سنگ وجود ندارد");
echoSuccess($stone);
});


$app->post('/soldStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());

    if(!isset($data->SSN) || !isset($data->StoneID))
        echoError("اطلاعات نا معتبر است");
    checkValidUser($app->db , $data->SSN);
    $resQ = $app->db->updateRecord('stone',"`Sold`='1'","stone.StoneID='$data->StoneID'");
    echoSuccess("سنگ فروخته شد");
});

$app->post('/unsoldStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());

    if(!isset($data->SSN) || !isset($data->StoneID))
        echoError("اطلاعات نا معتبر است");
    checkValidUser($app->db , $data->SSN);
    $resQ = $app->db->updateRecord('stone',"`Sold`='0'","stone.StoneID='$data->StoneID'");
    echoSuccess("سنگ به لیست بازگشت");
});

$app->post('/soldStoneRange', function() use ($app)  {
    $data = json_decode($app->request->getBody());

    if(!isset($data->SSN) || !isset($data->StoneID)
        || !isset($data->StartSlab) || !isset($data->EndSlab))
        echoError("اطلاعات نا معتبر است");
    checkValidUser($app->db , $data->SSN);
    $stone = $app->db->getOneRecord("SELECT s.CopeID FROM Stone s WHERE s.StoneID = ".$data->StoneID);

    $resQ = $app->db->updateRecord('stone',"`Sold`='1'","stone.CopeID=".$stone["CopeID"]." and stone.CopeNumber <= $data->EndSlab and stone.CopeNumber >= $data->StartSlab");
    echoSuccess("سنگ ها فروخته شدند");
});
?>