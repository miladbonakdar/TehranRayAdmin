<!DOCTYPE html>
<html lang="fa" ng-app="myApp">
<head>
    <?php
        require_once 'session_generator.php';

        generateSessionAsJavascriptVariable();
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- Bootstrap -->
<!--    <link href="css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="css/page-style.min.css">
    <link href="css/select.min.css" rel="stylesheet">
    <link href="css/full-page-styles.css" rel="stylesheet" />
    
<!--    <link href="css/hover-min.css" rel="stylesheet">-->
    <link href="css/imagehover.css" rel="stylesheet">
    <link href="css/treasure-overlay-spinner.css" rel="stylesheet">

<!--    <link href="css/ADM-dateTimePicker.min.css " rel="stylesheet">-->
<!--    <link href='css/textAngular.css' rel='stylesheet'>-->

<!--    <link href="css/font-awesome.min.css" rel="stylesheet">-->
    <link href="css/toaster.css" rel="stylesheet">
    <link href="css/angular-tooltips.css" rel="stylesheet" type="text/css" />
    <link href="cms/css/select/select.min.css" rel="stylesheet">
    
<!--    <link href="css/site-styles.css" rel="stylesheet" />-->

    <link href="css/jquery-fullPage.css" rel="stylesheet" />

    <title>سنگ تهران ری</title>
    <link rel="icon" href="images/icon.png">
</head>

<body style="background-color:#fcb814">
    <treasure-overlay-spinner active='spinner.active' >
    </treasure-overlay-spinner>

    <div ui-view  class="container" ></div>
    <toaster-container toaster-options="{'time-out': 10000, 'position-class': 'toast-bottom-right', 'close-button':true, 'animation-class': 'toast-bottom-right'}"></toaster-container>
</body>
<!-- jqurey libs -->
<script src="js/jquery.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script src="js/jquery.fullPage.js"></script>

<script src="js/angular.js"></script>
<script src="js/angular-route.min.js"></script>
<script src="js/angular-cookies.min.js"></script>
<script src="js/angular-animate.min.js" ></script>
<script src="js/angular-ui-router.js"></script>


<script src="js/toaster.min.js"></script>
<script src="js/ui-bootstrap-tpls-1.2.5.min.js"></script>

<script src="js/lazyLoad/ocLazyLoad.min.js" type="text/javascript" ></script>
<script src="js/ng-file-upload-shim.min.js"></script>
<script src="js/ng-file-upload.min.js"></script>
<script src="js/angular-tooltips.min.js"></script>
<script src="js/select.min.js"></script>
<script src='js/bootstrap-plus.min.js'></script>

<script src="js/editor/ckeditor/ckeditor.js"></script>
<script src='js/editor/ng-ckeditor.js'></script>

<script src="js/ng-sortable.js" type="text/javascript" ></script>

<script src="js/treasure-overlay-spinner.min.js" type="text/javascript"></script>

<!--<script src="js/jquery.js" type="text/javascript"></script>-->
<script src="js/angular-fullpage.js"></script>
<script src="cms/js/select/select.min.js"></script>

<script src="js/hotkeys.min.js"></script>

<script src="js/angular-aria.min.js"></script>
<script src="js/angular-messages.min.js"></script>

<script src="js/material/angular-material.min.js"></script>
<script src="js/material/svg-assets-cache.js"></script>
<link href="css/material/angular-material.min.css" rel="stylesheet">
<!--
<link rel="stylesheet" href="css/eeh-navigation.css"/>
<script src="js/eeh-nav/eeh-navigation.js"></script>
<script src="js/eeh-nav/eeh-navigation.tpl.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-translate/2.10.0/angular-translate.min.js"></script>-->

<script src="js/angular-pageslide-directive.js"></script>

<script src="app/ng-vendor.min.js"></script>
<script src="app/templates.min.js"></script>
<script src="js/angular-clipboard.js"></script>
<script src="app/directives/auto-pagination.js"></script>

<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/moment-jalaali.js"></script>
<script type="text/javascript" src="js/angular-confirm.min.js"></script>
<script type="text/javascript" src="js/ADM-dateTimePicker.min.js"></script>

<script src="js/bootstrap-toolkit.js"></script>
</html>

