
<?php
require 'api/dbHandler.php';
include_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check for database connection error
if (mysqli_connect_errno()) {
    echo "مشکل در برقراری ارتباط ، لطفا دوباره تلاش کنید.";
}

$conn->query('SET CHARACTER SET utf8') or die($this->conn->error.__LINE__);

$link = $_GET['link'];
$u = $conn->query("select user.* ,user_mail.ID as MailID from user_mail left join user on user.ID=user_mail.UserID where user_mail
.LinkID='$link'")->fetch_assoc();

if(!$u){
    header("Location: http://sepantarai.com");
}

$uRes = $conn->query("update user set user.MailAccepted=1 where user.ID=".$u['ID']);

if($uRes){
    $dRes = $conn->query("delete from user_mail where user_mail.ID=".$u['MailID']);
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>سپنتا</title>
    <link rel="icon" href="images/title.png">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/site-styles.css" rel="stylesheet" />

    <link href="css/jquery-fullPage.css" rel="stylesheet" />
</head>

<body style="background-color:transparent" class="vazir-font">

<div class="container">

    <div class="row">
        <div class="col-xs-12 vazir-font text-center" style="margin-top:20px;margin-bottom:20px;">
            <img src="images/Logo.png" width="80"/>
        </div>
        <div class="text-center col-xs-12  vazir-font">
            <label style="font-size: 14px;color: #fff">
                اداره کل ارتباطات و علائم الکتریکی
            </label>
        </div>
        <div class="col-xs-12 text-center vazir-font">
            <label style="font-size: 25px;color: #fff">
                سامانه پویای نگهداشت تجهیزات ارتباطی
            </label>
            <label class="col-xs-12" style="font-size: 15px;color: #fff">
                (سپنتا)
            </label>
            <label class="col-xs-12" style="font-size: 15px;color: #fff">
                ما برای وصل کردن آمدیم
            </label>
            <div class="hr col-xs-12"></div>
        </div>
        <div class="col-xs-12 text-center vazir-font" style="color: #fff;margin: 20px">

            <h4 class="text-center" dir="rtl">
                <span><?php echo $u['FullName']; ?></span>
                عزیز ، ایمیل شما تایید شد ، لطفا منتظر تایید ادمین فروم باشید.با تشکر از شما.
            </h4>
        </div>
    </div>
</div>

</body>
</html>

