<?php
/**
 * Created by PhpStorm.
 * User: -MR-
 * Date: 17/05/2016
 * Time: 11:16 PM
 */
function generateRequiredCMSJavaFiles(){
?>
    <script src="../cms/js/page-script.min.js"></script>
    <script src="../js/angular-cookies.min.js"></script>
    <!--    <script src="../cms/js/jQuery/jQuery-2.1.4.min.js"></script>-->
    <!--    <script src="../cms/js/jQueryUI/jquery-ui.min.js"></script>-->
    <!--    <script src="../cms/js/bootstrap.min.js"></script>-->
    <!--    <script src="../cms/js/fastclick/fastclick.js"></script>-->
    <!--    <script src="../cms/js/slimScroll/jquery.slimscroll.min.js"></script>-->
    <!--    <script src="../cms/js/app.js"></script>-->
<!--        <script src="../cms/js/demo.js"></script>-->
    <!--    <script src="../js/persian.min.js"></script>-->

    <!--    <script src="../js/angular.js"></script>-->
    <!--    <script src="../js/angular-route.min.js"></script>-->
    <!--    <script src="../js/angular-animate.min.js" ></script>-->
    <!--    <script src="../js/angular-sanitize.min.js" ></script>-->
    <!--    <script src="../js/angular-ui-router.js"></script>-->
    <!--    <script src="../js/ng-img-crop.js"></script>-->
    <!--    <script src="../js/ng-fx/ng-fx.min.js"></script>-->
    <!--    <script src="../js/ADM-dateTimePicker.min.js"></script>-->
    <!--    <script src="../js/ng-file-upload-shim.min.js"></script>-->
    <!--    <script src="../js/anim-in-out.js"></script>-->
    <!--    <script src="../js/ng-file-upload.min.js"></script>-->
    <!--    <script src="../js/angularpersian.min.js"></script>-->
    <!--    <script src="../cms/js/treasure-overlay-spinner.js" type="text/javascript"></script>-->
    <!--    <script src="../js/lazyLoad/ocLazyLoad.min.js" type="text/javascript"></script>-->
    <!--    <script src="../js/ui-bootstrap-tpls-1.2.5.min.js" type="text/javascript"></script>-->
    <!--    <script src="../cms/js/select/select.min.js" type="text/javascript"></script>-->
    <!--    <script src='../js/text-angular/textAngular-rangy.min.js'></script>-->
    <!--    <script src='../js/text-angular/textAngular-sanitize.min.js'></script>-->
    <!--    <script src='../js/text-angular/textAngular.min.js'></script>-->
    <!--    <script src="../js/toaster.js" type="text/javascript"></script>-->
    <!--    <script type="text/javascript" src="../js/moment.js"></script>-->
    <!--    <script type="text/javascript" src="../js/moment-jalaali.js"></script>-->
    <!--    <script type="text/javascript" src="../js/angular-confirm.min.js"></script>-->
    <!--    <script type="text/javascript" src="../app/directives/serial-chart-directive.js"></script>-->
    <!--    <script type="text/javascript" src="../app/directives/pie-chart-directive.js"></script>-->
    <!--    <script type="text/javascript" src="../app/directives/radar-chart-directive.js"></script>-->
    <!--    <script src="../js/am-charts/amcharts.js" type="text/javascript"></script>-->
    <!--    <script src="../js/am-charts/themes/dark.js" type="text/javascript"></script>-->
    <!--    <script src="../js/am-charts/serial.js" type="text/javascript"></script>-->
    <!--    <script src="../js/am-charts/pie.js" type="text/javascript"></script>-->
    <!--    <script src="../js/am-charts/radar.js" type="text/javascript"></script>-->
    <!--    <script src="../js/am-charts/plugins/export/export.min.js" type="text/javascript"></script>-->

    <script src="../js/toaster.js" type="text/javascript"></script>
    <script type="text/javascript" src="../js/moment.js"></script>
    <script type="text/javascript" src="../js/moment-jalaali.js"></script>
    <script type="text/javascript" src="../js/angular-confirm.min.js"></script>

    <script type="text/javascript" src="../app/directives/serial-chart-directive.js"></script>
    <script type="text/javascript" src="../app/directives/pie-chart-directive.js"></script>
    <script type="text/javascript" src="../app/directives/radar-chart-directive.js"></script>

    <script src="../js/am-charts/amcharts.js" type="text/javascript"></script>
    <script src="../js/am-charts/themes/dark.js" type="text/javascript"></script>
    <script src="../js/am-charts/serial.js" type="text/javascript"></script>
    <script src="../js/am-charts/pie.js" type="text/javascript"></script>
    <script src="../js/am-charts/radar.js" type="text/javascript"></script>
    <script src="../js/am-charts/plugins/export/export.min.js" type="text/javascript"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>

<?php
}

function generateRequiredCMSCssFiles(){
    ?>
<!--    <link href="../js/am-charts/plugins/export/export.css" rel="stylesheet" type="text/css">-->
<!--    <link rel='stylesheet' href='../css/textAngular.css'>-->
    <link rel="stylesheet" href="../css/ADM-dateTimePicker.css" type="text/css">
<!--    <link rel="stylesheet" href="../css/ng-img-crop.min.css" type="text/css">-->
<!--    <link rel="stylesheet" href="../css/anim-in-out.css">-->
    <link rel="stylesheet" href="../css/toaster.css">
    <link rel="stylesheet" href="../cms/css/treasure-overlay-spinner.min.css">
<!--    <link rel="stylesheet" href="../cms/css/select/select.css">-->
<!--    <link rel="stylesheet" href="../css/font-awesome-animation.min.css">-->
<!--    <link rel="stylesheet" href="../css/hover-min.css">-->
<!--    <link rel="stylesheet" href="../cms/css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" href="../css/font-awesome.min.css">-->
<!--    <link rel="stylesheet" href="../css/site-styles.css">-->
<!--    <link rel="stylesheet" href="../cms/css/pallete.css">-->
<!--    <link rel="stylesheet" href="../cms/css/AdminLTE-rtl.css">-->
<!--    <link rel="stylesheet" href="../cms/css/AdminLTE-rtl-fix.css">-->
<!--    <link rel="stylesheet" href="../cms/css/skins/_all-skins-srtl.css">-->

    <link rel="stylesheet" href="../css/page-style.min.css">
    <?php
}


function generateMetas(){
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="fa" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php
}

function generateFooter(){
    ?>

    <footer class="main-footer persian-rtl" style="">
        <div class="pull-left hidden-xs">
            <b>نسخه</b> {{websiteInfo.Version| pNumber}}
        </div>
        <strong class="persian-rtl"></strong>
        <span ng-bind="websiteInfo.CopyrightText | pNumber"></span>

            <span class="text-muted">

              Copyright &copy;
                <span ng-bind="nowDate() | jalaliDateSimple : 'YYYY' "></span>
            -
            <span ng-bind="websiteInfo.CopyrightStartDate | jalaliDateSimple : 'YYYY' "></span>
            </span>

    </footer>
    <?php
}
?>