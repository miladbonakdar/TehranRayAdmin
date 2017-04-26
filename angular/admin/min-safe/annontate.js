var appName = 'adminApp';
var uploadURL = '../api/upload/';
var serviceBaseURL = '../api/admin/';
var debugMode = false;

var app = angular.module(appName, ['ngRoute', 'treasure-overlay-spinner', 'ui.router', 'angular-confirm',
    'oc.lazyLoad', 'ngAnimate', 'toaster', 'ui.bootstrap', 'ui.router.title', 'ui.select',  'ngPersian',
    'ngFileUpload','anim-in-out','am-charts' ,'ADM-dateTimePicker','ngSanitize', 'ngCookies',
    'textAngular']);

app.config([
    '$provide', '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider','ADMdtpProvider',
function ($provide, $stateProvider, $urlRouterProvider, $ocLazyLoadProvider,ADMdtp) {
    // Add nested user links to the "foo" menu.
    $ocLazyLoadProvider.config({
        debug: debugMode,
        events: true
    });
    ADMdtp.setOptions({
        calType: 'jalali',
        format: 'YYYY/MM/DD hh:mm',
       // default: 'today'
    });

    $stateProvider
        // Admin states
        .state("dashboard", {
            url: "/dashboard",
            templateUrl: "angular.partial.Dashboard.html",
            controller: 'DashboardCtrl',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                    return $ocLazyLoad.load([]);
                }],
                $title: function () {
                    return 'داشبورد';
                }
            }
        }).state("profile", {
            url: "/profile/:action",
            templateUrl: "angular.partial.Profile.html",
            controller: 'ProfileCtrl',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                    return $ocLazyLoad.load([]);
                }],
                $title: function () {
                    return 'پروفایل';
                }
            }
        }).state("reporting", {
            url: "/reporting",
            templateUrl: "angular.partial.Reporting.html",
            controller: 'ReportingCtrl',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                    return $ocLazyLoad.load([]);
                }],
                $title: function () {
                    return 'گزارش گیری';
                }
            }
        }).state("stone", {
        url: "/stone",
        templateUrl: "angular.partial.AllStone.html",
        controller: 'AllStoneCtrl',
        resolve: {
            deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                return $ocLazyLoad.load([]);
            }],
            $title: function () {
                return 'لیست سنگ ها';
            }
        }
    }).state("stone_page", {
        url: "/stonePage/:id",
        templateUrl: "angular.partial.Stone.html",
        controller: 'StoneCtrl',
        resolve: {
            deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                return $ocLazyLoad.load([]);
            }],
            $title: function () {
                return 'مشاهده سنگ';
            }
        }
    }).state("stone_types", {
            url: "/stoneTypes",
            templateUrl: "angular.partial.StoneTypes.html",
            controller: 'StoneTypesCtrl',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                    return $ocLazyLoad.load([]);
                }],
                $title: function () {
                    return 'مدیریت انواع سنگ';
                }
            }
        }).state("user", {
        url: "/user/:id",
        templateUrl: "angular.partial.User.html",
        controller: 'UserCtrl',
        resolve: {
            deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                return $ocLazyLoad.load([]);
            }],
            $title: function () {
                return 'مدیریت اعضا';
            }
        }
    }).state("phone", {
        url: "/phone",
        templateUrl: "angular.partial.Phone.html",
        controller: 'PhoneCtrl',
        resolve: {
            deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                return $ocLazyLoad.load([]);
            }],
            $title: function () {
                return 'مدیریت گوشی ها';
            }
        }
    }).state("stone_edit", {
        url: "/StoneEdit/:id",
        templateUrl: "angular.partial.StoneEdit.html",
        controller: 'StoneEditCtrl',
        resolve: {
            deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                return $ocLazyLoad.load([]);
            }],
            $title: function () {
                return 'ویرایض سنگ';
            }
        }
    })
        .state("cope", {
        url: "/Cope",
        templateUrl: "angular.partial.Cope.html",
        controller: 'CopeCtrl',
        resolve: {
            deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                return $ocLazyLoad.load([]);
            }],
            $title: function () {
                return 'مدیریت سنگ های خام';
            }
        }});
    // }).state("award_questions", {
    //     url: "/AwardQuestions",
    //     templateUrl: "angular.partial.AwardQuestions.html",
    //     controller: 'AwardQuestionsCtrl',
    //     resolve: {
    //         deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //             return $ocLazyLoad.load(['../app/directives/auto-pagination.js']);
    //         }],
    //         $title: function () {
    //             return 'مدیریت سوال های جایزه دار';
    //         }
    //     }
    // }).state("message", {
    //         url: "/message/:id",
    //         templateUrl: "angular.partial.Message.html",
    //         controller: 'MessageCtrl',
    //         resolve: {
    //             deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //                 return $ocLazyLoad.load([]);
    //             }],
    //             $title: function () {
    //                 return 'ارسال پیام';
    //             }
    //         }
    //     }).state("skill", {
    //     url: "/skill",
    //     templateUrl: "angular.partial.Skill.html",
    //     controller: 'SkillCtrl',
    //     resolve: {
    //         deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //             return $ocLazyLoad.load([]);
    //         }],
    //         $title: function () {
    //             return 'مدیریت مهارت ها';
    //         }
    //     }
    //     }).state("upload_library", {
    //         url: "/UploadLibrary",
    //         templateUrl: "angular.partial.UploadLibrary.html",
    //         controller: 'UploadLibraryCtrl',
    //         resolve: {
    //             deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //                 return $ocLazyLoad.load([]);
    //             }],
    //             $title: function () {
    //                 return 'آپلود فایل';
    //             }
    //         }
    //     }).state("common_messages", {
    //         url: "/CommonMessage",
    //         templateUrl: "angular.partial.CommonMessage.html",
    //         controller: 'CommonMessageCtrl',
    //         resolve: {
    //             deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //                 return $ocLazyLoad.load([]);
    //             }],
    //             $title: function () {
    //                 return 'پیام های رایج';
    //             }
    //         }
    //     }).state("manage_library", {
    //         url: "/ManageLibrary",
    //         templateUrl: "angular.partial.ManageLibrary.html",
    //         controller: 'ManageLibraryCtrl',
    //         resolve: {
    //             deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //                 return $ocLazyLoad.load([]);
    //             }],
    //             $title: function () {
    //                 return 'مدیریت فایل ها';
    //             }
    //         }
    //     })
    //     .state("new_admin_post", {
    //         url: "/NewAdminPost/:id",
    //         templateUrl: "angular.partial.NewAdminPost.html",
    //         controller: 'NewAdminPostCtrl',
    //         resolve: {
    //             deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //                 return $ocLazyLoad.load([]);
    //             }],
    //             $title: function () {
    //                 return 'مطلب ادمین جدید';
    //             }
    //         }
    //     }).state("admin_post", {
    //         url: "/AdminPost",
    //         templateUrl: "angular.partial.AdminPost.html",
    //         controller: 'AdminPostCtrl',
    //         resolve: {
    //             deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //                 return $ocLazyLoad.load([]);
    //             }],
    //             $title: function () {
    //                 return 'مطلب ادمین';
    //             }
    //         }
    //     }).state("award_question", {
    //     url: "/AwardQuestion/:id",
    //     templateUrl: "angular.partial.AwardQuestion.html",
    //     controller: 'AwardQuestionCtrl',
    //     resolve: {
    //         deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //             return $ocLazyLoad.load([]);
    //         }],
    //         $title: function () {
    //             return 'سوال جایزه دار';
    //         }
    //     }
    // }).state("reporting", {
    //     url: "/Reporting",
    //     templateUrl: "angular.partial.Reporting.html",
    //     controller: 'ReportingCtrl',
    //     resolve: {
    //         deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //             return $ocLazyLoad.load([]);
    //         }],
    //         $title: function () {
    //             return 'گزارش گیری';
    //         }
    //     }
    // }).state("organ_report", {
    //     url: "/OrganReporting",
    //     templateUrl: "angular.partial.OrganReporting.html",
    //     controller: 'OrganReportingCtrl',
    //     resolve: {
    //         deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //             return $ocLazyLoad.load([]);
    //         }],
    //         $title: function () {
    //             return 'گزارش گیری';
    //         }
    //     }
    // }).state("new_popup", {
    //     url: "/NewPopUp:id",
    //     templateUrl: "angular.partial.NewPopUp.html",
    //     controller: 'NewPopUpCtrl',
    //     resolve: {
    //         deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //             return $ocLazyLoad.load([]);
    //         }],
    //         $title: function () {
    //             return 'پاپ آپ جدید';
    //         }
    //     }
    // }).state("popup_manager", {
    //     url: "/PopUpManager",
    //     templateUrl: "angular.partial.PopUpManager.html",
    //     controller: 'PopUpManagerCtrl',
    //     resolve: {
    //         deps: ['$ocLazyLoad', function ($ocLazyLoad) {
    //             return $ocLazyLoad.load([]);
    //         }],
    //         $title: function () {
    //             return 'مدیریت پاپ آپ';
    //         }
    //     }
    // });
    

    $provide.decorator('taOptions', ['taRegisterTool', '$uibModal' , '$delegate',
        function(taRegisterTool,$uibModal, taOptions) {

            taOptions.toolbar = [
                ['h1', 'h4', 'h6', 'p', 'pre', 'quote'],
                ['html', 'customInsertImage','insertLink', 'wordcount', 'charcount'],
                ['bold', 'italics', 'underline', 'strikeThrough', 'ul', 'ol', 'redo', 'undo', 'clear'],
                ['justifyLeft', 'justifyCenter', 'justifyRight'],
                ['dirLtr','dirRtl']
            ];

            taRegisterTool('dirLtr', {
                iconclass: "fa fa-indent",
                action: function(){
                    return this.$editor().wrapSelection("formatBlock", '<P style="direction:ltr">');
                }
            });
            taRegisterTool('dirRtl', {
                iconclass: "fa fa-dedent",
                action: function(){
                    return this.$editor().wrapSelection("formatBlock", '<P style="direction:rtl">');
                }
            });

            // Create our own insertImage button
            taRegisterTool('customInsertImage', {
                iconclass: "fa fa-picture-o",
                action: function($deferred) {
                    var textAngular = this;
                    var savedSelection = rangy.saveSelection();
                    var modalInstance = $uibModal.open({
                        // Put a link to your template here or whatever
                        templateUrl: '../js/text-angular/CustomSelectImageModal.tmpl.html',
                        size: 'md',
                        controller: ['$uibModalInstance', '$scope', 'Upload', '$state',
                            function($uibModalInstance, $scope , Upload , state) {
                                $scope.activeTab = 0;
                                $scope.img = {
                                    url: ''
                                };
                                $scope.submit = function() {
                                    $uibModalInstance.close($scope.img.url);
                                };
                                $scope.cancel = function() {
                                    $uibModalInstance.close();
                                };

                                $scope.getTab= function (tabId) {
                                    $scope.activeTab = tabId;
                                }

                                $scope.addImageLink= function (link) {
                                    $uibModalInstance.close(link);
                                }

                                $scope.filesChanged = function (files, file) {
                                    var url = uploadURL;
                                    url += 'upload_inline_attachment.php';
                                    var data = {file : file};
                                    data.Type = state.current.name;

                                    file.uploader = Upload.upload({
                                        url:  url ,
                                        data: data
                                    });

                                    file.uploader.then(function (resp) {

                                        $uibModalInstance.close(resp.data);
                                        console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
                                    }, function (resp) {
                                        console.log('Error status: ' + resp.status);
                                    }, function (evt) {
                                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                                        evt.config.data.file.percent = progressPercentage;
                                        // evt.config.data.file.loaded = $scope.sizeFilter(evt.loaded);
                                        //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
                                    });
                                }

                                $scope.stopUploadImage = function (file) {
                                    file.uploader.abort();
                                }
                            }
                        ]
                    });

                    modalInstance.result.then(function(imgUrl) {
                        if(!imgUrl)
                            return;

                        rangy.restoreSelection(savedSelection);

                        var embed = '<a href="'+imgUrl+'" target="_blank">' +
                            '<img class="img-responsive link attachment-inline-img" '
                            + ' src="'+imgUrl+'"  /></a>';
                        // insert
                        textAngular.$editor().wrapSelection('insertHtml', embed);

                        //textAngular.$editor().wrapSelection('insertImage', imgUrl);
                        $deferred.resolve();
                    });
                    return false;
                },
                //     // activeState: function(commonElement) {
                //     // return angular.element(taSelection.getSelectionElement()).attr('ng-click');
                // }
            });

            // Now add the button to the default toolbar definition
            // Note: It'll be the last button
            //taOptions.toolbar[3].push('customInsertImage');
            return taOptions;
        }]
    );

    $urlRouterProvider.otherwise(function ($injector, $location) {
        var $state = $injector.get('$state');
        //$state.go('home.home');
        $state.go('dashboard');
    });
}
]);

var persian = { 0: '۰', 1: '۱', 2: '۲', 3: '۳', 4: '۴', 5: '۵', 6: '۶', 7: '۷', 8: '۸', 9: '۹' };
var traverse = function (el) {
    if (el.nodeType == 3) {
        var list = el.data.match(/[0-9]/g);
        if (list != null && list.length != 0) {
            for (var i = 0; i < list.length; i++)
                el.data = el.data.replace(list[i], persian[list[i]]);
        }
    }
    for (var i = 0; i < el.childNodes.length; i++) {
        traverse(el.childNodes[i]);
    }
}

var fixPersianNumbers = function () {
    traverse(document.body);
}

var fixFooter = function () {
    traverse(document.body);
}

var activeElement = function (parent , name) {
    if(name){
        var elem = $(name);
        elem.addClass('active').siblings().removeClass('active');
    }
    var elemP = $(parent);
    elemP.addClass('active').siblings().removeClass('active');
}

var cmsVars = {};
var hideCMS = function (hide) {
    if(hide){
        cmsVars.v1 = $('.content-wrapper').css('marginRight');
        cmsVars.v2 = $('.main-footer').css('marginRight');
        cmsVars.v3 = $('.main-header').css('display');
        cmsVars.v4 = $('.main-sidebar').css('display');
        $('.content-wrapper').css('margin-right','0');
        $('.main-footer').css('margin-right','0');
        $('.main-header').css('display','none');
        $('.main-sidebar').css('display','none');
    }
    else{
        $('.content-wrapper').css('margin-right',cmsVars.v1);
        $('.main-footer').css('margin-right',cmsVars.v2);
        $('.main-header').css('display',cmsVars.v3);
        $('.main-sidebar').css('display',cmsVars.v4);
    }
}


app.factory("Extention", ['$http', '$timeout', '$rootScope', '$state', '$stateParams', 'toaster', '$uibModal',
    function ($http, $timeout, $rootScope, $state, $stateParams, toaster, $uibModal) { // This service connects to our REST API

        $rootScope.logout = function () {
            obj.post('logout').then(function (res) {
                if(res&&res.Status=='success'){
                    window.location = "../";
                }else{
                    obj.popError('مشکل ، لطفا دوباره امتحان کنید.');
                }
            });
        }

        $rootScope.user = session;

        $rootScope.spinner = {};
        var obj = {};
        obj.workers = 0;
        obj.serviceBase = serviceBaseURL;
        obj.debugMode = debugMode;

        obj.noImageClass = 'fa fa-2x fa-user';

        obj.setBusy = function (en) {
            if (en) {
                if (obj.workers === 0)
                    $rootScope.spinner.active = true;
                //$rootScope.progressbar.start();
                obj.workers++;
            } else {
                obj.workers--;
                if (obj.workers === 0)
                    $timeout(obj.disableLoading, 500);
                //$rootScope.progressbar.complete();
            }
        };

        obj.toast = function (data) {
            toaster.pop(data.status, "", data.message, 10000, 'trustedHtml');
        }

        obj.pop = function (status, msg, delay) {
            if (!delay)
                delay = 7000;
            toaster.pop(status, "", msg, delay, 'trustedHtml');
        }

        obj.popError = function (msg, delay) {
            if (!delay)
                delay = 7000;
            toaster.pop('error', "", msg, delay, 'trustedHtml');
        }
        obj.popSuccess = function (msg, delay) {
            if (!delay)
                delay = 7000;
            toaster.pop('success', "", msg, delay, 'trustedHtml');
        }
        obj.popInfo = function (msg, delay) {
            if (!delay)
                delay = 7000;
            toaster.pop('info', "", msg, delay, 'trustedHtml');
        }

        obj.get = function (q) {
            obj.setBusy(true);
            return $http.get(obj.serviceBase + q).then(function (results) {
                obj.setBusy(false);
                return results.data;
            }, function (err) {
                obj.setBusy(false);
                return err;
            });
        };

        obj.getExternal = function (q) {
            obj.setBusy(true);
            return $http.get(q).then(function (results) {
                obj.setBusy(false);
                return results.data;
            }, function (err) {
                obj.setBusy(false);
                return err;
            });
        };

        obj.post = function (q, object) {
            obj.setBusy(true);
            return $http.post(obj.serviceBase + q, object).then(function (results) {

                if(obj.debugMode ){
                    console.log(results.data);

                    if(results.status != 200)
                        obj.popModal(results);
                }
                obj.setBusy(false);

                if(results.data.AuthState && results.data.AuthState == 'UN_AUTH'){
                    console.log('State : UN_AUTHORIZED_USER');
                    window.location = '../';
                }

                return results.data;
            }, function (err) {

                if(obj.debugMode){
                    console.log(err.data);
                    obj.popModal(err.data);
                }

                if(err.data.AuthState && err.data.AuthState == 'UN_AUTH'){
                    console.log('State : UN_AUTHORIZED_USER');
                    window.location = '../';
                }

                obj.setBusy(false);
                return err;
            });
        };

        obj.postAsync = function (q, object) {
            return $http.post(obj.serviceBase + q, object).then(function (results) {

                if(obj.debugMode && results.status != 200){
                    obj.popModal(results);
                }
                return results.data;
            }, function (err) {
                if(obj.debugMode)
                    obj.popModal(err.data);
                return err;
            });
        };

        obj.disableLoading = function () {
            $rootScope.spinner.active = false;
        }


        obj.authUser = function (user) {
            $rootScope.authenticated = true;
            $rootScope.user = {};
            $rootScope.user.UserID = user.UserID;
            $rootScope.user.lastName = user.LastName;
            $rootScope.user.firstName = user.FirstName;
        }

        obj.unAuthUser = function () {
            $rootScope.authenticated = false;
            $rootScope.isAdmin = false;
            $rootScope.user = {};
        }

        obj.isAdmin = function () {
            return $rootScope.isAdmin;
        }

        obj.getAuth = function () {
            return { authenticated: $rootScope.authenticated, isAdmin: $rootScope.isAdmin };
        }

        obj.openSignupPanel = function (lang) {
            var template;
            if (lang == 'en')
                template = 'partials/HomeEN/SignupTemplate.html';
            else
                template = 'partials/Home/SignupTemplate.html';

            $uibModal.open({
                animation: true,
                templateUrl: template,
                controller: 'authCtrl',
                size: 'md'
            });
        }

        obj.openSigninPanel = function (lang) {
            var template;
            if (lang == 'en')
                template = 'partials/HomeEN/LoginTemplate.html';
            else
                template = 'partials/Home/LoginTemplate.html';

            $uibModal.open({
                animation: true,
                templateUrl: template,
                controller: 'authCtrl',
                size: 'md'
            });
        }

        obj.scrollTo = function (y) {

            var startY = currentYPosition();
            var stopY = y;//elmYPosition(eID);
            var distance = stopY > startY ? stopY - startY : startY - stopY;
            if (distance < 100) {
                scrollTo(0, stopY); return;
            }
            var speed = Math.round(distance / 10);
            if (speed >= 5) speed = 5;
            var step = Math.round(distance / 25);
            var leapY = stopY > startY ? startY + step : startY - step;
            var timer = 0;
            if (stopY > startY) {
                for (var i = startY; i < stopY; i += step) {
                    setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
                    leapY += step; if (leapY > stopY) leapY = stopY; timer++;
                } return;
            }
            for (var i = startY; i > stopY; i -= step) {
                setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
                leapY -= step; if (leapY < stopY) leapY = stopY; timer++;
            }

            function currentYPosition() {
                // Firefox, Chrome, Opera, Safari
                if (self.pageYOffset) return self.pageYOffset;
                // Internet Explorer 6 - standards mode
                if (document.documentElement && document.documentElement.scrollTop)
                    return document.documentElement.scrollTop;
                // Internet Explorer 6, 7 and 8
                if (document.body.scrollTop) return document.body.scrollTop;
                return 0;
            }
        };
        obj.scrollToElement = function (element, offsetY) {
            if (!offsetY)
                offsetY = 0;
            var startY = currentYPosition();
            var stopY = elmYPosition(element) + offsetY;
            var distance = stopY > startY ? stopY - startY : startY - stopY;
            if (distance < 100) {
                scrollTo(0, stopY); return;
            }
            var speed = Math.round(distance / 10);
            if (speed >= 5) speed = 5;
            var step = Math.round(distance / 25);
            var leapY = stopY > startY ? startY + step : startY - step;
            var timer = 0;
            if (stopY > startY) {
                for (var i = startY; i < stopY; i += step) {
                    setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
                    leapY += step; if (leapY > stopY) leapY = stopY; timer++;
                } return;
            }
            for (var i = startY; i > stopY; i -= step) {
                setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
                leapY -= step; if (leapY < stopY) leapY = stopY; timer++;
            }

            function currentYPosition() {
                // Firefox, Chrome, Opera, Safari
                if (self.pageYOffset) return self.pageYOffset;
                // Internet Explorer 6 - standards mode
                if (document.documentElement && document.documentElement.scrollTop)
                    return document.documentElement.scrollTop;
                // Internet Explorer 6, 7 and 8
                if (document.body.scrollTop) return document.body.scrollTop;
                return 0;
            }

            function elmYPosition(eID) {
                var elm = document.getElementById(eID);
                var y = elm.offsetTop;
                var node = elm;
                while (node.offsetParent && node.offsetParent != document.body) {
                    node = node.offsetParent;
                    y += node.offsetTop;
                } return y;
            }

        };

        return obj;
    }]);

app.factory("OnlineSocket", ['$http', '$timeout', '$rootScope', 'Extention',
    function ($http, $timeout, $rootScope, Extention) { // This service connects to our REST API

        var obj = {};
        obj.getData = function () {
            $timeout(function () {
                obj.fetch();
            }, 50000);
        };

        obj.fetch = function () {
            Extention.postAsync('getSocketData').then(function (res) {
                $rootScope.socketData = res;
                $timeout(function () {
                    $rootScope.$broadcast('socketDataChanged');
                });
                obj.getData();
            });
        }

        obj.fetch();

        return obj;
    }]);

app.directive('errSrc', function () {
    return {
        link: function (scope, element, attrs) {
            element.bind('error', function () {
                if (attrs.src != attrs.errSrc) {
                    attrs.$set('src', attrs.errSrc);
                }
            });
        }
    }
});

app.run(['$rootScope', '$templateCache', '$state', '$location', 'Extention', 'OnlineSocket', function ($rootScope, $templateCache, $state, $location, Extention, OnlineSocket) {

    $rootScope.spinner ={};

    $rootScope.$on("$stateChangeSuccess", function () {
        Extention.setBusy(false);
    });
    $rootScope.$on('$stateChangeError',
        function(event, toState, toParams, fromState, fromParams, error){
            Extention.setBusy(false);
        });
    $rootScope.$on('$stateNotFound',
        function(event, unfoundState, fromState, fromParams){
            Extention.setBusy(false);
        })

    $rootScope.$on("$stateChangeStart", function (event, next, current) {
        Extention.setBusy(true);
        $rootScope.globalSearchActive = false;
    });

}]);

app.filter('fileSizeFilter', function() {
    return function(bytes, precision) {
        if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
        if (typeof precision === 'undefined') precision = 1;
        var units = ['بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت', 'ترابایت', 'پتابایت'],
            number = Math.floor(Math.log(bytes) / Math.log(1024));
        return persianJs((bytes / Math.pow(1024, Math.floor(number))).toFixed(precision)).englishNumber().toString() +
            ' '+units[number] ;
    }
});
app.filter('fileSizeFilterEnglish', function() {
    return function(bytes, precision) {
        if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
        if (typeof precision === 'undefined') precision = 1;
        var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PT'],
            number = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) + ' '+units[number] ;
    }
});

app.filter('jalaliDate', function () {
    return function (inputDate, format) {
        var date = moment(inputDate);
        return date.fromNow() + " " + date.format(format);
    }
});

app.filter('jalaliDateSimple', function () {
    return function (inputDate, format) {
        var date = moment(inputDate);
        return date.format(format);
    }
});

app.filter('moment', function () {
    return function (inputDate, format) {
        return moment(inputDate).format(format);
    }
});

app.filter('subString', function () {
    return function (text, length) {
        if (text && text.length > length) {
            return text.substr(0, length) + "...";
        }
        return text;
    }
});
app.filter('fromNow', function () {
    return function (inputDate) {
        var date = moment(inputDate);
        return date.fromNow();
    }
});

app.filter('split', function () {
    return function (input, splitChar, feildName) {
        if (!input)
            return "";
        var str = "";
        for (var i = 0; i < input.length; i++) {
            if (i === input.length - 1)
                str += (input[i][feildName]);
            else
                str += (input[i][feildName] + splitChar);
        }
        return str;
    }
});

app.filter('Length', function () {
    return function (input) {
        var number = Number(input);
        number /= 10;
        return number.toString() +" سانتی متر";
    }
});

app.filter('Volume', function () {
    return function (input) {
        var number = Number(input);
        number /= 1000000;
        return number.toString() +" متر مربع";
    }
});
app.filter('Weight', function () {
    return function (input) {
        if(input.length > 3)
        {
            var number = Number(input);
            number /= 1000;
            return number.toString() +" تن";
        }else
            return input +" تن";
    }
});
app.filter('Toman', function () {
    return function (input) {
            return input +"  هزار تومان";
    }
});
app.filter('Time', function () {
    return function (input) {
        var myDate = new Date(input);

        return myDate.getHours() +":"+myDate.getMinutes();
    }
});

app.directive('slideable', function () {
    return {
        restrict: 'C',
        compile: function (element, attr) {
            // wrap tag
            var contents = element.html();
            element.html('<div class="slideable_content" style="margin:0 !important; padding:0 !important" >' + contents + '</div>');

            return function postLink(scope, element, attrs) {
                // default properties

                attrs.duration = (!attrs.duration) ? '1s' : attrs.duration;
                attrs.easing = (!attrs.easing) ? 'ease-in-out' : attrs.easing;
                element.css({
                    'overflow': 'hidden',
                    'height': '0px',
                    'transitionProperty': 'height',
                    'transitionDuration': attrs.duration,
                    'transitionTimingFunction': attrs.easing
                });
            };
        }
    };
});

app.directive('slideToggle', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            var target = document.querySelector(attrs.slideToggle);
            attrs.expanded = false;
            element.bind('click', function () {
                var content = target.querySelector('.slideable_content');
                if (!attrs.expanded) {
                    content.style.border = '1px solid rgba(0,0,0,0)';
                    var y = content.clientHeight;
                    content.style.border = 0;
                    target.style.height = y + 'px';
                } else {
                    target.style.height = '0px';
                }
                attrs.expanded = !attrs.expanded;
            });
        }
    }
});

app.directive('compile', [
    '$compile', function ($compile) {
        return function (scope, element, attrs) {
            scope.$watch(
                function (scope) {
                    // watch the 'compile' expression for changes
                    return scope.$eval(attrs.compile);
                },
                function (value) {
                    // when the 'compile' expression changes
                    // assign it into the current DOM
                    element.html(value);

                    // compile the new DOM and link it to the current
                    // scope.
                    // NOTE: we only compile .childNodes so that
                    // we don't get into infinite loop compiling ourselves
                    $compile(element.contents())(scope);
                }
            );
        };
    }
]);

angular.module("ui.router.title", ["ui.router"])
	.run(["$rootScope", "$timeout", "$state", "Extention", function ($rootScope, $timeout, $state, Extention) {

	    $rootScope.$on("$stateChangeSuccess", function () {
	        var title = $state.$current.locals.globals.$title;
	        var isAsync = $state.$current.locals.globals.$isAsyncTitle;
	        if (isAsync) {
	            Extention.post(title).then(function (res) {
	                $timeout(function () {
	                    $rootScope.$title = res.SiteName;
	                });

	                $rootScope.$breadcrumbs = [];
	                var state = $state.$current;
	                while (state) {
	                    if (state.resolve && state.resolve.$title) {
	                        $rootScope.$breadcrumbs.unshift({
	                            title: getTitleValue(state.locals.globals.$title),
	                            state: state.self.name,
	                            stateParams: state.locals.globals.$stateParams
	                        });
	                    }
	                    state = state.parent;
	                }
	            });
	        } else {
	            var t = getTitleValue(title);
	            $timeout(function () {
	                $rootScope.$title = t;
	            });

	            $rootScope.$breadcrumbs = [];
	            var state = $state.$current;
	            while (state) {
	                if (state.resolve && state.resolve.$title) {
	                    $rootScope.$breadcrumbs.unshift({
	                        title: getTitleValue(state.locals.globals.$title),
	                        state: state.self.name,
	                        stateParams: state.locals.globals.$stateParams
	                    });
	                }
	                state = state.parent;
	            }
	        }
	    });

	    function getTitleValue(title) {
	        return angular.isFunction(title) ? title() : title;
	    }

	}]);
angular.module(appName).controller('AdminCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', '$log', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, $log) {

}]);

angular.module(appName).controller('AllStoneCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', 'Extention', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention) {
    $scope.pagingParams = {};
	$scope.pagingController = {};
	console.log($rootScope.user.AdminPermissionLevel);
	$scope.search = function () {
		$scope.pagingController.update();
	}

	$scope.removeStone = function (uid) {
	    Extention.post('deleteStone', { StoneID: uid }).then(function (res) {
			if(res && res.Status=='success'){
			    Extention.popSuccess("سنگ با موفقیت حذف شد!");
				$scope.pagingController.update();
			}else{
			    Extention.popError("مشکل در حذف سنگ ، لطفا دوباره امتحان کنید.");
			}
		});
	}
	activeElement('#SStone');
}]);

angular.module(appName).controller('CopeCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', 'Extention', '$uibModal', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {
    $scope.pagingParams = {};
    $scope.pagingController = {};
    $scope.cope ={};
    $scope.StoneType ={selected:null};
    $scope.StoneTypes =[];

    Extention.postAsync('getAllStoneTypes', {}).then(function (msg) {
        $scope.StoneTypes = msg.Data;
    });

    $scope.search = function () {
        $scope.pagingController.update();
    }
    $scope.editCope =function (cope) {
        $scope.cope = cope;
        for (var i = 0 ; i<$scope.StoneTypes.length ; i++){
            if($scope.StoneTypes[i].StoneTypeID == cope.StoneTypeID){
                $scope.StoneType.selected =$scope.StoneTypes[i];
                break;
            }
        }
    }
    $scope.cancleEdite =function () {
        $scope.cope = {};
        $scope.StoneType.selected = null;
    }
    $scope.insertNewUser= function() {

        if (!$scope.user.FullName || $scope.user.FullName.length < 3) {Extention.popError('نام کامل وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.Email || $scope.user.Email.length < 3) {Extention.popError('ایمیل وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.Username || $scope.user.Username.length < 3) {Extention.popError('نام کاربری وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.pass || $scope.user.pass.length < 3) {Extention.popError('پسورد وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.passRe || $scope.user.passRe.length < 3) {Extention.popError('تکرار پسورد وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.UserType.selected) {Extention.popError('نوع کاربر را انتخاب کنید');return}
        if ($scope.user.passRe != $scope.user.pass) {Extention.popError('پسورد با تکرار آن برابر نیست');return}
        $scope.user.PermissionID = $scope.UserType.selected.AdminPermissionID;
        Extention.post('savePerson', $scope.user).then(function (res) {
            console.log(res);
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $scope.user ={};
                $scope.pagingController.update();
            } else {
                Extention.popError(res.Message);
            }
        });
    }

    $scope.insertCope= function() {
        if (!$scope.cope.CopeName || $scope.cope.CopeName.length < 3) {Extention.popError('کد سنگ خام را وارد کنید');return}
        if (!$scope.cope.Weight) {Extention.popError('وزن سنگ را وارد کنید');return}
        if (!$scope.cope.UnitPrice) {Extention.popError('قیمت واحد را وارد کنید');return}
        if (!$scope.StoneType.selected) {Extention.popError('نوع سنگ را انتخاب کنید');return}
        $scope.cope.StoneTypeID = $scope.StoneType.selected.StoneTypeID;
        Extention.post('insertOrEditCope', $scope.cope).then(function (res) {
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $scope.cope ={};
                $scope.StoneType.selected = null;
                $scope.pagingController.update();
            } else {
                Extention.popError("مشکل در وارد کردن سنگ خام ، لطفا دوباره تلاش کنید.");
            }
        });
    }
    $scope.deleteCope= function(id) {
        Extention.post('deleteCope', {CopeID : id}).then(function (res) {
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $scope.pagingController.update();
            } else {
                Extention.popError(res.Message);
            }
        });
    }

	activeElement('#SCope');
}]);
angular.module(appName).controller('DashboardCtrl', ['$scope', 'ADMdtpConvertor', '$rootScope', 'Extention', '$state', '$timeout', function ($scope, ADMdtpConvertor, $rootScope, Extention, $state, $timeout) {

    activeElement('#SDashboard');
}]);

angular.module(appName).controller('PhoneCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', 'Extention', '$uibModal', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {
    $scope.pagingParams = {};
    $scope.pagingController = {};
    $scope.phone ={};
    $scope.search = function () {
        $scope.pagingController.update();
    }

    $scope.insertNewPhone= function() {

        if (!$scope.phone.Username || $scope.phone.Username.length < 3) {Extention.popError('نام کاربری وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.phone.pass || $scope.phone.pass.length < 3) {Extention.popError('پسورد وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.phone.passRe || $scope.phone.passRe.length < 3) {Extention.popError('تکرار پسورد وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if ($scope.phone.passRe != $scope.phone.pass) {Extention.popError('پسورد با تکرار آن برابر نیست');return}
        Extention.post('savePhone', $scope.phone).then(function (res) {
            console.log(res);
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $scope.pagingController.update();
            } else {
                Extention.popError(res.Message);
            }
        });
    }
    $scope.deletePhone= function(id) {
        Extention.post('deletePhone', {PhoneID : id}).then(function (res) {
            console.log(res);
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $scope.pagingController.update();
            } else {
                Extention.popError(res.Message);
            }
        });
    }

	activeElement('#SPhone');
}]);

angular.module(appName).controller('ProfileCtrl', ['$scope', '$rootScope', '$stateParams', '$state', '$uibModal', '$timeout', 'Extention', 'Upload', function ($scope, $rootScope, $stateParams, $state, $uibModal,$timeout, Extention,Upload) {

    $scope.onChangeAvatar = function ($files, $file) {
        if($files.length == 0)
            return;
        $uibModal.open({
            animation: true,
            templateUrl: 'cropModal.html',
            controller: ['$scope', '$uibModalInstance', 'file', function ($scope , $uibModalInstance , file) {
                $scope.croppedImage = {};

                $scope.cancel = function () {
                    $uibModalInstance.dismiss();
                };

                if(file != null){

                    var reader = new FileReader();
                    reader.onload = function (evt) {
                        $scope.$apply(function($scope){
                            $scope.myImage = evt.target.result;
                        });
                    };
                    reader.readAsDataURL(file);
                }


                $scope.changeAvatar = function () {
                    Extention.setBusy(true);

                    var dataURItoBlob = function(dataURI) {
                        var binary = atob(dataURI.split(',')[1]);
                        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
                        var array = [];
                        for(var i = 0; i < binary.length; i++) {
                            array.push(binary.charCodeAt(i));
                        }
                        return new Blob([new Uint8Array(array)], {type: mimeString});
                    };

                    var file = dataURItoBlob($scope.croppedImage);
                    file.name = 'cropfile.png';
                    file.upload = Upload.upload({
                        url: serviceBaseURL + 'updateAvatar',
                        data: {
                            file: file
                        }
                    });

                    $scope.uploading = true;
                    Extention.popInfo('لطفا تا پایان تغییر تصویر صبر کنید.');

                    file.upload.then(function (response) {
                        $timeout(function () {
                            Extention.popSuccess('تصویر با موفقیت تغییر کرد!');
                            //$scope.myWatcher =$scope.addWatcherForFileChanges();
                            Extention.setBusy(false);
                            session.Image = response.data.Image;
                            $rootScope.user.Image = response.data.Image;
                            $state.go('profile', {}, {reload: true});
                            $uibModalInstance.dismiss();
                        });
                    }, function (response) {
                        if (response.status > 0) {
                            Extention.popError('مشکل در تغییر تصویر پروفایل');
                        }else{
                            Extention.popSuccess('تصویر با موفقیت تغییر کرد!');
                        }
                        //$scope.myWatcher =$scope.addWatcherForFileChanges();
                        Extention.setBusy(false);
                        $state.go('profile', {}, {reload: true});

                    }, function (evt) {
                        // Math.min is to fix IE which reports 200% sometimes
                        file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));

                    });
                }

            }],
            size: 'md',
            resolve: {
                file: function () {
                    return $file;
                }
            }
        });
    }


    $scope.timelinePagingController = {};

    if(!$stateParams.action){
        $scope.activeTab = 2;
    }else{
        switch ($stateParams.action){
            case 'Sessions':
                $scope.activeTab = 1;
                break;
            case 'Info':
                $scope.activeTab = 2;
                break;
        }
    }

    $scope.bgColorArray= ["bg-aqua-active",
        "bg-blue-active","bg-green-active",
        "bg-yellow-active","bg-maroon-active","bg-light-blue-active",
        "bg-green","bg-orange","bg-purple","bg-red",
        "bg-yellow","bg-light-blue"];

    $scope.getRandomColorClass = function(id){
        var i = id % $scope.bgColorArray.length;
        return $scope.bgColorArray[i];
    }

    $scope.getIconClass= function (item){
        switch (item.EventTypeID){
            // ثبت نام
            case '-1':
                return 'fa-user-plus ipalette-bg-sun-flower';
            // <tr><td>1</td><td>به جواب شما امتیاز مثبت داد.</td></tr>
            case '1':
            // <tr><td>2</td><td>به سوال شما امتیاز مثبت داد.</td></tr>
            case '2':
                return 'fa-star ipalette-bg-sun-flower';

            // <tr><td>3</td><td>سوال شما را تایید کرد.</td></tr>
            case '3':
                return 'fa-check ipalette-bg-peter-river';

            // <tr><td>4</td><td>سوال جدید مطرح کرده.</td></tr>
            case '4':
                return 'fa-flask  ipalette-bg-pomegranate';

            // <tr><td>5</td><td>پیام جدید برای شما ارسال کرد.</td></tr>
            case '5':
                return 'fa-envelope  ipalette-bg-pumpkin';

            // <tr><td>6</td><td>سوال شما را دنبال کرد.</td></tr>
            case '6':
                return 'fa-user  ipalette-bg-emerald';

            // <tr><td>7</td><td>فعالیت شما را پیگیر می شود.</td></tr>
            case '7':
                return 'fa-eye ipalette-bg-pomegranate';

            // <tr><td>8</td><td>به سوال شما جواب داده است.</td></tr>
            case '8':
                return 'fa-hand-pointer-o ipalette-bg-alizarin';

            // <tr><td>9</td><td>جواب جدید ، برای سوال دنبال شده شما ثبت کرده است.</td></tr>
            case '9':
                return 'fa-comments ipalette-bg-amethyst';

            // <tr><td>10</td><td>جواب شما را تایید کرد.</td></tr>
            case '10':
                return 'fa-check ipalette-bg-green-sea';

            // <tr><td>11</td><td>سوال جدید برای موضوع دنبال شده شما ارسال کرد.</td></tr>
            case '11':
                return 'fa-flask ipalette-bg-orange';
            default :
                return 'fa-clone ipalette-bg-orange';
        }
    }

    $scope.getTab= function (tabId) {
        $scope.activeTab = tabId;
        var opt = {
            location: true,
            inherit: true,
            relative: $state.$current,
            notify: false
        };
        switch ($scope.activeTab){
            case 1:
                $state.transitionTo('profile', {action: 'Sessions'}, opt );
                break;
            case 2:
                $state.transitionTo('profile', {action:'Info'}, opt);
                break;
        }
    }

    $scope.isEqualWithVerify = true;

    $scope.getUser = function () {

        Extention.post('getUserProfile').then(function (res) {
            $scope.curUser = res;
            console.log(res);
        });
    }
    $scope.getUser();

    $scope.getRandomSpan = function(){
        var i = Math.floor((Math.random()*6)+1);

        switch (i){
            case 1:
                return 'danger';
            case 2:
                return 'success';
            case 3:
                return 'info';
            case 4:
                return 'warning';
            case 5:
                return 'primary';
        }
    }
    
    $scope.saveUserInfo = function () {

        if($scope.curUser.Password && ($scope.curUser.VerifyPassword != $scope.curUser.Password) ||
            $scope.curUser.VerifyPassword && ($scope.curUser.VerifyPassword != $scope.curUser.Password) )
        {
            Extention.popError('رمز وارد شده با تکرار آن یکسان نیست!');
            return;
        }

        if($scope.curUser.Password && $scope.curUser.Password.length < 5 )
        {
            Extention.popError(persianJs('رمز جدید بایستی حداقل 5 کاراکتر باشد!').englishNumber().toString() );
            return;
        }

        Extention.post('saveUserInfo', $scope.curUser).then(function (res) {

            if(res && res.Status=='success'){
                Extention.popSuccess('با موفقیت تغییر کرد!');
                session.FullName = res.FullName;
                $rootScope.user.FullName = res.FullName;
                $scope.curUser.Password=undefined;
                $scope.curUser.VerifyPassword=undefined;
                $scope.curUser.OldPassword=undefined;
            }else{
                if(res.Message == 'EmailExists'){
                    Extention.popWarning('خطا : این ایمیل قبلا ثبت شده ، لطفا ایمیل دیگری انتخاب کنید.',12000);
                }else if(res.Message == 'OldPasswordIsNotValid'){
                    Extention.popError('خطا : رمز عبور فعلی اشتباه است.');
                }else if(res.Message == 'PasswordIsNotValid'){
                    Extention.popError(persianJs('خطا : رمز عبور جدید بایستی حداقل 5 کاراکتر باشد.').englishNumber().toString());
                }else {
                    Extention.popError('مشکل در تغییر اطلاعات ، لطفا دوباره تلاش کنید.');
                }
            }
        });
    }

    $scope.saveUserAddintionalInfo = function () {
        Extention.post('saveUserAddintionalInfo', $scope.curUser).then(function (res) {
            if(res && res.Status=='success'){
                Extention.popSuccess('با موفقیت تغییر کرد!');
            }else{
                Extention.popError('مشکل در تغییر اطلاعات ، لطفا دوباره تلاش کنید.');
            }
        });
    }


    $scope.passwordChanged = function () {
        if(!$scope.curUser.Password && !$scope.curUser.VerifyPassword)
        {
            $scope.isEqualWithVerify = true;
            return;
        }
        $scope.isEqualWithVerify = $scope.curUser.VerifyPassword == $scope.curUser.Password;
    }

    $scope.randomClass= ['danger','info','success','warning'];
    $scope.randomColor = function (id) {
        return $scope.randomClass[id % $scope.randomClass.length];
    }

    $scope.removeSession= function (id) {
        Extention.post('deleteSession',{UserSessionID : id}).then(function (res) {

            if(res){
                if (res.Status=='success') {
                    Extention.popSuccess('اتصال با موفقیت قطع شد.');
                    $scope.getUser();
                }
                else if(res.Message == 'CurrentSession'){
                    Extention.popInfo('مشکل در قطع اتصال ، شما با این شناسه متصل هستید!');
                }
                else {
                    Extention.popError('مشکل در قطع اتصال ، لطفا دوباره تلاش کنید.');
                }
            }else {
                Extention.popError('مشکل در قطع اتصال ، لطفا دوباره تلاش کنید.');
            }
        });
    }

    activeElement('#SProfile');
}]);

angular.module(appName).controller('ReportingCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', '$stateParams', '$uibModal', 'Extention', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, $stateParams, $uibModal, Extention) {
    $scope.pagingParams = { Position: null };
    $scope.pagingController = {};
    $scope.Machine ={selected :null};
    $scope.StoneType ={selected :[]};
    $scope.StoneState ={selected :null};
    $scope.machines = [{
        Name : "ماشین A",
        ID : "1"
    },{
        Name : "ماشین B",
        ID : "2"
    }];

    $scope.StoneStates = [{
        Name : "فروخته شده",
        ID : "2"
    },{
        Name : "موجود",
        ID : "1"
    }];

    if($rootScope.user.AdminPermissionLevel =='viewStones'){
        $scope.StoneState.selected = $scope.StoneStates[1];
    }

    Extention.postAsync('getAllStoneTypes', {}).then(function (msg) {
        $scope.StoneTypes = msg.Data;
    });
    $scope.search = function () {
        $scope.pagingParams.MachineID = ($scope.Machine.selected) ? $scope.Machine.selected.ID : null;
        $scope.pagingParams.StoneStateID = ($scope.StoneState.selected) ? $scope.StoneState.selected.ID : null;
        if($scope.Height){
            $scope.pagingParams.Height = Number($scope.Height)*10;
        }
        if($scope.Width){
            $scope.pagingParams.Width = Number($scope.Width)*10;
        }
        if($scope.Area){
            $scope.pagingParams.Area = $scope.Area*1000000;
        }
        if($scope.EndDate){
            var EndDate = new Date($scope.toFullEnd.unix);
            $scope.pagingParams.EndDate  = EndDate;
        }
        if($scope.StartDate){
            var StartDate = new Date($scope.toFullStart.unix);
            $scope.pagingParams.StartDate  = StartDate;
        }

        if($scope.StoneType.selected.length > 0){
            $scope.pagingParams.StoneTypeID = "("+$scope.StoneType.selected[0].StoneTypeID;
            for(var i = 1 ; i < $scope.StoneType.selected.length ; i++){
                $scope.pagingParams.StoneTypeID += ","+$scope.StoneType.selected[i].StoneTypeID;
            }
            $scope.pagingParams.StoneTypeID += ")";
        }else
            $scope.pagingParams.StoneTypeID = null;
        $scope.pagingController.update();
    }

    $scope.getStoneImage = function (id , image) {
        $timeout(function () {
            document.getElementById("stoneImage"+id).setAttribute("src","data:image/png;base64,"+image);
        })
    }

    activeElement('#SReporting','#SPersonReport');
}]);

angular.module(appName).controller('StoneCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', '$stateParams', 'Extention', function ($scope, $rootScope, $routeParams, $state, $location, $timeout,$stateParams, Extention) {
	$scope.stone = null;

    ($scope.getStoneByID = function () {
        Extention.post("getStone", { StoneID: $stateParams.id }).then(function (res) {
            if (res.Status == 'success') {
                $scope.stone = res.Data;
                document.getElementById("stoneImage").setAttribute("src","data:image/png;base64,"+$scope.stone.Image);
                console.log(res);
            } else {
                $scope.stone = null;
                console.log(res);
            }
        });
    })();
}]);

angular.module(appName).controller('StoneEditCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', '$stateParams', 'Extention', function ($scope, $rootScope, $routeParams, $state, $location, $timeout,$stateParams, Extention) {
	$scope.stone = null;
    $scope.Machine ={selected :null};
    $scope.Direction ={selected :null};
    $scope.StoneType ={selected :null};
    $scope.CopeType ={selected :null};
    $scope.StoneState ={selected :null};
    $scope.machines = [{
        Name : "ماشین A",
        ID : "1"
    },{
        Name : "ماشین B",
        ID : "2"
    },{
        Name : "ماشین C",
        ID : "3"
    }];

    $scope.Directions = [{
        Name : "بدون بوک مچ",
        Value : ""
    },{
        Name : "چپ",
        Value : "LEFT"
    },{
        Name : "راست",
        Value : "RIGHT"
    }];

    $scope.StoneStates = [{
        Name : "فروخته شده",
        ID : "2"
    },{
        Name : "موجود",
        ID : "1"
    }];


    ($scope.getStoneByID = function () {
        Extention.post("getStone", { StoneID: $stateParams.id }).then(function (res) {
            if (res.Status == 'success') {
                res.Data.CopeNumber = Number(res.Data.CopeNumber);
                res.Data.Area = Number(res.Data.Area);
                res.Data.Width = Number(res.Data.Width);
                res.Data.Height = Number(res.Data.Height);
                $scope.stone = res.Data;
                document.getElementById("stoneImage").setAttribute("src","data:image/png;base64,"+$scope.stone.Image);
                Extention.postAsync('getAllStoneTypes', {}).then(function (msg) {
                    $scope.StoneTypes = msg.Data;
                    for(var i =0 ;i<$scope.StoneTypes.length;i++){
                        if($scope.stone.StoneTypeID == $scope.StoneTypes[i].StoneTypeID)
                        {$scope.StoneType.selected = $scope.StoneTypes[i];break;}
                    }
                });
                Extention.postAsync('getAllCopeTypes', {}).then(function (msg) {
                    $scope.copeTypes = msg.Data;
                    for(var i =0 ;i<$scope.copeTypes.length;i++){
                        if($scope.stone.CopeID == $scope.copeTypes[i].CopeID)
                        {$scope.CopeType.selected = $scope.copeTypes[i];break;}
                    }
                });
                for(var i =0 ;i<$scope.machines.length;i++){
                    if($scope.stone.MachineNumber == $scope.machines[i].ID)
                    {$scope.Machine.selected = $scope.machines[i];break;}
                }
                for(var i =0 ;i<$scope.Directions.length;i++){
                    if($scope.stone.BockMachDirection == $scope.Directions[i].Value)
                    {$scope.Direction.selected = $scope.Directions[i];break;}
                }
                for(var i =0 ;i<$scope.StoneStates.length;i++){
                    if($scope.stone.Sold == ($scope.StoneStates[i].ID)-1)
                    {$scope.StoneState.selected = $scope.StoneStates[i];break;}
                }
                console.log(res);
            } else {
                $scope.stone = null;
                console.log(res);
            }
        });
    })();

    $scope.editStone = function () {
        if (!$scope.StoneType.selected) {Extention.popError('نوع سنگ را انتخاب کنید');return}
        if (!$scope.Machine.selected) {Extention.popError('ماشین تولید کننده انتخاب نشده است');return}
        if (!$scope.Direction.selected) {Extention.popError('جهت بوک مچ انتخاب نشده است');return}
        if (!$scope.CopeType.selected) {Extention.popError('بلوک سنگ انتخاب نشده است');return}
        if (!$scope.StoneState.selected) {Extention.popError('وضعیت سنگ انتخاب نشده است');return}
        $scope.stone.StoneTypeID = $scope.StoneType.selected.StoneTypeID;
        $scope.stone.MachineNumber = $scope.Machine.selected.ID;
        $scope.stone.CopeID = $scope.CopeType.selected.CopeID;
        $scope.stone.BockMachDirection = $scope.Direction.selected.Value;
        $scope.stone.Sold = Number($scope.StoneState.selected.ID)-1;

        var data =$scope.stone;
        data.Image = null;
        data.ImageSize = null;
        Extention.post('editStone', data).then(function (res) {
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $state.go('stone');
            } else {
                console.log(res);
                Extention.popError("مشکل در ویرایش سنگ ، لطفا دوباره تلاش کنید.");
            }
        });
    }

}]);

angular.module(appName).controller('StoneTypesCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', 'Extention', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention) {
    $scope.pagingParams = {};
	$scope.pagingController = {};
	$scope.stoneType ={};
    $scope.editMode = false;
	$scope.search = function () {
		$scope.pagingController.update();
	}

	$scope.insertStoneType= function() {
	    if ($scope.stoneType.Name && $scope.stoneType.Name.length > 2) {
	        Extention.post('insertOrEditStoneType', $scope.stoneType).then(function (res) {
	            if (res && res.Status == 'success') {
	                Extention.popSuccess(res.Data);
	                $scope.pagingController.update();
                    $scope.stoneType ={Name:"",PreFix:""};
                    $scope.editMode = false;
	            } else {
	                Extention.popError("مشکل در وارد کردن تگ ، لطفا دوباره تلاش کنید.");
	            }
	        });
	    }
	}
	$scope.editStoneType = function (stoneType) {
        $scope.stoneType = stoneType;$scope.editMode = true;
    }

    $scope.removeStoneType = function (uid) {
        Extention.post('deleteStoneType', { StoneTypeID: uid }).then(function (res) {
            if(res && res.Status=='success'){
                Extention.popSuccess("نوع سنگ با موفقیت حذف شد!");
                $scope.search();
            }else{
                Extention.popError("مشکل در حذف نوع سنگ ، لطفا دوباره امتحان کنید.");
            }
        });
    }
	activeElement('#SStoneType');
}]);

angular.module(appName).controller('UserCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', 'Extention', '$uibModal', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {
    $scope.pagingParams = {};
    $scope.pagingController = {};
    $scope.user ={};
    $scope.UserType ={selected:null};

    Extention.postAsync('getAllUserTypes', {}).then(function (msg) {
        $scope.UserTypes = msg.Data;
    });

    $scope.search = function () {
        $scope.pagingController.update();
    }

    $scope.insertNewUser= function() {

        if (!$scope.user.FullName || $scope.user.FullName.length < 3) {Extention.popError('نام کامل وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.Email || $scope.user.Email.length < 3) {Extention.popError('ایمیل وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.Username || $scope.user.Username.length < 3) {Extention.popError('نام کاربری وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.pass || $scope.user.pass.length < 3) {Extention.popError('پسورد وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.passRe || $scope.user.passRe.length < 3) {Extention.popError('تکرار پسورد وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.UserType.selected) {Extention.popError('نوع کاربر را انتخاب کنید');return}
        if ($scope.user.passRe != $scope.user.pass) {Extention.popError('پسورد با تکرار آن برابر نیست');return}
        $scope.user.PermissionID = $scope.UserType.selected.AdminPermissionID;
        Extention.post('savePerson', $scope.user).then(function (res) {
            console.log(res);
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $scope.user ={};
                $scope.pagingController.update();
            } else {
                Extention.popError(res.Message);
            }
        });
    }
    $scope.deleteUser= function(id) {
        Extention.post('deleteUser', {UserID : id}).then(function (res) {
            console.log(res);
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $scope.pagingController.update();
            } else {
                Extention.popError(res.Message);
            }
        });
    }

	activeElement('#SUser');
}]);
angular.module(appName).controller('MainCtrl', ['$scope', '$rootScope', '$routeParams', '$state', '$location', '$timeout', '$log', 'Extention', '$cookies', '$uibModal', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, $log, Extention,$cookies,$uibModal) {

    $scope.bgColorArray= ["bg-aqua-active","bg-purple-active","bg-red-active","bg-navy-active","bg-orange-active",
        "bg-blue-active","bg-green-active","bg-olive-active","bg-lime-active",
        "bg-fuchsia-active","bg-teal-active","bg-yellow-active","bg-maroon-active","bg-light-blue-active","bg-black-active",
        "bg-green","bg-navy","bg-teal","bg-olive","bg-lime","bg-orange","bg-fuchsia","bg-purple","bg-maroon","bg-red",
        "bg-yellow","bg-aqua","bg-blue","bg-light-blue","bg-black"];

    $scope.getRandomColorClass = function (id) {
        var i = id % $scope.bgColorArray.length;
        return $scope.bgColorArray[i];
    }

}]);