<?php
$app->post('/insertOrEditCope', function() use ($app)  {

    $data = json_decode($app->request->getBody());
    checkUserPemision($app);
    $stone = $app->db->getOneRecord("SELECT * FROM cope c WHERE c.CopeName='".$data->CopeName."'");
    if($stone["CopeID"] && !isset($data->CopeID)){
        echoError("نام سنگ خام تکراری است");
    }
    if(isset($data->CopeID)){
        $app->db->updateRecord('cope',"CopeName='".$data->CopeName."',Weight='".$data->Weight
            ."',UnitPrice='".$data->UnitPrice."',StoneTypeID='".$data->StoneTypeID."'",
            "CopeID='$data->CopeID'");
        echoSuccess(' سنگ خام ویرایش شد');
    }else{
        $app->db->insertToTable('cope','CopeName,Weight,UnitPrice,StoneTypeID,CreationDate',
            "'".$data->CopeName."','".$data->Weight."','".$data->UnitPrice."','".$data->StoneTypeID."',now()");
        echoSuccess('سنگ خام اضافه شد');
    }
});
$app->post('/deleteCope', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    checkUserPemision($app);
    $res = $app->db->deleteFromTable('cope',"CopeID='$data->CopeID'");
    if($res)
        echoSuccess('سنگ خام حذف شد');
    else
        echoError("Cannot delete record.");
});
$app->post('/deleteStoneType', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    checkUserPemision($app);
    $res = $app->db->deleteFromTable('stone_type',"StoneTypeID='$data->StoneTypeID'");
    if($res)
        echoSuccess('نوع سنگ حذف شد');
    else
        echoError("Cannot delete record.");
});
$app->post('/getAllStoneTypes', function() use ($app)  {
    $sess = $app->session;
    if($sess->AdminPermissionLevel == "viewStones"){
        $res = $app->db->getRecords("SELECT * from stone_type WHERE StoneTypeID !=1");
    }else{
        $res = $app->db->getRecords("SELECT * from stone_type");
    }
    echoSuccess($res);
});
$app->post('/getAllCopeTypes', function() use ($app)  {
    $res = $app->db->getRecords("SELECT * from cope");
    echoSuccess($res);
});
$app->post('/getAllUserTypes', function() use ($app)  {
    checkUserPemision($app);
    $res = $app->db->getRecords("SELECT * from admin_permission");
    echoSuccess($res);
});
$app->post('/getSocketData', function() use ($app)  {
    $s = new Session();

    $resQ = $app->db->makeQuery("Select user.UserID,user.FullName,user.LastActiveTime,file_storage.FullPath as Image from user LEFT JOIN file_storage on
    file_storage.ID=user.AvatarID
 where UserAccepted=1 and user.ID!='$s->UserID' and user.LastActiveTime > NOW() - INTERVAL 3 MINUTE");

    $arr = [];
    $res = [];
    while($r = $resQ->fetch_assoc()){
        $arr[] = $r;
    }

    $res['OnlineUsers'] = $arr;
    echoResponse(200, $res);
});
$app->post('/deletePhone', function() use ($app)  {

    $data = json_decode($app->request->getBody());
    checkUserPemision($app);
    $qID =  $app->db->deleteFromTable('phone','PhoneID='.$data->PhoneID);
    if($qID)
        echoSuccess('گوشی با موفقیت حذف شد');
    else
        echoError();
});
$app->post('/deleteStone', function() use ($app)  {

    $data = json_decode($app->request->getBody());
    checkUserPemision($app);

    $qID =  $app->db->deleteFromTable('stone','StoneID='.$data->StoneID);
    if($qID)
        echoSuccess();
    else
        echoError();
});
$app->post('/getStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());

    if(!isset($data->StoneID))
        echoError("اطلاعات نا معتبر است");
    $stone = $app->db->getOneRecord("SELECT (SELECT ps.StoneID FROM stone as ps WHERE ps.CopeID = s.CopeID and ps.CopeNumber = (s.CopeNumber -1 )  LIMIT 1) as PreviousStoneID ,
(SELECT ns.StoneID FROM stone as ns WHERE ns.CopeID = s.CopeID and ns.CopeNumber = (s.CopeNumber +1 ) LIMIT 1) as NextStoneID ,
s.`StoneID`, st.`Name`,s.StoneTypeID, s.Height,s.Width , s.CreationDate, s.Image,
s.ImageSize, s.Description, s.Area, s.MachineNumber, s.CopeID, s.CopeNumber, c.CopeName,
s.Sold, s.`BockMachDirection` , s2.StoneID  as BookMatchStoneID1, s3.StoneID as BookMatchStoneID2
FROM stone s 
 LEFT JOIN cope as c on c.CopeID = s.`CopeID`
 inner JOIN stone_type st on st.StoneTypeID = s.StoneTypeID
 LEFT JOIN stone as s2 on s2.StoneID = s.BookMatchStoneID
  LEFT JOIN stone as s3 on s3.BookMatchStoneID = s.StoneID WHERE s.StoneID = ".$data->StoneID);
    if($stone["StoneID"]){
        $stone["Image"] = base64_encode($stone["Image"]);
        echoSuccess($stone);
    }
    else
        echoError("سنگ وجود ندارد");
    echoSuccess($stone);
});
$app->post('/getAllStonesWithImage', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $sess = $app->session;
    $where = "WHERE 1=1 ";
    if(isset($data->StartDate)){
        $where .=" AND (s.`CreationDate` > '$data->StartDate')";
    }

    if(isset($data->EndDate)){
        $where .=" AND (s.`CreationDate` < '$data->EndDate')";
    }

    if(isset($data->StoneTypeID)){
        $where .=" AND (s.StoneTypeID in $data->StoneTypeID )";
    }

    if(isset($data->MachineID)){
        $where .=" AND (s.MachineNumber ='$data->MachineID')";
    }

    if(isset($data->StoneStateID)){
        if($data->StoneStateID == "1"){
            $where .=" AND (s.Sold ='0')";
        }

        if($data->StoneStateID == "2"){
            $where .=" AND (s.Sold ='1')";
        }
    }

    if(isset($data->Height) && strlen($data->Height) > 0){
        $where .= " AND (s.Height > '$data->Height' )";
    }

    if(isset($data->Width) && strlen($data->Width) > 0){
        $where .= " AND (s.Width > '$data->Width' )";
    }

    if(isset($data->Area) && strlen($data->Area) > 0){
        $where .= " AND (s.Area > '$data->Area' )";
    }
    if(isset($data->HasBookMatch)){
        if($data->HasBookMatch == "true"){
            $where .= " AND (s.BookMatchStoneID != 0 )";
        }
    }

    if(isset($data->CopeName) && strlen($data->CopeName) > 0){
        $s = mb_convert_encoding($data->CopeName, "UTF-8", "auto");
        $where .= " AND (CopeName LIKE '%".$s."%')";
    }

    if($sess->AdminPermissionLevel == "viewStones"){
        $where .= " AND (st.StoneTypeID !=1)";
    }

    $resq = $pr->getPage($app->db,"SELECT s.Image, s.StoneID,s.`Height`,s.`Width`,s.`CreationDate`,s.`Description`,s.`ImageSize`,s.`Area`,s.`MachineNumber`,
s.`BookMatchStoneID`,s.`CopeNumber`,s.`Sold` ,s.`BockMachDirection`,st.Name , c.CopeName FROM `stone` s  LEFT JOIN cope as c on c.CopeID = s.`CopeID`
 INNER JOIN stone_type st on st.StoneTypeID = s.`StoneTypeID`".$where." ORDER BY s.StoneID DESC");
    if($resq){
        echoResponse(200,$resq);
    }
    else
        echoError();
});
$app->post('/getAllCopes', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    checkUserPemision($app);
    $resq = $pr->getPage($app->db,"SELECT c.*, st.Name ,(SELECT COUNT(*) from stone s where s.CopeID = c.CopeID ) as StoneCount FROM cope c  
INNER JOIN stone_type st on st.StoneTypeID = c.StoneTypeID WHERE CopeID != 10000 ORDER BY c.CopeID DESC");
    if($resq)
        echoResponse(200,$resq);
    else
        echoError();
});
$app->post('/getAllStones', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $sess = $app->session;
    $where = "WHERE 1=1";
    if($sess->AdminPermissionLevel == "viewStones"){
        $where .= " AND (st.StoneTypeID !=1)";
    }
    $resq = $pr->getPage($app->db,"SELECT s.StoneID,s.`Height`,s.`Width`,s.`CreationDate`,s.`Description`,s.`ImageSize`,s.`Area`,s.`MachineNumber`,
s.`BookMatchStoneID`,s.`CopeNumber`,s.`Sold` ,s.`BockMachDirection`,st.Name , c.CopeName FROM `stone` s  
LEFT JOIN cope as c on c.CopeID = s.`CopeID` INNER JOIN stone_type st on st.StoneTypeID = s.`StoneTypeID` ".$where." ORDER BY s.StoneID DESC");
    if($resq)
        echoResponse(200,$resq);
    else
        echoError();
});
$app->post('/deleteUser', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    $sess = $app->session;
    checkUserPemision($app);
    if($sess->UserID == $data->UserID)
        echoError("شما خودتان را نمی توانید حذف کنید");

    if($data->UserID == "1" || $data->UserID == "2")
        echoError("این کاربر قابل پاک کردن نیست");

    $res = $app->db->deleteFromTable('user',"UserID='$data->UserID'");
    if($res)
        echoSuccess('کاربر حذف شد');
    else
        echoError("Cannot delete record.");
});
$app->post('/savePerson', function() use ($app) {
    $r = json_decode($app->request->getBody());
    checkUserPemision($app);
    require_once '../passwordHash.php';
    $password = $r->pass;
    $r->pass = passwordHash::hash($password);

    $resq = $app->db->makeQuery("select COUNT(*) as resp FROM user WHERE Email = '$r->Email' OR Username = '$r->Username'");
    $res = $resq->fetch_assoc();

    if ($res['resp'] > 0 )
    {
        echoError('ایمیل یا نام کاربری تکراری است');
    }

    $userID = $app->db->insertToTable('user','FullName,Email,Password,Username,SignupDate'
        ,"'$r->FullName','$r->Email','$r->pass','$r->Username',now()",true);
    $adminID = $app->db->insertToTable('admin','UserID,PermissionID'
        ,"'$userID','$r->PermissionID'",true);

    if($userID)
        echoSuccess('کاربر اضافه شد');
    else
        echoError('مشکل در اضافه کردن کاربر');
});
$app->post('/savePhone', function() use ($app) {

    $r = json_decode($app->request->getBody());
    checkUserPemision($app);
    require_once '../passwordHash.php';
    $password = $r->pass;
    $r->pass = passwordHash::hash($password);

    $resq = $app->db->makeQuery("select COUNT(*) as resp FROM phone WHERE Username = '$r->Username'");
    $res = $resq->fetch_assoc();

    if ($res['resp'] > 0 )
    {
        echoError('نام کاربری تکراری است');
    }

    $userID = $app->db->insertToTable('phone','Password,Username,SignupDate'
        ,"'$r->pass','$r->Username',now()",true);

    if($userID)
        echoSuccess('گوشی اضافه شد');
    else
        echoError('مشکل در اضافه کردن گوشی');
});
$app->post('/getAllUsers', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);

    checkUserPemision($app);

    $pageRes = $pr->getPage($app->db,"SELECT 
user.`UserID`, `FullName`, `Email`, `Username`,ap.Description as permision,
`PhoneNumber`, `SignupDate`, user.`Description`, `SessionID`, `ValidSessionID`, user.LastActiveTime, user.SignupDate , file_storage.FullPath
FROM user LEFT JOIN file_storage on file_storage.FileStorageID = user.AvatarID
 INNER JOIN admin as a on a.UserID = user.UserID
INNER JOIN admin_permission as ap on ap.AdminPermissionID = a.PermissionID");

    echoResponse(200, $pageRes);
});
$app->post('/getAllPhones', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    checkUserPemision($app);
    $pr = new Pagination($data);

    $pageRes = $pr->getPage($app->db,"SELECT 
`PhoneID`,`Username`, `SignupDate` FROM phone ORDER BY PhoneID desc");

    echoResponse(200, $pageRes);
});
$app->post('/saveUserInfo', function() use ($app)  {
    //adminRequire();
    $data = json_decode($app->request->getBody());
    checkUserPemision($app,"LimitFuctionality");
    $sess = $app->session;

    $resQ = $app->db->makeQuery("select 1 from user where Email='$data->Email' and UserID!='$sess->UserID' LIMIT 1");
    $count = mysqli_num_rows($resQ);
    if($count > 0){
        echoError("EmailExists");
    }

    if(isset($data->Password)){

        if(!isset($data->OldPassword)){
            echoError("OldPasswordIsNotValid");
        }else if(strlen($data->OldPassword) < 5){
            echoError("OldPasswordIsNotValid");
        }

        if(!isset($data->Password)){
            echoError("PasswordIsNotValid");
        }else if(strlen($data->Password) < 5){
            echoError("PasswordIsNotValid");
        }

        $userPassword = $app->db->makeQuery("SELECT user.Password from user where user.UserID='$sess->UserID'")->fetch_assoc()['Password'];
        if(passwordHash::check_password($userPassword,$data->OldPassword)){

            $pass = passwordHash::hash($data->Password);

            $resQ = $app->db->updateRecord('user',"`FullName`='$data->FullName',`Email`='$data->Email',`Username`='$data->Username',`PhoneNumber`='$data->PhoneNumber',`Password`='$pass'","user.UserID='$sess->UserID'");
        }else{
            echoError("OldPasswordIsNotValid");
        }
    }else{

        $resQ = $app->db->updateRecord('user',"`FullName`='$data->FullName',`Email`='$data->Email',`Username`='$data->Username',`PhoneNumber`='$data->PhoneNumber'","user.UserID='$sess->UserID'");
    }

    $sess->updateFullName($data->FullName);

    $res = [];
    if($resQ){
        $res["Status"] = "success";
        $res["FullName"] = $data->FullName;
    }
    echoResponse(200, $res);
});
$app->post('/deleteSession', function() use ($app)  {

    $data = json_decode($app->request->getBody());

    $sess= $app->session;

    $resQ = $app->db->makeQuery("select * from user_session where user_session.UserSessionID='".$data->UserSessionID."'");
    $s = $resQ->fetch_assoc();

    if($s['SessionID'] == $sess->SSN)
        echoError("CurrentSession");

    $res = $app->db->deleteFromTable('user_session',"UserSessionID='".$data->UserSessionID."'");

    if($res)
        echoSuccess();

    echoError("Cannot delete from table.");
});
$app->post('/getUserProfile', function() use ($app)  {
    require_once '../db/user_session.php';
    $sess = $app->session;

    $resQ = $app->db->makeQuery("SELECT user.`UserID`,user.Description, `FullName`, `Email`, `Username`, `PhoneNumber`,
    `SignupDate`, FullPath as AvatarImagePath 
 FROM user LEFT JOIN file_storage on file_storage.FileStorageID = AvatarID WHERE user.UserID = $sess->UserID");

    $user = $resQ->fetch_assoc();
    $user['ActiveSessions'] = getAllUserActiveSessions($app->db,$sess->UserID);

    echoResponse(200, $user);
});
$app->post('/insertOrEditStoneType', function() use ($app)  {

    $data = json_decode($app->request->getBody());
    checkUserPemision($app);
    if(isset($data->StoneTypeID)){
        $app->db->updateRecord('stone_type',"Name='".$data->Name."',PreFix='".$data->PreFix."'", "StoneTypeID='$data->StoneTypeID'");
        echoSuccess('نوع سنگ ویرایش شد');
    }else{
        $app->db->insertToTable('stone_type','Name,PreFix',
            "'".$data->Name."','".$data->PreFix."'");
        echoSuccess('نوع سنگ اضافه شد');
    }
});
$app->post('/getAllStoneTypesPaging', function() use ($app)  {
    checkUserPemision($app);
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $resq = $pr->getPage($app->db,"SELECT * , (SELECT COUNT(*) FROM stone s WHERE s.StoneTypeID = st.`StoneTypeID`)as UseCount 
FROM `stone_type` st WHERE st.StoneTypeID != 100000000");
    if($resq)
        echoResponse(200,$resq);
    else
        echoError();
});
$app->post('/editStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    checkUserPemision($app);
        $app->db->updateRecord('stone',"StoneTypeID='".$data->StoneTypeID
            ."',Width='".$data->Width
            ."',Height='".$data->Height
            ."',Description='".$data->Description
            ."',Area='".$data->Area
            ."',CopeID='".$data->CopeID
            ."',CopeNumber='".$data->CopeNumber
            ."',Sold='".$data->Sold
            ."',BockMachDirection='".$data->BockMachDirection
            ."',MachineNumber='".$data->MachineNumber."'",
            "StoneID='$data->StoneID'");
        echoSuccess(' سنگ ویرایش شد');
});
$app->post('/addStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    checkUserPemision($app);
    $app->db->insertToTable('stone','StoneTypeID,Width,Height,Description,Area,CopeID,CopeNumber,Sold,BockMachDirection,MachineNumber,CreationDate',
        "'".$data->StoneTypeID
        ."','".$data->Width
        ."','".$data->Height
        ."','".$data->Description
        ."','".$data->Area
        ."','".$data->CopeID
        ."','".$data->CopeNumber
        ."','".$data->Sold
        ."','".$data->BockMachDirection
        ."','".$data->MachineNumber
        ."',now()");
    echoSuccess(' سنگ اضافه شد');
});

?>