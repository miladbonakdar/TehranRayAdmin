<?php

require_once '../dbHandler.php';
require_once '../classes.php';
require_once '../sessionHandler.php';
require_once '../passwordHash.php';
require_once '../functions.php';

$session = new Session();
$db = new DbHandler($session ,true ,true);

$filename = $_FILES['file']['name'];
$meta = $_POST;

if (!file_exists('../../content/user_upload/')) {
    mkdir('../../content/user_upload/', 0777, true);
}

$rand = generateRandomString(18);
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

move_uploaded_file( $_FILES['file']['tmp_name'] , '../../content/user_upload/'.$rand.'.'.$ext );

$fileTypeQ = $db->makeQuery("select file_type.ID from file_type where file_type.TypeName='$ext'");

$fileTypeID = -1;
if(mysqli_num_rows($fileTypeQ) > 0)
    $fileTypeID = $fileTypeQ->fetch_assoc()['ID'];

$destination ='content/user_upload/'.$rand.'.'.$ext;
$fileSize = $_FILES['file']['size'] / 1024;

$fid = $db->insertToTable('file_storage','AbsolutePath,FullPath,Filename,IsAvatar,UserID,FileTypeID,
                FileSize,UploadDate'.((isset($meta['Description']))?',Description':''),
    "'$destination','../$destination','$filename','0','$session->UserID','$fileTypeID','$fileSize',NOW()".
    ((isset($meta['Description']))?",'".$meta['Description']."'":''),
    true);


if($meta['Type'] == 'new_question'){

    $db->insertToTable('question_attachment','IsInline,FileID',
        "'1','$fid'");
}else if($meta['Type'] == 'question'){

    $db->insertToTable('answer_attachment','IsInline,FileID',
        "'1','$fid'");
}else if($meta['Type'] == 'new_admin_post'){

    $db->insertToTable('admin_post_attachment','IsInline,FileID',
        "'1','$fid'");
}

echo '../'.$destination;
?>
