<?php
$app->post('/getStoneImage', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    if(!isset($data->SSN) || !isset($data->StoneID))
        echoError("bad request");
    checkValidUser($app->db , $data->SSN);
    $user = $app->db->getOneRecord("SELECT  s.Image 
FROM Stone s WHERE s.StoneID = ".$data->StoneID);
    if($user["Image"]){
        $Image = base64_encode($user["Image"]);
        echo $Image;
    }
    else
        echoError("dosent exist");
});

$app->post('/getStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    if(!isset($data->SSN) || !isset($data->StoneID))
        echoError("bad request");
    checkValidUser($app->db , $data->SSN);
    $stone = $app->db->getOneRecord("SELECT (SELECT ps.StoneID FROM stone as ps WHERE ps.CopeID = s.CopeID and ps.CopeNumber = (s.CopeNumber -1 ) and ps.StoneTypeID = s.StoneTypeID LIMIT 1) as PreviousStoneID ,
(SELECT ns.StoneID FROM stone as ns WHERE ns.CopeID = s.CopeID and ns.CopeNumber = (s.CopeNumber +1 ) and ns.StoneTypeID = s.StoneTypeID LIMIT 1) as NextStoneID ,
s.`StoneID`, st.`Name`, s.Height,s.Width , s.CreationDate, s.Image,
s.ImageSize, s.Description, s.Area, s.MachineNumber, s.CopeID, s.CopeNumber,
s.Sold, s.`BockMachDirection`
FROM Stone s inner JOIN stone_type st on st.StoneTypeID = s.StoneTypeID WHERE s.StoneID = ".$data->StoneID);
    if($stone["Image"]){
        $stone["Image"] = base64_encode($stone["Image"]);
        echoSuccess($stone);
    }
    else
        echoError("dosent exist");

echoSuccess($stone);
});


$app->post('/soldStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());

    if(!isset($data->SSN) || !isset($data->StoneID))
        echoError("bad request");
    checkValidUser($app->db , $data->SSN);
    $resQ = $app->db->updateRecord('stone',"`Sold`='1'","stone.StoneID='$data->StoneID'");
    echoSuccess("done");
});

$app->post('/unsoldImage', function() use ($app)  {
    $data = json_decode($app->request->getBody());

    if(!isset($data->SSN) || !isset($data->StoneID))
        echoError("bad request");
    checkValidUser($app->db , $data->SSN);
    $resQ = $app->db->updateRecord('stone',"`Sold`='0'","stone.StoneID='$data->StoneID'");
    echoSuccess("done");
});
?>