<?php

require_once '../dbHandler.php';
require_once '../classes.php';
require_once '../sessionHandler.php';
require_once '../passwordHash.php';
require_once '../functions.php';

$session = new Session();
$db = new DbHandler($session ,true ,false);

$filename = $_FILES['file']['name'];
$meta = $_POST;

if (!file_exists('../../content/library/')) {
    mkdir('../../content/library/', 0777, true);
}

$rand = generateRandomString(18);
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

move_uploaded_file( $_FILES['file']['tmp_name'] , '../../content/library/'.$rand.'.'.$ext );

$fileTypeQ = $db->makeQuery("select file_type.ID from file_type where file_type.TypeName='$ext'");

$fileTypeID = -1;
if(mysqli_num_rows($fileTypeQ) > 0)
    $fileTypeID = $fileTypeQ->fetch_assoc()['ID'];

$destination ='content/library/'.$rand.'.'.$ext;
$fileSize = $_FILES['file']['size'];

$fid = $db->insertToTable('file_storage','AbsolutePath,FullPath,Filename,IsAvatar,UserID,FileTypeID,
                FileSize,UploadDate'.((isset($meta['Description']))?',Description':''),
    "'$destination','../$destination','$filename','0','$session->UserID','$fileTypeID','$fileSize',NOW()".
    ((isset($meta['Description']))?",'".$meta['Description']."'":''),
    true);
$cols = 'FileID';
$values = "'$fid'";
$resQ = $db->makeQuery("select ap.ID as val from user as u INNER JOIN admin as a on a.UserID = u.ID
INNER JOIN admin_permission ap on ap.ID = a.PermissionID
where u.ID = '$session->UserID' and ap.PermissionLevel = 'Base' limit 1");
$sql =$resQ->fetch_assoc();

if($sql){
    $cols .= ',AdminAccepted';
    $values .= ",1";
}
if(isset($meta['MainSubjectID'])){
    $cols .= ',MainSubjectID';
    $values .= ",'".$meta['MainSubjectID']."'";
}

if(isset($meta['SubjectID'])){
    $cols .= ',SubjectID';
    $values .= ",'".$meta['SubjectID']."'";
}

if(isset($meta['Title'])){
    $cols .= ',Title';
    $values .= ",'".$meta['Title']."'";
}

$libraryID = $db->insertToTable('library' , $cols, $values,true);

if(isset($meta['Tags'])){
    $items = "";
    foreach ($meta['Tags'] as $tag) {
        $items .="($libraryID , $tag->ID),";
    }
    $items = substr($items , 0 , strlen($items)-1);
    $resQ = $db->makeQuery("INSERT INTO `tag_library`(`LibraryID`, `TagID`) VALUES ".$items);
    $sql =$resQ->fetch_assoc();
}
?>
