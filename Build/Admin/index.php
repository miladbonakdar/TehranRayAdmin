<!DOCTYPE html>
<html ng-app="adminApp" ng-controller="MainCtrl as vm" style="background-color: #ECF0F5;">
<head>
    <?php
        require_once  '../cms/functions.php';
        require_once  '../session_generator.php';

        if (!isset($_SESSION)) {
            session_start();
            if(!hasInfo()){?>
        <script>
                window.location ="../";
        </script>
        <?php
            }else{
                generateSessionAsJavascriptVariable();
            }
        }
        generateMetas();
        generateRequiredCMSCssFiles();
        ?>

    <title ng-bind="($title || 'Loading ...')">Loading ...</title>
    <link rel="icon" href="../images/icon.png">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
</head>
<body class="hold-transition skin-blue sidebar-mini vazir-font">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">
                    <b>T</b>
                    R
                </span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg vazir-font">تهران ری</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a class="link" data-toggle="control-sidebar">
                                <i class="fa fa-gears"></i>
                            </a>
                        </li>

                        <li class="dropdown user user-menu" ng-if="user.AdminPermissionLevel =='Base' || user.AdminPermissionLevel =='limitFuctionality'">
                            <a class="dropdown-toggle link" data-toggle="dropdown">
                                <img ng-src="{{session.Image}}" src="../images/Avatar.jpg" class="user-image" alt="User Image" />
                                <span class="hidden-xs" ng-bind="session.FullName"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img ng-src="{{session.Image}}" src="../images/Avatar.jpg" class="img-circle link"
                                        alt="User Image" />
                                    <p>
                                        <span ng-bind="session.FullName"></span>
                                        - ادمین
                                        <small class="vazir-font">
                                            عضویت در سال {{userSession.SignupDate |  jalaliDateSimple:'jYYYY'}}
                                        </small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a  ui-sref="profile" class="btn btn-default btn-flat">پروفایل</a>
                                    </div>
                                    <div class="pull-right">
                                        <a ng-click="logout()" class="btn btn-default btn-flat">خروج</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-right image">
                        <img ng-src="{{session.Image}}" src="../images/Avatar.jpg" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p ng-bind="session.FullName"></p>
                        <a>
                            آنلاین
                            <i class="fa fa-circle text-success"></i>
                        </a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="treeview" id="SDashboard">
                        <a ui-sref="dashboard">
                            <i class="fa fa-tachometer"></i>
                            <span>داشبورد</span>
                        </a>
                    </li>
                    <li class="treeview" id="SUser" ng-if="user.AdminPermissionLevel =='Base'">
                        <a ui-sref="user">
                            <i class="fa fa-user"></i>
                            <span>مدیریت اعضا</span>
<!--                            <small ng-show="UserBadge.UserCount != 0" class="label pull-right bg-orange">-->
<!--                                <span ng-bind="UserBadge.UserCount | pNumber"></span>-->
<!--                            </small>-->
                        </a>
                    </li>
                    <li class="treeview" id="SPhone" ng-if="user.AdminPermissionLevel =='Base'">
                        <a ui-sref="phone">
                            <i class="fa fa-mobile"></i>
                            <span>مدیریت گوشی ها</span>
                        </a>
                    </li>
                    <li class="treeview" id="SStone">
                        <a ui-sref="stone">
                            <i class="fa fa-barcode"></i>
                            <span>سنگ ها</span>
                        </a>
                    </li>
                    <li class="treeview" id="SAddStone" ng-if="user.AdminPermissionLevel =='Base'">
                        <a ui-sref="addStone">
                            <i class="fa fa-plus"></i>
                            <span>افزودن سنگ</span>
                        </a>
                    </li>
                    <li class="treeview" id="SStoneType" ng-if="user.AdminPermissionLevel =='Base'">
                        <a ui-sref="stone_types">
                            <i class="fa fa-database"></i>
                            <span>نوع سنگ</span>
                        </a>
                    </li>
                    <li class="treeview" id="SCope" ng-if="user.AdminPermissionLevel =='Base'">
                        <a ui-sref="cope">
                            <i class="fa fa-cube"></i>
                            <span>سنگ های خام</span>
                        </a>
                    </li>
                    <li class="treeview" id="SReporting">
                        <a ui-sref="reporting">
                            <i class="fa fa-bar-chart"></i>
                            <span>سنگ های موجود</span>
                        </a>
                    </li>
                    <li id="SProfile"   ng-if="user.AdminPermissionLevel =='Base' || user.AdminPermissionLevel =='limitFuctionality'">
                        <a ui-sref="profile">
                            <i class="fa fa-male"></i>
                            <span>پروفایل من </span>
                        </a>
                    </li>
                    <li class="treeview" ng-if="user.AdminPermissionLevel =='Base' || user.AdminPermissionLevel =='limitFuctionality'">
                        <a class="link" href="TehranRey.apk">
                            <i class="fa fa-android"></i>
                            <span>دانلود برنامه اندروید</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a class="link" ng-click="logout()">
                            <i class="fa fa-power-off"></i>
                            <span>خروج</span>
                        </a>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <script id="notifyModal.html" type="text/ng-template">
        <div class="modal-header">
          <h3 class="modal-title">Error in XHR request </h3>
        </div>
        <div class="modal-body">
          <data compile="data"></data>
        </div>
        </script>

        <toaster-container toaster-options="{'time-out': 10000, 'position-class': 'toast-bottom-right', 'close-button':true, 'animation-class': 'toast-bottom-right'}"></toaster-container>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <treasure-overlay-spinner active='spinner.active' spinner-storke-width="3" spinner-size="60"></treasure-overlay-spinner>
            <!-- Content Wrapper. Contains page content -->
            <div ui-view ng-hide="globalSearchActive" id="mainContent" data-anim-speed="600"
                class="anim-in-out anim-slide-below-fade" data-anim-sync="false" style="min-height: 600px;"></div>
            <!--        <div ng-show="globalSearchActive" ng-include src="'partials/GlobalSearch.html'" id="searchContent" ></div>-->
            <!-- /.content-wrapper -->
        </div>
        <?php generateFooter(); ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript::;">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript::;">
                                <i class="menu-icon fa fa-user bg-yellow"></i>
                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
                                    <p>New phone +1(800)555-1234</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript::;">
                                <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
                                    <p>nora@example.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript::;">
                                <i class="menu-icon fa fa-file-code-o bg-green"></i>
                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
                                    <p>Execution time 5 seconds</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript::;">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>
                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript::;">
                                <h4 class="control-sidebar-subheading">
                                    Update Resume
                                    <span class="label label-success pull-right">95%</span>
                                </h4>
                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript::;">
                                <h4 class="control-sidebar-subheading">
                                    Laravel Integration
                                    <span class="label label-warning pull-right">50%</span>
                                </h4>
                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript::;">
                                <h4 class="control-sidebar-subheading">
                                    Back End Framework
                                    <span class="label label-primary pull-right">68%</span>
                                </h4>
                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>
                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked />
                            </label>
                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Allow mail redirect
                                <input type="checkbox" class="pull-right" checked />
                            </label>
                            <p>
                                Other sets of options are available
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Expose author name in posts
                                <input type="checkbox" class="pull-right" checked />
                            </label>
                            <p>
                                Allow the user to show his name in blog posts
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <h3 class="control-sidebar-heading">Chat Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Show me as online
                                <input type="checkbox" class="pull-right" checked />
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Turn off notifications
                                <input type="checkbox" class="pull-right" />
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Delete chat history
                                <a href="javascript::;" class="text-red pull-right">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </label>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
    <script type="text/ng-template" id="popUp.html">
        <div class="modal-header vazir-font text-right">
            <h3 class="modal-title text-danger" ng-bind="popup.Title"></h3>
        </div>
        <div class="modal-body vazir-font text-right" compile="popup.ModalText ">
        </div>
        <div class="modal-footer vazir-font">
            <button class="btn btn-info pull-left" type="button" ng-click="cancel()">بستن</button>
        </div>
    </script>
    <?php generateRequiredCMSJavaFiles() ?>
    <script src="ng-vendor.min.js"></script>
    <script src="templates.min.js"></script>
    <script src="../app/directives/auto-pagination.js"></script>

</body>
</html>
