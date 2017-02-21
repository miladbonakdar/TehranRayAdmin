<?php
$app->post('/deleteUser', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    $sess = new Session();
    if($sess->UserID == $data->UserID)
        echoError("شما خودتان را نمی توانید حذف کنید");
    $res = $app->db->deleteFromTable('user',"UserID='$data->UserID'");
    if($res)
        echoSuccess('کاربر حذف شد');
    else
        echoError("Cannot delete record.");
});

$app->post('/savePerson', function() use ($app) {

    $r = json_decode($app->request->getBody());

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

    if($userID)
        echoSuccess('کاربر اضافه شد');
    else
        echoError('مشکل در اضافه کردن کاربر');
});
$app->post('/getAllUsers', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $sess = new Session();

    $pageRes = $pr->getPage($app->db,"SELECT 
user.`UserID`, `FullName`, `Email`, `Username`,
`PhoneNumber`, `SignupDate`, user.`Description`, `SessionID`, `ValidSessionID`, user.LastActiveTime, user.SignupDate , file_storage.FullPath
FROM user LEFT JOIN file_storage on file_storage.FileStorageID = user.AvatarID ORDER BY user.UserID desc");

    echoResponse(200, $pageRes);
});
$app->post('/saveUserInfo', function() use ($app)  {
    //adminRequire();
    $data = json_decode($app->request->getBody());

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

    if(isset($data->StoneTypeID)){
        $app->db->updateRecord('stone_type',"Name='".$data->Name."'", "StoneTypeID='$data->StoneTypeID'");
        echoSuccess('نوع سنگ ویرایش شد');
    }else{
        $app->db->insertToTable('stone_type','Name',
            "'".$data->Name."'");
        echoSuccess('نوع سنگ اضافه شد');
    }
});

$app->post('/getAllStoneTypes', function() use ($app)  {

    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $resq = $pr->getPage($app->db,"SELECT * , (SELECT COUNT(*) FROM stone s WHERE s.StoneTypeID = st.`StoneTypeID`)as UseCount FROM `stone_type` st ");
    if($resq)
        echoResponse(200,$resq);
    else
        echoError();
});


//////////////////////////////////////////old///////////////////////////////////////////////////
$app->post('/deletePopUp', function() use ($app)  {

    $data = json_decode($app->request->getBody());
    $sess = $app->session;
    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

    $qID =  $app->db->deleteFromTable('pop_up','ID='.$data->PopUpID);
    if($qID)
        echoSuccess();
    else
        echoError();
});
$app->post('/getAllPopUps', function() use ($app) {
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $resq = $pr->getPage($app->db,"SELECT * , (SELECT IF(p.`ExpireDate` > NOW(), 'true', 'false') )  AS PopUpState FROM `pop_up` p ORDER BY p.ID");
    if($resq)
        echoResponse(200,$resq);
    else
        echoError();
});
$app->post('/checkPopUp', function() use ($app) {
    $r = json_decode($app->request->getBody());
    $resq = $app->db->makeQuery("SELECT * FROM `pop_up` p WHERE p.ExpireDate > NOW() ORDER BY p.ID LIMIT 1");
    $res = $resq->fetch_assoc();
    if($res)
        echoSuccess($res);
    else
        echoError();
});
$app->post('/saveNewPopUp', function() use ($app)  {

    $data = json_decode($app->request->getBody());
    $sess = $app->session;
    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');
    
    $qID = $app->db->insertToTable('pop_up','Title,ModalText,CreationDate,ExpireDate',
        "'$data->Title','$data->ModalText',now(),'".$data->ExpireDate."'",true);
    if($qID)
        echoSuccess();
    else
        echoError();
});
$app->post('/getAllUserReciveMessages', function() use ($app)  {

    require_once '../db/message.php';

    $data = json_decode($app->request->getBody());
    $sess = $app->session;

    $pin = new PaginationInput($data);
    $res = getPageUserMessages($app->db,$sess->UserID,$pin);

    echoResponse(200, $res);
});
$app->post('/getSocketData', function() use ($app)  {
$s = new Session();

$resQ = $app->db->makeQuery("Select user.ID,user.FullName,user.LastActiveTime,file_storage.FullPath as Image from user LEFT JOIN file_storage on
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
$app->post('/getReportChartData', function() use ($app)  {

    require_once "../db/forum_subject.php";
    require_once "../db/organ_position.php";

    $data = json_decode($app->request->getBody());

    if(isset($data->OrganizationID)){
        $resQ = $app->db->makeQuery("select u.ID , u.FullName as OrganizationName,
    (select count(*) from user
  where organ_position.ID=user.OrganizationID and user.UserAccepted =1) as UserTotal,
  sum((select count(*) from forum_question
  where forum_question.AuthorID=u.ID and forum_question.AdminAccepted =1) ) as QTotal,
  sum((select count(*) from forum_question
  where forum_question.AuthorID=u.ID and forum_question.AdminAccepted =0) ) as QTotalNA,
  sum((select count(*) from forum_answer
    inner join forum_question on forum_question.ID=forum_answer.QuestionID
  where forum_answer.AuthorID=u.ID and forum_answer.AdminAccepted =1
        and forum_question.AdminAccepted =1) ) as ATotal,
  sum((select count(*) from forum_answer
    inner join forum_question on forum_question.ID=forum_answer.QuestionID
  where forum_answer.AuthorID=u.ID and forum_answer.AdminAccepted =0
        and forum_question.AdminAccepted =1) ) as ATotalNA
from organ_position
  LEFT JOIN user as u on u.OrganizationID=organ_position.ID
  where u.OrganizationID = '$data->OrganizationID'
GROUP BY u.ID
ORDER by u.score DESC");

        $cqDataStack = [];
        while($r = $resQ->fetch_assoc()){
            $r['ATotal'] = -$r['ATotal'];
            $r['ATotalNA'] = -$r['ATotalNA'];
//
            $r['ATotal'] += $r['ATotalNA'];
            $r['QTotal'] += $r['QTotalNA'];
            $cqDataStack[] = $r;
        }
        $resp['StackChartData'] = $cqDataStack;

//        $resp['MainSubjects'] = getAllMainSubjects($app->db);
//        $resp['Organs'] = getAllPositions($app->db);

        echoResponse(200, $resp);
    }else
    {
        $resQ = $app->db->makeQuery("select organ_position.ID , organ_position.OrganizationName,
    (select count(*) from user
  where organ_position.ID=user.OrganizationID and user.UserAccepted =1) as UserTotal,
  sum((select count(*) from forum_question
  where forum_question.AuthorID=u.ID and forum_question.AdminAccepted =1) ) as QTotal,
  sum((select count(*) from forum_question
  where forum_question.AuthorID=u.ID and forum_question.AdminAccepted =0) ) as QTotalNA,
  sum((select count(*) from forum_answer
    inner join forum_question on forum_question.ID=forum_answer.QuestionID
  where forum_answer.AuthorID=u.ID and forum_answer.AdminAccepted =1
        and forum_question.AdminAccepted =1) ) as ATotal,
  sum((select count(*) from forum_answer
    inner join forum_question on forum_question.ID=forum_answer.QuestionID
  where forum_answer.AuthorID=u.ID and forum_answer.AdminAccepted =0
        and forum_question.AdminAccepted =1) ) as ATotalNA
from organ_position
  LEFT JOIN user as u on u.OrganizationID=organ_position.ID
GROUP BY organ_position.ID
ORDER by organ_position.ID asc");

        $cqDataStack = [];
        while($r = $resQ->fetch_assoc()){
            $r['ATotal'] = -$r['ATotal'];
            $r['ATotalNA'] = -$r['ATotalNA'];
//
            $r['ATotal'] += $r['ATotalNA'];
            $r['QTotal'] += $r['QTotalNA'];
            $cqDataStack[] = $r;
        }
        $resp['StackChartData'] = $cqDataStack;

//        $resp['MainSubjects'] = getAllMainSubjects($app->db);
//        $resp['Organs'] = getAllPositions($app->db);

        echoResponse(200, $resp);
    }
});
$app->post('/getUsersReport', function() use ($app)  {


    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $sess = new Session();

    $where = "WHERE (user.UserAccepted ='1')";
//    if(isset($data->filter)){
//        $where .=" AND (Username LIKE '%".$data->filter."%' OR FullName LIKE '%".$data->filter."%' OR Email LIKE '%".$data->filter."%')";
//    }
    if(isset($data->OrganizationID)){
        $where .=" AND (user.OrganizationID ='$data->OrganizationID')";}
    if(isset($data->ForumMainSubjectID)){
        $pageRes = $pr->getPage($app->db,"SELECT (SELECT COUNT(*) FROM forum_answer as fa 
INNER JOIN forum_question fq on fq.ID = fa.QuestionID
INNER JOIN forum_subject fss on fss.ID = fq.SubjectID
 WHERE fa.AuthorID = user.`ID` AND fa.AdminAccepted =1 and fss.ParentSubjectID = '$data->ForumMainSubjectID' ) as AnswerCount ,
(SELECT COUNT(*) FROM forum_question as fq
INNER JOIN forum_subject fss on fss.ID = fq.SubjectID
 WHERE fq.AuthorID = user.`ID` AND fq.AdminAccepted =1 and fss.ParentSubjectID = '$data->ForumMainSubjectID') as QuestionCount ,
(SELECT COUNT(*) FROM person_follow as pf WHERE pf.TargetUserID = user.`ID`) as FollowersCount ,
(SELECT COUNT(*) FROM person_follow as pf WHERE pf.UserID = user.`ID`) as FollowingCount ,
user.`ID`, `FullName`, `Email`, `Username`,op.ID as OrganID , op.OrganizationName,
`PhoneNumber`, `Tel`, `SignupDate`, `Gender`, user.`Description`,
`MailAccepted`, `ValidSessionID`, `UserAccepted`, user.LastActiveTime, user.SignupDate , user.score , file_storage.FullPath
FROM user LEFT  JOIN organ_position as op on op.ID = user.OrganizationID
LEFT JOIN file_storage on file_storage.ID = user.AvatarID ".$where." ORDER BY AnswerCount+QuestionCount DESC");

        echoResponse(200, $pageRes);
    }else
    {
        $pageRes = $pr->getPage($app->db,"SELECT (SELECT COUNT(*) FROM forum_answer as fa WHERE fa.AuthorID = user.`ID` AND fa.AdminAccepted =1) as AnswerCount ,
(SELECT COUNT(*) FROM forum_question as fq WHERE fq.AuthorID = user.`ID` AND fq.AdminAccepted =1) as QuestionCount ,
(SELECT COUNT(*) FROM person_follow as pf WHERE pf.TargetUserID = user.`ID`) as FollowersCount ,
(SELECT COUNT(*) FROM person_follow as pf WHERE pf.UserID = user.`ID`) as FollowingCount ,
user.`ID`, `FullName`, `Email`, `Username`,op.ID as OrganID , op.OrganizationName,
`PhoneNumber`, `Tel`, `SignupDate`, `Gender`, user.`Description`,
`MailAccepted`, `ValidSessionID`, `UserAccepted`, user.LastActiveTime, user.SignupDate , user.score , file_storage.FullPath
FROM user LEFT  JOIN organ_position as op on op.ID = user.OrganizationID
LEFT JOIN file_storage on file_storage.ID = user.AvatarID ".$where." ORDER BY user.score DESC");

        echoResponse(200, $pageRes);
    }


});
$app->post('/getAllAwardedQuestions', function() use ($app)  {


    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $sess = new Session();

    $pageRes = $pr->getPage($app->db,"SELECT fq.* ,aq.IsFinished,fs.Title as SubjectName ,u.FullName , lq.TargetQuestionID ,u.Email ,fis.FullPath ,u.ID as UserID
FROM forum_question as fq
INNER JOIN forum_subject as fs on fs.ID = fq.SubjectID
INNER JOIN forum_main_subject as fms on fms.ID = fs.ParentSubjectID
INNER join user as u on u.ID = fq.AuthorID
INNER JOIN avarded_question aq on aq.ForumQuestionID = fq.ID
LEFT JOIN file_storage as fis on fis.ID = u.AvatarID
LEFT JOIN link_question as lq on lq.LinkedQuestionID = fq.ID GROUP BY fq.ID ORDER BY fq.ID desc");

    echoResponse(200, $pageRes);
});
$app->post('/saveAwardQuestion', function() use ($app)  {
    //TODO CHANGE THIS FUNCTION
    $data = json_decode($_POST['formData']);

    $sess = new Session();

    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

    $qID = -1;
    if(isset($data->ID)){
        $qID = $data->ID;
        $app->db->updateRecord('forum_question',"SubjectID='".$data->Subject->ID."',QuestionText='$data->QuestionText',
        Title='$data->Title'", "ID='$qID'");
        $d = $app->db->deleteFromTable('tag_question','QuestionID='.$qID);
    }else{
        $qID = $app->db->insertToTable('forum_question','QuestionText,Title,SubjectID,AuthorID,CreationDate,AdminAccepted',
            "'$data->QuestionText','$data->Title','".$data->Subject->ID."','".$sess->UserID."',NOW(),1",true);
        $app->db->insertToTable('avarded_question','ForumQuestionID',$qID);

        if (isset($_FILES['file'])) {
            $file_ary = reArrayFiles($_FILES['file']);

            if (!file_exists('../content/user_upload/')) {
                mkdir('../content/user_upload/', 0777, true);
            }

            foreach ($file_ary as $file) {
                $filename = $file['name'];
                $rand = generateRandomString(18);
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                $destination ='content/user_upload/'.$rand.'.'.$ext;
                $uploadSuccess = move_uploaded_file( $file['tmp_name'] , '../'.$destination );
                if($uploadSuccess){
                    $fileTypeQ = $app->db->makeQuery("select file_type.ID from file_type where file_type.TypeName='$ext'");

                    $fileTypeID = -1;
                    if(mysqli_num_rows($fileTypeQ) > 0)
                        $fileTypeID = $fileTypeQ->fetch_assoc()['ID'];

                    $fileSize = $file['size'] / 1024;
                    $fid = $app->db->insertToTable('file_storage','AbsolutePath,FullPath,Filename,IsAvatar,UserID,FileTypeID,
                FileSize,UploadDate',
                        "'$destination','../$destination','$filename','0','$sess->UserID','$fileTypeID','$fileSize',NOW()",
                        true);

                    $app->db->insertToTable('question_attachment','QuestionID,FileID',
                        "'$qID','$fid'");
                }
            }
        }
    }

    if(isset($data->Tags)){

        foreach($data->Tags as $tag){
            $cq = $app->db->insertToTable('tag_question',"TagID,QuestionID","'$tag->ID','$qID'");
        }
    }

    $resQ = $app->db->makeQuery("SELECT user.* FROM user ");
    $users = [];
    while($r = $resQ->fetch_assoc())
        $users[] = $r;
    foreach ($users as $user){
        $app->db->insertToTable('event','EventUserID,EventTypeID , EventDate , EventCauseID , EvenLinkID',$user['ID'].",12,now(),$sess->UserID,$qID");
    }
    $res = [];
    $res['Status'] ='success';
    $res['QuestionID'] = $qID;

    echoResponse(200, $res);
});

$app->post('/getQuestionMetaEdit', function() use ($app)  {
    //userRequire();
    require_once '../db/tag.php';
    require_once '../db/forum_subject.php';
    require_once '../db/question_attachment.php';
    $data = json_decode($app->request->getBody());

    $res = [];

    if(isset($data)) {
        $resQ = $app->db->makeQuery("select * from forum_question where forum_question.ID='$data->QuestionID'");
        $res['Question'] = $resQ->fetch_assoc();

        $res['Question']['Subject'] = getQuestionSubject($app->db , $data->QuestionID);
        $res['Question']['MainSubject'] = getSubjectParent($app->db , $res['Question']['Subject']['ID']);
        $res['Question']['Tags'] = getQuestionTags($app->db , $data->QuestionID);
        $res['Question']['Attachments'] = getQuestionAttachments($app->db , $data->QuestionID);
    }

    $res['AllTags'] = getAllTags($app->db);
    $res['AllSubjects'] = getAllMainSubjectsWithChilds($app->db);

    echoResponse(200, $res);
});

$app->post('/getUploadLibraryData', function() use ($app)  {
    require_once '../db/forum_subject.php';
    $resp = [];
    $resp['ForumMainSubjects'] = getAllMainSubjectsWithChilds($app->db);

    echoResponse(200, $resp);
});

//$app->post('/saveLibraryFile', function() use ($app)  {
//
//    $filename = $_FILES['file']['name'];
//    $meta = $_POST;
//    move_uploaded_file( $_FILES['file']['tmp_name'] , '../files/'.$filename );
//
//    echoResponse(200, null);
//});

$app->post('/getNewQuestionsGraphData', function() use ($app)  {

    $r = json_decode($app->request->getBody());
    //$session = $app->session;

    $resQ = null;

    $curDate = date('Y-m-d');
    $dateWhere = '';
    if(isset($r->toDate) && isset($r->fromDate)){
        $dateWhere = "cd.IntervalDay BETWEEN '".$r->fromDate."' and '".$r->toDate."'";
    }else if(isset($r->fromDate)){
        $resCQ = $app->db->makeQuery("select * from calendar_day where calendar_day.IntervalDay=Date('$r->fromDate')");
        $cid  = $resCQ->fetch_assoc()['ID'];

        $dateWhere = "cd.ID BETWEEN ".($cid)." and ".($cid+30);
    }else if(isset($r->toDate)){
        $resCQ = $app->db->makeQuery("select * from calendar_day where calendar_day.IntervalDay=Date('$r->toDate')");
        $cid  = $resCQ->fetch_assoc()['ID'];

        $dateWhere = "cd.ID BETWEEN ".($cid - 30)." and ".($cid);
    }else{

        $resCQ = $app->db->makeQuery("select * from calendar_day where calendar_day.IntervalDay='$curDate'");
        $cid  = $resCQ->fetch_assoc()['ID'];
        $dateWhere = "cd.ID BETWEEN ".($cid - 30)." and ".($cid);
    }
    //echoSuccess($where);

    $filterMainSubject = isset($r->MainSubjectID) && $r->MainSubjectID != -1;
    $filterOrgan = isset($r->OrganizationID) && $r->OrganizationID != -1;

    if($filterMainSubject && $filterOrgan){

        $resQ = $app->db->makeQuery("
SELECT cd.IntervalDay as date,
  (select count(*) from forum_question
  left join user on forum_question.AuthorID=user.ID
  left join forum_subject on forum_subject.ID=forum_question.SubjectID
  left join forum_main_subject on forum_main_subject.SubjectID=forum_subject.ParentSubjectID
  where forum_question.AdminAccepted=1 and forum_main_subject.SubjectID='$r->MainSubjectID'
  and user.OrganizationID = '$r->OrganizationID'
  and Date(forum_question.CreationDate) = cd.IntervalDay) as QuestionCount
  ,
  (select count(*) from forum_answer
  left join forum_question on forum_answer.QuestionID=forum_question.ID
  left join user on forum_question.AuthorID=user.ID
  left join forum_subject on forum_subject.ID=forum_question.SubjectID
  left join forum_main_subject on forum_main_subject.SubjectID=forum_subject.ParentSubjectID
  where forum_answer.AdminAccepted=1 and forum_main_subject.SubjectID='$r->MainSubjectID'
  and user.OrganizationID = '$r->OrganizationID'
  and Date(forum_answer.CreationDate) = cd.IntervalDay) 
  as AnswerCount,
   IF(cd.IntervalDay = '$curDate','pulseBullet red-pulse' , '') as className
from calendar_day as cd
where $dateWhere");
    }
    else if($filterOrgan){

        $resQ = $app->db->makeQuery("
SELECT cd.IntervalDay as date,
  (select count(*) from forum_question
  left join user on forum_question.AuthorID=user.ID
  where forum_question.AdminAccepted=1
  and user.OrganizationID = '$r->OrganizationID'
  and Date(forum_question.CreationDate) = cd.IntervalDay) as QuestionCount
  ,
  (select count(*) from forum_answer
  left join forum_question on forum_answer.QuestionID=forum_question.ID
  left join user on forum_question.AuthorID=user.ID
  where forum_answer.AdminAccepted=1
  and user.OrganizationID = '$r->OrganizationID'
  and Date(forum_answer.CreationDate) = cd.IntervalDay) 
  as AnswerCount,
   IF(cd.IntervalDay = '$curDate','pulseBullet red-pulse' , '') as className
from calendar_day as cd
where $dateWhere");
    }
    else if($filterMainSubject){

        $resQ = $app->db->makeQuery("
SELECT cd.IntervalDay as date,
  (select count(*) from forum_question
  left join forum_subject on forum_subject.ID=forum_question.SubjectID
  left join forum_main_subject on forum_main_subject.SubjectID=forum_subject.ParentSubjectID
  where forum_question.AdminAccepted=1 and forum_main_subject.SubjectID='$r->MainSubjectID'
  and Date(forum_question.CreationDate) = cd.IntervalDay) as QuestionCount
  ,
  (select count(*) from forum_answer
  left join forum_question on forum_answer.QuestionID=forum_question.ID
  left join forum_subject on forum_subject.ID=forum_question.SubjectID
  left join forum_main_subject on forum_main_subject.SubjectID=forum_subject.ParentSubjectID
  where forum_answer.AdminAccepted=1 and forum_main_subject.SubjectID='$r->MainSubjectID'
  and Date(forum_answer.CreationDate) = cd.IntervalDay) 
  as AnswerCount,
   IF(cd.IntervalDay = '$curDate','pulseBullet red-pulse' , '') as className
from calendar_day as cd
where $dateWhere");
    }
    else{
        $resQ = $app->db->makeQuery("
SELECT cd.IntervalDay as date,
  (select count(*) from forum_question
  where forum_question.AdminAccepted=1 
  and Date(forum_question.CreationDate) = cd.IntervalDay) as QuestionCount
  ,
  (select count(*) from forum_answer
  where forum_answer.AdminAccepted=1
  and Date(forum_answer.CreationDate) = cd.IntervalDay) 
  as AnswerCount,
   IF(cd.IntervalDay = '$curDate','pulseBullet red-pulse' , '') as className
from calendar_day as cd
where $dateWhere");
    }

    $maxA = $maxQ = -1;
    $maxAIndex = $maxQIndex = -1;
    $i = 0;

    $cqData = [];
    while($r = $resQ->fetch_assoc()){
        if($r['AnswerCount'] >= $maxA){
            $maxAIndex = $i;
            $maxA = $r['AnswerCount'];
        }
        if($r['QuestionCount'] >= $maxQ){
            $maxQIndex = $i;
            $maxQ = $r['QuestionCount'];
        }
        $cqData[] = $r;
        $i++;
    }
    $cqData[$maxQIndex]['classNameQ'] = 'pulseBullet red-pulse';
    $cqData[$maxAIndex]['classNameA'] = 'pulseBullet blue-pulse';

    echoResponse(200, $cqData);
});

$app->post('/getDashboardData', function() use ($app)  {

    require_once "../db/forum_subject.php";
    require_once "../db/organ_position.php";

    $r = json_decode($app->request->getBody());
    $session = $app->session;

    $curDate = date('Y-m-d');
    $resCQ = $app->db->makeQuery("select * from calendar_day where calendar_day.IntervalDay='$curDate'");
    $cid  = $resCQ->fetch_assoc()['ID'];

    $resQ = $app->db->makeQuery("

SELECT cd.IntervalDay as date,
  (select count(*) from forum_question
  where forum_question.AdminAccepted=1 
  and Date(forum_question.CreationDate) = cd.IntervalDay) as QuestionCount
  ,
  (select count(*) from forum_answer
  where forum_answer.AdminAccepted=1
  and Date(forum_answer.CreationDate) = cd.IntervalDay) 
  as AnswerCount,
   IF(cd.IntervalDay = '$curDate','pulseBullet red-pulse' , '') as classNameQ
from calendar_day as cd
where cd.ID BETWEEN ".($cid - 30)." and ".($cid));

    $maxA = $maxQ = -1;
    $maxAIndex = $maxQIndex = -1;
    $i = 0;
    $resp= [];
    $cqData = [];
    while($r = $resQ->fetch_assoc()){
        if($r['AnswerCount'] >= $maxA){
            $maxAIndex = $i;
            $maxA = $r['AnswerCount'];
        }
        if($r['QuestionCount'] >= $maxQ){
            $maxQIndex = $i;
            $maxQ = $r['QuestionCount'];
        }
        $cqData[] = $r;
        $i++;
    }
    $cqData[$maxQIndex]['classNameQ'] = 'pulseBullet red-pulse';
    $cqData[$maxAIndex]['classNameA'] = 'pulseBullet blue-pulse';
    $resp['ChartData'] = $cqData;

    $resQ = $app->db->makeQuery("
SELECT cd.IntervalDay as date,
  (select count(*) from forum_question
  where forum_question.AdminAccepted=1 
  and Date(forum_question.CreationDate) < cd.IntervalDay) as IQuestionCount
  ,
  (select count(*) from forum_answer
  where forum_answer.AdminAccepted=1
  and Date(forum_answer.CreationDate) < cd.IntervalDay) 
  as IAnswerCount,
   IF(cd.IntervalDay = '$curDate','pulseBullet' , '') as className
from calendar_day as cd
where cd.ID BETWEEN ".($cid - 30)." and ".($cid));

    $cqDataInc = [];
    while($r = $resQ->fetch_assoc()){
        $cqDataInc[] = $r;
    }

    $resp['ChartDataInc'] = $cqDataInc;

    $resQ = $app->db->makeQuery("
select forum_main_subject.ID , forum_main_subject.Title , forum_main_subject.SubjectName,
  sum((select count(*) from forum_question
  where forum_question.SubjectID=forum_subject.ID and forum_question.AdminAccepted =1) )as QTotal,
  sum((select count(*) from forum_answer
    inner join forum_question on forum_question.ID=forum_answer.QuestionID
  where forum_question.SubjectID=forum_subject.ID and forum_answer.AdminAccepted =1
        and forum_question.AdminAccepted =1) )as ATotal
from forum_main_subject
  LEFT JOIN forum_subject on forum_subject.ParentSubjectID=forum_main_subject.SubjectID
GROUP BY forum_main_subject.ID");

    $cqDataRadar = [];
    while($r = $resQ->fetch_assoc())
        $cqDataRadar[] = $r;
    $resp['RadarChartData'] = $cqDataRadar;


    $resQ = $app->db->makeQuery("select organ_position.ID , organ_position.OrganizationName,
    (select count(*) from user
  where organ_position.ID=user.OrganizationID and user.UserAccepted =1) as UserTotal,
  sum((select count(*) from forum_question
  where forum_question.AuthorID=u.ID and forum_question.AdminAccepted =1) ) as QTotal,
  sum((select count(*) from forum_question
  where forum_question.AuthorID=u.ID and forum_question.AdminAccepted =0) ) as QTotalNA,
  sum((select count(*) from forum_answer
    inner join forum_question on forum_question.ID=forum_answer.QuestionID
  where forum_answer.AuthorID=u.ID and forum_answer.AdminAccepted =1
        and forum_question.AdminAccepted =1) ) as ATotal,
  sum((select count(*) from forum_answer
    inner join forum_question on forum_question.ID=forum_answer.QuestionID
  where forum_answer.AuthorID=u.ID and forum_answer.AdminAccepted =0
        and forum_question.AdminAccepted =1) ) as ATotalNA
from organ_position
  LEFT JOIN user as u on u.OrganizationID=organ_position.ID
GROUP BY organ_position.ID
ORDER by organ_position.ID asc");

    $cqDataStack = [];
    while($r = $resQ->fetch_assoc()){
        $r['ATotal'] = -$r['ATotal'];
        $r['ATotalNA'] = -$r['ATotalNA'];
//
        $r['ATotal'] += $r['ATotalNA'];
        $r['QTotal'] += $r['QTotalNA'];
        $cqDataStack[] = $r;
    }
    $resp['StackChartData'] = $cqDataStack;

    $resp['MainSubjects'] = getAllMainSubjects($app->db);
    $resp['Organs'] = getAllPositions($app->db);

    echoResponse(200, $resp);
});

$app->post('/getAllTags', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());
	$pr = new Pagination($data);

	$pageRes = $pr->getPage($app->db,"SELECT t.* , (SELECT COUNT(*) FROM tag_question as tq WHERE tq.TagID= t.ID) as UseCount
FROM tag as t  ORDER BY t.ID desc");

	echoResponse(200, $pageRes);
});

$app->post('/getAllSkills', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());
	$pr = new Pagination($data);

	$pageRes = $pr->getPage($app->db,"SELECT t.* , (SELECT COUNT(*) FROM user_skill as tq WHERE tq.SkillID= t.ID) as UseCount
FROM skill as t  ORDER BY t.ID desc");

	echoResponse(200, $pageRes);
});

$app->post('/deleteTag', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();

    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->deleteFromTable('tag' , "ID = $data->ID");
    $res2 = $app->db->deleteFromTable('tag_question' , "TagID = $data->ID");
	if($res && $res2)
		echoSuccess();
	else
		echoError("Cannot delete record.");
});
$app->post('/insertMessage', function() use ($app)  {


	$data = json_decode($app->request->getBody());
    if(!isset($data->MessageType) || !isset($data->MessageName))
        echoError('bad request');
	$sess = new Session();

    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');
        $res = $app->db->insertToTable('common_message','Message,MessageTitle',
                   "'$data->MessageName','$data->MessageType'");
	if($res)
		echoSuccess();
	else
		echoError("Cannot update record.");
});
$app->post('/insertSkill', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();

    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');
    $object = (object) [
            'Skill' => $data->Skill
          ];

    $column_names = array( 'Skill');

	$res = $app->db->insertIntoTable($object ,$column_names,'skill');
	if($res)
		echoSuccess();
	else
		echoError("Cannot update record.");
});

$app->post('/insertTag', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();

    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');
    $object = (object) [
            'Text' => $data->Text
          ];

    $column_names = array( 'Text');

	$res = $app->db->insertIntoTable($object ,$column_names,'tag');
	if($res)
		echoSuccess();
	else
		echoError("Cannot update record.");
});

$app->post('/getAllEducations', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());
	$pr = new Pagination($data);

	$pageRes = $pr->getPage($app->db,"SELECT e.* , (SELECT COUNT(*) FROM user_education as ue WHERE ue.EducationID = e.ID) as UseCount
FROM education as e  ORDER BY e.ID desc");

	echoResponse(200, $pageRes);
});

$app->post('/deleteEducation', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();

    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->deleteFromTable('education' , "ID = $data->ID");
    $res2 = $app->db->deleteFromTable('user_education' , "EducationID = $data->ID");
	if($res && $res2)
		echoSuccess();
	else
		echoError("Cannot delete record.");
});
$app->post('/deleteSkill', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();

    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->deleteFromTable('skill' , "ID = $data->ID");
    $res2 = $app->db->deleteFromTable('user_skill' , "SkillID = $data->ID");
	if($res && $res2)
		echoSuccess();
	else
		echoError("Cannot delete record.");
});

$app->post('/insertEducation', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();

    $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');
    $object = (object) [
            'Name' => $data->Name
          ];

    $column_names = array( 'Name');

	$res = $app->db->insertIntoTable($object ,$column_names,'education');
	if($res)
		echoSuccess();
	else
		echoError("Cannot update record.");
});


$app->post('/changeUserAccepted', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	if(isset($data->State)){
		if($data->State=='1' || $data->State=='-1'){
			$sess = new Session();

			if($sess->UserID == $data->UserID)
				echoError('You cannot change your own accepted state.');

                $resQ = $app->db->makeQuery("select Count(*) as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base'");

    $sql =$resQ->fetch_assoc();
    if($sql['val'] == 0)
        echoError('You don\'t have permision to do this action');

			$res = $app->db->updateRecord('user',"UserAccepted='$data->State'","ID='$data->UserID'");

			if($res){
                if($data->State == 1){
                    $resQ = $app->db->makeQuery("SELECT u.Email,u.FullName FROM user as u WHERE u.ID = '$data->UserID'");
                    $res = $resQ->fetch_assoc();
                    $subject = 'Sepantarai.com';
    $message = '
                <html>
                <head>
                  <title></title>
                </head>
                <body>
<p style="direction: rtl">سلام '.$res["FullName"].'</p><br>
                <p style="direction: rtl">
اکانت شما تا??د شده . شما م? توان?د در انجمن شروع به فعال?ت کن?د
<br>
'.$res["Email"].'
                </p>
                </body>
                </html>
';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .=  'From: sepantarai@sepantarai.com' . "\r\n" .
        'Reply-To: '.$res["Email"]."\r\n" .
        'X-Mailer: Sepantarai.com';
        mail($res["Email"], $subject, $message, $headers);
                }
                	echoSuccess();
            }
			else
				echoError("Cannot update record.");
		}else{
			echoError("Sended value:$data->State is not valid!");
		}
	}

	echoError("User state is not set!");
});

$app->post('/deleteAnswer', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();
                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$data->AdminPermissionLevel')
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

    $res = $app->db->deleteFromTable('answer_attachment',"AnswerID='$data->AnswerID'");
    $res = $app->db->deleteFromTable('answer_rate',"AnswerID='$data->AnswerID'");
	$res = $app->db->deleteFromTable('forum_answer',"ID='$data->AnswerID'");
	if($res)
    {
        $app->db->insertToTable('message','SenderUserID,UserID,MessageDate,MessageTitle,Message,MessageType',
                   "'$sess->UserID','$data->UserID',NOW(),'".'حذف جواب'."','".'جواب شما حذف شد'."','0'");
        echoSuccess();
    }
	else
		echoError("Cannot update record.");
});

$app->post('/deleteFile', function() use ($app)  {


	$data = json_decode($app->request->getBody());

    if(!isset($data->ID) || !isset($data->FileID) || !isset($data->AbsolutePath))
        echoError('bad request');
	$sess = new Session();
    $resQ = $app->db->makeQuery("select a.ID from user as u
    INNER JOIN admin as a on a.UserID = u.ID
    INNER JOIN admin_permission ap on ap.ID = a.PermissionID
    WHERE a.UserID = '$sess->UserID'
    LIMIT 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->deleteFromTable('library',"ID='$data->ID'");
	if($res)
    {
        $res = $app->db->deleteFromTable('file_storage',"ID='$data->FileID'");
        unlink('../../'.$data->AbsolutePath);
        echoSuccess();
    }
	else
		echoError("Cannot update record.");
});

$app->post('/changeAnswerAccepted', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	if(isset($data->State)){
		if($data->State=='1' || $data->State=='-1'){
			$sess = new Session();

                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$data->AdminPermissionLevel')
LIMIT 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

			$res = $app->db->updateRecord('forum_answer',"AdminAccepted='$data->State'","ID='$data->AnswerID'");
			if($res){
                if($data->State == 1){
                    $app->db->updateRecord('user',"score=(score+2)" , "ID = '$data->UserID'");
                    $app->db->insertToTable('message','SenderUserID,UserID,MessageDate,MessageTitle,Message,MessageType',
                    "'$sess->UserID','$data->UserID',NOW(),'".'تایید پیام'."','".'پیام شما تایید شد'."','0'");
                    if($data->UserID != $sess->UserID)
                        $app->db->insertToTable('event','EventUserID,EventTypeID , EventDate , EventCauseID , EvenLinkID',"$data->UserID,10,now(),$sess->UserID,$data->QuestionID");
                    if($data->AuthorID != $data->UserID)
                        $app->db->insertToTable('event','EventUserID,EventTypeID , EventDate , EventCauseID , EvenLinkID',"$data->AuthorID,8,now(),$data->UserID,$data->QuestionID");
                        $pageRes = $app->db->makeQuery("SELECT qf.UserID FROM question_follow as qf WHERE qf.QuestionID = '$data->QuestionID'");
                        $res=[];
                        while($r = $pageRes->fetch_assoc())
                            $res[] = $r;
                        foreach ($res as $value)
                        {
                        	$app->db->insertToTable('event','EventUserID,EventTypeID , EventDate , EventCauseID , EvenLinkID', $value["UserID"].",9,now(),$data->UserID,$data->QuestionID");
                        }
                }
                else if($data->State == -1 && isset($data->Message))
                {
                    $app->db->insertToTable('message','SenderUserID,UserID,MessageDate,MessageTitle,Message,MessageType',
                        "'$sess->UserID','$data->UserID',NOW(),'".$data->Message->MessageTitle."','".$data->Message->Message."','0'");
                }
        echoSuccess();
            }
			else
				echoError("Cannot update record.");
		}else{
			echoError("Sended value:$data->State is not valid!");
		}
	}

	echoError("User state is not set!");
});


$app->post('/getAllFiles', function() use ($app)  {

	$data = json_decode($app->request->getBody());
	$pr = new Pagination($data);
	//$sess = new Session();

    //$where = "WHERE fms.SubjectName = '$data->SubjectName'";
	
    //if(isset($data->answerType)){
    //    $where .=" AND (fa.AdminAccepted ='$data->answerType')";
    //}
    //if(isset($data->OrganizationID)){
    //    $where .=" AND (u.OrganizationID ='$data->OrganizationID')";
    //}
    //if(isset($data->searchValue) && strlen($data->searchValue) > 0){
    //    $s = mb_convert_encoding($data->searchValue, "UTF-8", "auto");
    //    $where .= " AND ( fa.AnswerText LIKE '%".$s."%' OR u.FullName LIKE '%".$s."%' OR u.Email LIKE '%".$s."%')";
    //    $hasWhere = TRUE;
    //}

	$pageRes = $pr->getPage($app->db,"SELECT f.ID as ID ,f.ID as FileID , fs.FullPath as UserPic, f.Filename , u.FullName
, f.FullPath , f.UploadDate ,f.FileSize ,f.Description,ft.TypeName , ft.GeneralType, l.* , f.AbsolutePath
FROM `library` as l inner JOIN file_storage as f on f.ID = l.FileID and f.FileSize > 0
INNER JOIN user as u on u.ID = f.UserID
left JOIN file_type as ft on ft.ID = f.FileTypeID
LEFT JOIN file_storage as fs on fs.ID = u.AvatarID
ORDER BY l.ID desc");

	echoResponse(200, $pageRes);
});

$app->post('/getAllAnswers', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());
	$pr = new Pagination($data);
	$sess = new Session();

	$where = "WHERE fms.SubjectName = '$data->SubjectName'";
	$hasWhere = FALSE;
    if(isset($data->answerType)){
        $where .=" AND (fa.AdminAccepted ='$data->answerType')";
	}
    if(isset($data->OrganizationID)){
        $where .=" AND (u.OrganizationID ='$data->OrganizationID')";
	}
	if(isset($data->searchValue) && strlen($data->searchValue) > 0){
		$s = mb_convert_encoding($data->searchValue, "UTF-8", "auto");
		$where .= " AND ( fa.AnswerText LIKE '%".$s."%' OR u.FullName LIKE '%".$s."%' OR u.Email LIKE '%".$s."%')";
		$hasWhere = TRUE;
	}

	$pageRes = $pr->getPage($app->db,"SELECT fa.*,fq.QuestionText ,fq.AuthorID as QuestionAuthorID , fq.ID as QuestionID ,u.FullName ,u.Email ,fis.FullPath ,u.ID as UserID FROM forum_answer as fa 
INNER JOIN forum_question as fq on fq.ID = fa.QuestionID
INNER JOIN forum_subject as fs on fs.ID = fq.SubjectID
INNER JOIN forum_main_subject as fms on fms.ID = fs.ParentSubjectID
INNER join user as u on u.ID = fa.AuthorID
LEFT JOIN file_storage as fis on fis.ID = u.AvatarID ".$where." ORDER BY fa.ID desc");

	echoResponse(200, $pageRes);
});

$app->post('/getAllPosts', function() use ($app)  {

	$data = json_decode($app->request->getBody());
	$pr = new Pagination($data);

	$pageRes = $pr->getPage($app->db,"SELECT fq.* ,fms.Title as MainSubjectName ,fs.Title as SubjectName ,u.FullName ,u.Email ,fis.FullPath ,u.ID as UserID,pt.PostType
FROM admin_post as fq
INNER JOIN forum_subject as fs on fs.ID = fq.SubjectID
INNER JOIN forum_main_subject as fms on fms.ID = fs.ParentSubjectID
INNER join user as u on u.ID = fq.AuthorID
INNER JOIN post_type as pt on pt.ID = fq.PostTypeID
LEFT JOIN file_storage as fis on fis.ID = u.AvatarID
ORDER BY fq.ID desc");

echoResponse(200, $pageRes);
});

$app->post('/getAllQuestions', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());
	$pr = new Pagination($data);
	$sess = new Session();

	$where = "WHERE fms.SubjectName = '$data->SubjectName'";
	$hasWhere = FALSE;
    if(isset($data->questionType)){
        $where .=" AND (fq.AdminAccepted ='$data->questionType')";
	}

    if(isset($data->OrganizationID)){
        $where .=" AND (u.OrganizationID ='$data->OrganizationID')";
	}
	if(isset($data->searchValue) && strlen($data->searchValue) > 0){
		$s = mb_convert_encoding($data->searchValue, "UTF-8", "auto");
		$where .= " AND (fq.Title LIKE '%".$s."%' OR fq.QuestionText LIKE '%".$s."%' OR u.FullName LIKE '%".$s."%' OR u.Email LIKE '%".$s."%')";
		$hasWhere = TRUE;
	}

	$pageRes = $pr->getPage($app->db,"SELECT fq.* ,fs.Title as SubjectName ,u.FullName , lq.TargetQuestionID ,u.Email ,fis.FullPath ,u.ID as UserID
FROM forum_question as fq
INNER JOIN forum_subject as fs on fs.ID = fq.SubjectID
INNER JOIN forum_main_subject as fms on fms.ID = fs.ParentSubjectID
INNER join user as u on u.ID = fq.AuthorID
LEFT JOIN file_storage as fis on fis.ID = u.AvatarID
LEFT JOIN link_question as lq on lq.LinkedQuestionID = fq.ID ".$where." GROUP BY fq.ID ORDER BY fq.ID desc");

	echoResponse(200, $pageRes);
});

$app->post('/deletePost', function() use ($app)  {

	$data = json_decode($app->request->getBody());

	$sess = new Session();
    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID'
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');
    $res = $app->db->deleteFromTable('admin_post_attachment',"AdminPostID='$data->PostID'");
    $res = $app->db->deleteFromTable('admin_post',"ID='$data->PostID'");
	if($res){
		echoSuccess();
    }
	else
		echoError("Cannot update record.");
});

$app->post('/deleteQuestion', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();
    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$data->AdminPermissionLevel')
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

    $pageRes = $app->db->makeQuery("SELECT pf.ID FROM forum_answer as pf WHERE pf.QuestionID = '$data->QuestionID'");
        $Answers=[];
    while($r = $pageRes->fetch_assoc())
        $Answers[] = $r;
    $res = $app->db->deleteFromTable('link_question',"TargetQuestionID='$data->QuestionID' or LinkedQuestionID = '$data->QuestionID'");
    $res = $app->db->deleteFromTable('question_attachment',"QuestionID='$data->QuestionID'");
    $res = $app->db->deleteFromTable('question_follow',"QuestionID='$data->QuestionID'");
    $res = $app->db->deleteFromTable('question_rate',"QuestionID='$data->QuestionID'");
    $res = $app->db->deleteFromTable('question_view',"QuestionID='$data->QuestionID'");
    $res = $app->db->deleteFromTable('forum_question',"ID='$data->QuestionID'");
    foreach ($Answers as $value) {
        $res = $app->db->deleteFromTable('answer_attachment',"AnswerID='".$value["ID"]."'");
        $res = $app->db->deleteFromTable('answer_rate',"AnswerID='".$value["ID"]."'");
        $res = $app->db->deleteFromTable('forum_answer',"ID='".$value["ID"]."'");
    }
	if($res){
        $app->db->insertToTable('message','SenderUserID,UserID,MessageDate,MessageTitle,Message,MessageType',
                "'$sess->UserID','$data->UserID',NOW(),'".'حذف سوال'."','".'سوال شما حذف شد'."','0'");
		echoSuccess();
    }
	else
		echoError("Cannot update record.");
});
$app->post('/editQuestion', function() use ($app)  {


	$data = json_decode($app->request->getBody());
    if(!isset($data->QuestionText) || !isset($data->Title))
        echoError("Bad Request");

	$sess = new Session();
                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$sess->AdminPermissionLevel')
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->updateRecord('forum_question',"QuestionText = '$data->QuestionText' , Title = '$data->Title' ","ID='$data->QuestionID'");
	if($res){
		echoSuccess();
    }
	else
		echoError("Cannot update record.");
});

$app->post('/editAnswer', function() use ($app)  {


	$data = json_decode($app->request->getBody());
    if(!isset($data->AnswerText) || !isset($data->AnswerID))
        echoError("Bad Request");

	$sess = new Session();
                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$sess->AdminPermissionLevel')
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->updateRecord('forum_answer',"AnswerText = '$data->AnswerText'","ID='$data->AnswerID'");
	if($res){
		echoSuccess();
    }
	else
		echoError("Cannot update record.");
});

$app->post('/linkQuestion', function() use ($app)  {


	$data = json_decode($app->request->getBody());

	if(isset($data->LinkedQuestionID) && isset($data->TargetQuestionID) && isset($data->UserID)){
        $sess = new Session();
                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$sess->AdminPermissionLevel')
LIMIT 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

			$res = $app->db->updateRecord('forum_question',"AdminAccepted='-2'","ID='$data->LinkedQuestionID'");
            if($data->LinkedQuestionID == $data->TargetQuestionID)
                echoError('you cant link a queston to it self');
            $app->db->insertToTable('link_question',"LinkedQuestionID , TargetQuestionID , LinkedDate","'$data->LinkedQuestionID' ,'$data->TargetQuestionID' , NOW()");
			if($res)
            {
                    $app->db->updateRecord('user',"score=(score+1)" , "ID = '$data->UserID'");
                    $app->db->insertToTable('message','SenderUserID,UserID,MessageDate,MessageTitle,Message,MessageType,LinkQuestionID',
                "'$sess->UserID','$data->UserID',NOW(),'".'لینک سوال'."','".'سوال شما به سوال دیگری لینک داده شد'."','0','$data->TargetQuestionID'");
                echoSuccess();
            }
			else
				echoError("Cannot update record.");
	}

	echoError("User state is not set!");
});
$app->post('/changeQuestionAccepted', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	if(isset($data->State)){
		if($data->State=='1' || $data->State=='-1'){
			$sess = new Session();

                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$data->AdminPermissionLevel')
LIMIT 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

			$res = $app->db->updateRecord('forum_question',"AdminAccepted='$data->State'","ID='$data->QuestionID'");
			if($res)
            {
                if($data->State == 1)
                {
                    $app->db->updateRecord('user',"score=(score+1)" , "ID = '$data->UserID'");
                    $app->db->insertToTable('message','SenderUserID,UserID,MessageDate,MessageTitle,Message,MessageType',
                "'$sess->UserID','$data->UserID',NOW(),'".'تایید سوال'."','".'سوال شما تایید شد'."','0'");
                    if($data->UserID != $sess->UserID)
                        $app->db->insertToTable('event','EventUserID,EventTypeID , EventDate , EventCauseID , EvenLinkID',"$data->UserID,3,now(),$sess->UserID,$data->QuestionID");
                        $pageRes = $app->db->makeQuery("SELECT pf.UserID FROM person_follow as pf WHERE pf.TargetUserID = '$data->UserID'");
                        $res=[];
                        while($r = $pageRes->fetch_assoc())
                            $res[] = $r;
                        foreach ($res as $value)
                        {
                        	$app->db->insertToTable('event','EventUserID,EventTypeID , EventDate , EventCauseID , EvenLinkID', $value["UserID"].",4,now(),$data->UserID,$data->QuestionID");
                        }
                        $pageRes = $app->db->makeQuery("SELECT msf.UserID FROM forum_subject as fs INNER JOIN
                        forum_main_subject as fms on fms.ID = fs.ParentSubjectID
                        INNER JOIN main_subject_follow as msf on msf.MainSubjectID = fms.ID where fs.ID = '$data->QuestionSubjectID'
                        UNION
                        SELECT sf.UserID FROM forum_subject as fs
                        INNER JOIN subject_follow as sf on sf.SubjectID = fs.ID
                        where fs.ID = '$data->QuestionSubjectID'");
                        $res=[];
                        while($r = $pageRes->fetch_assoc())
                            $res[] = $r;
                        foreach ($res as $value)
                        {
                            $app->db->insertToTable('event','EventUserID,EventTypeID , EventDate , EventCauseID , EvenLinkID', $value["UserID"].",11,now(),$data->UserID,$data->QuestionID");
                        }
                }
                else if($data->State == -1 && isset($data->Message))
                {
                    $app->db->insertToTable('message','SenderUserID,UserID,MessageDate,MessageTitle,Message,MessageType',
                        "'$sess->UserID','$data->UserID',NOW(),'".$data->Message->MessageTitle."','".$data->Message->Message."','0'");
                }
                echoSuccess();
            }
			else
				echoError("Cannot update record.");
		}else{
			echoError("Sended value:$data->State is not valid!");
		}
	}

	echoError("User state is not set!");
});



$app->post('/getAllForumTypes', function() use ($app)  {

    $pageRes = $app->db->makeQuery("SELECT * FROM `forum_main_subject`");
    $res=[];
    while($r = $pageRes->fetch_assoc())
        $res[] = $r;
    echoSuccess($res);
});

$app->post('/getAllMessages', function() use ($app)  {
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);
    $pageRes = $pr->getPage($app->db,"SELECT * FROM common_message");

    echoResponse(200,$pageRes);
});

$app->post('/getAllAdminTypes', function() use ($app)  {

    $pageRes = $app->db->makeQuery("SELECT * FROM `admin_permission`");
    $res=[];
    while($r = $pageRes->fetch_assoc())
        $res[] = $r;
    echoSuccess($res);
});

$app->post('/getAllPositions', function() use ($app)  {

    $resq = $app->db->makeQuery("SELECT * FROM `organ_position` WHERE 1");
    $res=[];
    while($r = $resq->fetch_assoc())
        $res[] = $r;
    echoResponse(200, $res);
});

$app->post('/updateAdmin', function() use ($app)  {

    
	$data = json_decode($app->request->getBody());
	$sess = new Session();

$resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base')
LIMIT 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

    $resQ = $app->db->makeQuery("SELECT COUNT(*) as val FROM `user` WHERE `ID` = '$data->UserID'");

    $sql =$resQ->fetch_assoc();
    if($sql["val"] == 0)
        echoError('there is no user with this UserID');

    $resQ = $app->db->makeQuery("SELECT COUNT(*) as val FROM `admin` WHERE `UserID` = '$data->UserID'");

    $sql =$resQ->fetch_assoc();
    if($sql["val"] == 0){
        $resQ = $app->db->insertToTable('admin',"`UserID`, `PermissionID`,`ForumID`","'$data->UserID','$data->PermissionID','$data->ForumID'");
    }else
    {
        if($data->UserID != $sess->UserID)
            $resQ = $app->db->updateRecord('admin',"`PermissionID`= '$data->PermissionID', ForumID = '$data->ForumID'","UserID = '$data->UserID'");
        else
            echoError('you cannot change your own permission level');
    }
    if($sql)
        echoSuccess();
    else
        echoError('error in updateing admin');
});

$app->post('/getAllAdmins', function() use ($app)  {

    
    $data = json_decode($app->request->getBody());
    $pr = new Pagination($data);

    $pageRes = $pr->getPage($app->db,"SELECT u.ID as UserID , u.FullName, u.SignupDate , u.Email , ap.ID as PID , ap.Permission , ap.PermissionLevel ,a.ID , a.ForumID FROM `admin` as a INNER JOIN user as u on u.ID = a.`UserID` INNER JOIN admin_permission as ap on ap.ID = a.`PermissionID`");

    echoResponse(200, $pageRes);
});
$app->post('/deleteMessage', function() use ($app)  {


	$data = json_decode($app->request->getBody());

	$sess = new Session();

    $resQ = $app->db->makeQuery("select Count(*) as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base'");

    $sql =$resQ->fetch_assoc();
    if($sql['val'] == 0)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->deleteFromTable('message',"ID='$data->MessageID'");
	if($res)
		echoSuccess();
	else
		echoError("Cannot update record.");
});

$app->post('/deleteAdmin', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();
	if($sess->UserID == $data->AdminID)
		echoError('You cannot delete your account.');

    $resQ = $app->db->makeQuery("select Count(*) as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' and ap.PermissionLevel = 'Base'");

    $sql =$resQ->fetch_assoc();
    if($sql['val'] == 0)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->deleteFromTable('admin',"ID='$data->AdminID'");
	if($res)
		echoSuccess();
	else
		echoError("Cannot update record.");
});

$app->post('/getUsersByName', function() use ($app)  {


	$data = json_decode($app->request->getBody());
    if(!isset($data->filter))
        echoError("bad request");

    $resQ = $app->db->makeQuery("SELECT u.FullName , u.Email , u.ID FROM user as u where u.FullName LIKE N'%$data->filter%' or u.Email LIKE N'%$data->filter%' limit 20");
    $res=[];
    while($r = $resQ->fetch_assoc())
        $res[] = $r;
	echoSuccess($res);
});
$app->post('/sendMessage', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());
    $sess = new Session();
        $resQ = $app->db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$sess->UserID' limit 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

    foreach ($data->Users as $value)
    {
    	$app->db->insertToTable('message','SenderUserID,UserID,MessageDate,MessageTitle,Message,MessageType',
            "'$sess->UserID','$value->ID',NOW(),'".$data->Message->MessageTitle."','".$data->Message->Message."','".$data->Message->MessageType."'");

        if($value->ID != $sess->UserID)
                        $app->db->insertToTable('event','EventUserID,EventTypeID , EventDate , EventCauseID',"$value->ID,5,now(),$sess->UserID");
        if($data->Message->MessageType == 1){
            $subject = 'Sepantarai.com';
            $message = '
                        <html>
                        <head>
                          <title></title>
                        </head>
                        <body>
                        <p style="direction: rtl">'.$data->Message->MessageTitle.'
                        </p>
                        <br>
                        <br>
                        <p style="direction: rtl">'.$data->Message->Message.'
                        </p>
                        </body>
                        </html>
        ';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .=  'From: sepantarai@sepantarai.com' . "\r\n" .
            'Reply-To: '.$value->Email."\r\n" .
            'X-Mailer: Sepantarai.com';
            mail($value->Email, $subject, $message, $headers);
        }
    }
	echoSuccess();
});

$app->post('/getAllUserMessages', function() use ($app)  {


	$data = json_decode($app->request->getBody());
	$pr = new Pagination($data);
	$sess = new Session();
    if(!isset($data->UserID) || isset($data->UserID) != $sess->UserID )
        echoError('invalid UserID');
    if($data->UserID != $sess->UserID )
        echoError('invalid UserID');
	$where = "WHERE au.ID = '$data->UserID'";
	$hasWhere = FALSE;
    if(isset($data->MessageType)){
        $where .=" AND (m.MessageType ='$data->MessageType')";
	}
	if(isset($data->searchValue) && strlen($data->searchValue) > 0){
		$s = mb_convert_encoding($data->searchValue, "UTF-8", "auto");
		$where .= " AND ( m.Message LIKE '%".$s."%' OR m.MessageTitle LIKE '%".$s."%' OR u.FullName LIKE '%".$s."%')";
		$hasWhere = TRUE;
	}

	$pageRes = $pr->getPage($app->db,"SELECT u.FullName ,fs.FullPath, u.Email , m.* FROM message as m
INNER JOIN user as u on u.ID = m.UserID
INNER join user as au on au.ID = m.SenderUserID
LEFT JOIN file_storage as fs on u.AvatarID = fs.ID ".$where." ORDER BY m.ID desc");

	echoResponse(200, $pageRes);
});

$app->post('/getCommonMessages', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());
	$where = "WHERE 1";
	if(isset($data->filter) && strlen($data->filter) > 0){
		$s = mb_convert_encoding($data->filter, "UTF-8", "auto");
		$where .= " AND MessageTitle = '$s'";
	}
	$pageRes = $app->db->makeQuery("SELECT * FROM `common_message` $where");

    $res=[];
    while($r = $pageRes->fetch_assoc())
        $res[] = $r;
	echoSuccess($res);
});

$app->post('/getSubjects', function() use ($app)  {

	
	$pageRes = $app->db->makeQuery("SELECT fs.* , fm.Title as MainTitle FROM forum_subject fs INNER JOIN
forum_main_subject as fm on fm.ID = fs.ParentSubjectID");
    $res=[];
    while($r = $pageRes->fetch_assoc())
        $res[] = $r;
	echoSuccess($res);
});

$app->post('/deleteMessage', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	$sess = new Session();
                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID'
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->deleteFromTable('message',"ID='$data->MessageID'");
	if($res){
		echoSuccess();
    }
	else
		echoError("Cannot update record.");
});

$app->post('/deleteMessages', function() use ($app)  {


	$data = json_decode($app->request->getBody());
    if(!isset($data->MessagesID))
        echoError('bad request');
	$sess = new Session();
                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID'
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

	$res = $app->db->deleteFromTable('message',"ID in $data->MessagesID");
	if($res){
		echoSuccess();
    }
	else
		echoError("Cannot update record.");
});

$app->post('/exchangeQuestion', function() use ($app)  {

	
	$data = json_decode($app->request->getBody());

	if( isset($data->QuestionID) && isset($data->SubjectID)){
			$sess = new Session();

                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$sess->AdminPermissionLevel')
LIMIT 1");

    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

			$res = $app->db->updateRecord('forum_question',"SubjectID='$data->SubjectID'","ID='$data->QuestionID'");
			if($res)
            {
                echoSuccess();
            }
			else
				echoError("Cannot update record.");
	}

	echoError("Bad request!");
});

$app->post('/getUserNotifications', function() use ($app)  {

    require_once '../db/event.php';

    $sess = new Session();

    $notify = [];
    $notify['Total']= getUserTotalNotifications($app->db,$sess->UserID);
    $notify['All'] = getUserLastNotifications($app->db, $sess->UserID , 25);
    echoSuccess($notify);
});

$app->post('/getUserMessages', function() use ($app)  {

    require_once '../db/message.php';
    $sess = new Session();

    $res = [];
    $res['All'] = getUserUnreadMessages($app->db,$sess->UserID,20);
    $res['Total'] = getUserUnreadMessagesCount($app->db,$sess->UserID);
    echoResponse(200 , $res);
});

$app->post('/markLastNotifications', function() use ($app)  {

    require_once '../db/event.php';

    $sess = new Session();

    $resQ= $app->db->makeQuery("update event set event.EventSeen='1'
                           where event.EventUserID='$sess->UserID' and event.EventSeen='0'
                           order by event.EventDate desc limit 25");

    $notify = [];
    $notify['Total']= getUserTotalNotifications($app->db,$sess->UserID);
    $notify['All'] = getUserLastNotifications($app->db, $sess->UserID , 25);
    echoSuccess($notify);
});

$app->post('/getAdminBadges', function() use ($app)  {

    	$sess = new Session();
                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and (ap.PermissionLevel = 'Base' or ap.PermissionLevel = '$sess->AdminPermissionLevel')
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');
    $resp = [];
    $resQ = $app->db->makeQuery("SELECT COUNT(CASE WHEN fms.SubjectName = 'DataSwitch' THEN 1
                  ELSE NULL
             END) AS QuestionDataSwitch
       ,COUNT(CASE WHEN fms.SubjectName = 'Radio' THEN 1
                   ELSE NULL
              END) AS QuestionRadio
                  ,COUNT(CASE WHEN fms.SubjectName = 'TransportManagement' THEN 1
                         ELSE NULL
                         END) AS QuestionTransportManagement
                  ,COUNT(CASE WHEN fms.SubjectName = 'Transition' THEN 1
                         ELSE NULL
                         END) AS QuestionTransition
                  ,COUNT(CASE WHEN fms.SubjectName = 'CommonTopics' THEN 1
                         ELSE NULL
                         END) AS QuestionCommonTopics
       ,COUNT(*) AS AllQuestions
    FROM forum_question as fq INNER JOIN
    forum_subject as fs on fs.ID = fq.SubjectID
    INNER JOIN forum_main_subject as fms on fms.ID = fs.ParentSubjectID
    WHERE fq.AdminAccepted = 0");
    $resp["Question"] = $resQ->fetch_assoc();
        $resQ = $app->db->makeQuery("SELECT COUNT(CASE WHEN fms.SubjectName = 'DataSwitch' THEN 1
                  ELSE NULL
             END) AS AnswerDataSwitch
       ,COUNT(CASE WHEN fms.SubjectName = 'Radio' THEN 1
                   ELSE NULL
              END) AS AnswerRadio
                  ,COUNT(CASE WHEN fms.SubjectName = 'TransportManagement' THEN 1
                         ELSE NULL
                         END) AS AnswerTransportManagement
                  ,COUNT(CASE WHEN fms.SubjectName = 'Transition' THEN 1
                         ELSE NULL
                         END) AS AnswerTransition
                  ,COUNT(CASE WHEN fms.SubjectName = 'CommonTopics' THEN 1
                         ELSE NULL
                         END) AS AnswerCommonTopics
       ,COUNT(*) AS AllAnswers
    FROM forum_answer as fa INNER JOIN
    forum_question as fq on fa.QuestionID = fq.ID INNER JOIN
    forum_subject as fs on fs.ID = fq.SubjectID
    INNER JOIN forum_main_subject as fms on fms.ID = fs.ParentSubjectID
    WHERE fa.AdminAccepted = 0");
    $resp["Answer"] = $resQ->fetch_assoc();
            $resQ = $app->db->makeQuery("SELECT COUNT(*) as UserCount FROM user as u WHERE u.UserAccepted = 0");
    $resp["User"] = $resQ->fetch_assoc();
    $resQ = $app->db->makeQuery("SELECT COUNT(*) as MessageCount FROM message as u WHERE u.UserID = '$sess->UserID' AND u.MessageViewed = 0");
    $resp["Message"] = $resQ->fetch_assoc();
    echoSuccess($resp);
});

$app->post('/getAdminPostMetaEdit', function() use ($app)  {
    //userRequire();
    require_once '../db/post_type.php';
    require_once '../db/forum_subject.php';
    require_once '../db/admin_post_attachment.php';
    $data = json_decode($app->request->getBody());


    $res = [];

    if(isset($data)) {
        $resQ = $app->db->makeQuery("select * from admin_post where admin_post.ID='$data->AdminPostID'");
        $res['AdminPost'] = $resQ->fetch_assoc();

        $res['AdminPost']['PostType'] = getAdminPostType($app->db , $data->AdminPostID);
        $res['AdminPost']['Subject'] = getAdminPostSubject($app->db , $data->AdminPostID);
        $res['AdminPost']['MainSubject'] = getSubjectParent($app->db , $res['AdminPost']['Subject']['ID']);
        $res['AdminPost']['Attachments'] = getAdminPostAttachments($app->db , $data->AdminPostID);
    }
 
    $res['AllPostTypes'] = getAllPostTypes($app->db);
    $res['AllSubjects'] = getAllMainSubjectsWithChilds($app->db);

    echoResponse(200, $res);
});

$app->post('/saveAdminPost', function() use ($app)  {

    $data = json_decode($_POST['formData']);

    $sess = $app->session;
    $qID = $app->db->insertToTable('admin_post','PostText,Title,SubjectID,AuthorID,CreationDate,PostTypeID',
        "'$data->PostText','$data->Title','".$data->Subject->ID."','".$sess->UserID."',NOW(),'".
        $data->PostType->ID."'",true);

    if (isset($_FILES['file'])) {
        $file_ary = reArrayFiles($_FILES['file']);

        if (!file_exists('../../content/admin_upload/')) {
            mkdir('../../content/admin_upload/', 0777, true);
        }

        foreach ($file_ary as $file) {
            $filename = $file['name'];
            $rand = generateRandomString(18);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            $destination ='content/admin_upload/'.$rand.'.'.$ext;
            $uploadSuccess = move_uploaded_file( $file['tmp_name'] , '../../'.$destination );
            if($uploadSuccess){
                $fileTypeQ = $app->db->makeQuery("select file_type.ID from file_type where file_type.TypeName='$ext'");

                $fileTypeID = -1;
                if(mysqli_num_rows($fileTypeQ) > 0)
                    $fileTypeID = $fileTypeQ->fetch_assoc()['ID'];

                $fileSize = $file['size'] / 1024;
                $fid = $app->db->insertToTable('file_storage','AbsolutePath,FullPath,Filename,IsAvatar,UserID,FileTypeID,
                FileSize,UploadDate',
                    "'$destination','../$destination','$filename','0','$sess->UserID','$fileTypeID','$fileSize',NOW()",
                    true);

                $app->db->insertToTable('admin_post_attachment','AdminPostID,FileID',
                    "'$qID','$fid'");
            }
        }
    }

    $res = [];
    $res['Status'] ='success';
    $res['AdminPostID'] = $qID;

    echoResponse(200, $res);
});

$app->post('/editAdminPost', function() use ($app)  {

    $data = json_decode($app->request->getBody());
    $sess = $app->session;

    $app->db->updateRecord('admin_post',"SubjectID='".$data->Subject->ID."',PostText='$data->PostText',
        Title='$data->Title',PostTypeID='".$data->PostType->ID."'", "ID='$data->ID' and AuthorID='$sess->UserID'");

    $res = [];
    $res['Status'] ='success';

    echoResponse(200, $res);
});

$app->post('/calculateUsersScore', function() use ($app)  {

	$data = json_decode($app->request->getBody());

	$sess = new Session();
                    $resQ = $app->db->makeQuery("select a.ID from user as u
INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
WHERE a.UserID = '$sess->UserID' and a.UserID = 32
LIMIT 1");
    $sql =$resQ->fetch_assoc();
    if(!$sql)
        echoError('You don\'t have permision to do this action');

    $resQ = $app->db->makeQuery("SELECT  u.ID,u.FullName , u.Email , u.ID FROM user as u");
    $res=[];
    while($r = $resQ->fetch_assoc())
        $res[] = $r;


    foreach ($res as $user)
    {
        $resQ = $app->db->makeQuery("SELECT ((SELECT count(*) as val1 FROM link_question as lq INNER JOIN forum_question q on lq.LinkedQuestionID = q.ID WHERE q.AuthorID = ".$user["ID"]." and q.AdminAccepted = 1)+
((SELECT count(*) as val2 FROM forum_answer as f WHERE f.AuthorID = ".$user["ID"]." and f.AdminAccepted = 1) * 2)+
(SELECT count(*) as val3 FROM forum_question as q1 WHERE q1.AuthorID = ".$user["ID"]." and q1.AdminAccepted = 1)) as val");
            $resQ = $resQ->fetch_assoc();
            $res = $app->db->updateRecord('user',"score = ".$resQ['val'],"ID=".$user["ID"]);
    }
    echoSuccess("done");
});

$app->post('/getStone', function() use ($app)  {
    $data = json_decode($app->request->getBody());

    $user = $app->db->getOneRecord("SELECT (SELECT ps.StoneID FROM stone as ps WHERE ps.CopeID = s.CopeID and ps.CopeNumber = (s.CopeNumber -1 ) and ps.StoneTypeID = s.StoneTypeID LIMIT 1) as PreviousStoneID ,
(SELECT ns.StoneID FROM stone as ns WHERE ns.CopeID = s.CopeID and ns.CopeNumber = (s.CopeNumber +1 ) and ns.StoneTypeID = s.StoneTypeID LIMIT 1) as NextStoneID ,
s.`StoneID`, st.`Name`, s.Height,s.Width , s.Image , s.CreationDate,
s.ImageSize, s.Description, s.Area, s.MachineNumber, s.CopeID, s.CopeNumber,
s.Sold, s.`BockMachDirection`
FROM Stone s inner JOIN stone_type st on st.StoneTypeID = s.StoneTypeID");
    echoSuccess($user);
});
?>