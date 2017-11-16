var appName = 'adminApp';
var uploadURL = '../api/upload/';
var serviceBaseURL = '../api/admin/';
var debugMode = false;

var app = angular.module(appName, ['ngRoute', 'treasure-overlay-spinner', 'ui.router', 'angular-confirm',
    'oc.lazyLoad', 'ngAnimate', 'toaster', 'ui.bootstrap', 'ui.router.title', 'ui.select', 'ngPersian',
    'ngFileUpload', 'anim-in-out', 'am-charts', 'ADM-dateTimePicker', 'ngSanitize', 'ngCookies',
    'textAngular', 'nvd3', 'ngCsv']);

app.config([
    '$provide', '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', 'ADMdtpProvider',
    function ($provide, $stateProvider, $urlRouterProvider, $ocLazyLoadProvider, ADMdtp) {
        // Add nested user links to the "foo" menu.
        $ocLazyLoadProvider.config({
            debug: debugMode,
            events: true
        });
        ADMdtp.setOptions({
            calType: 'jalali',
            format: 'YYYY/MM/DD hh:mm'
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
                }
            }).state("addStone", {
            url: "/AddStone",
            templateUrl: "angular.partial.AddStone.html",
            controller: 'AddStoneCtrl',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                    return $ocLazyLoad.load([]);
                }],
                $title: function () {
                    return 'افزودن سنگ جدید';
                }
            }
        });
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


        $provide.decorator('taOptions', ['taRegisterTool', '$uibModal', '$delegate',
            function (taRegisterTool, $uibModal, taOptions) {

                taOptions.toolbar = [
                    ['h1', 'h4', 'h6', 'p', 'pre', 'quote'],
                    ['html', 'customInsertImage', 'insertLink', 'wordcount', 'charcount'],
                    ['bold', 'italics', 'underline', 'strikeThrough', 'ul', 'ol', 'redo', 'undo', 'clear'],
                    ['justifyLeft', 'justifyCenter', 'justifyRight'],
                    ['dirLtr', 'dirRtl']
                ];

                taRegisterTool('dirLtr', {
                    iconclass: "fa fa-indent",
                    action: function () {
                        return this.$editor().wrapSelection("formatBlock", '<P style="direction:ltr">');
                    }
                });
                taRegisterTool('dirRtl', {
                    iconclass: "fa fa-dedent",
                    action: function () {
                        return this.$editor().wrapSelection("formatBlock", '<P style="direction:rtl">');
                    }
                });

                // Create our own insertImage button
                taRegisterTool('customInsertImage', {
                    iconclass: "fa fa-picture-o",
                    action: function ($deferred) {
                        var textAngular = this;
                        var savedSelection = rangy.saveSelection();
                        var modalInstance = $uibModal.open({
                            // Put a link to your template here or whatever
                            templateUrl: '../js/text-angular/CustomSelectImageModal.tmpl.html',
                            size: 'md',
                            controller: ['$uibModalInstance', '$scope', 'Upload', '$state',
                                function ($uibModalInstance, $scope, Upload, state) {
                                    $scope.activeTab = 0;
                                    $scope.img = {
                                        url: ''
                                    };
                                    $scope.submit = function () {
                                        $uibModalInstance.close($scope.img.url);
                                    };
                                    $scope.cancel = function () {
                                        $uibModalInstance.close();
                                    };

                                    $scope.getTab = function (tabId) {
                                        $scope.activeTab = tabId;
                                    }

                                    $scope.addImageLink = function (link) {
                                        $uibModalInstance.close(link);
                                    }

                                    $scope.filesChanged = function (files, file) {
                                        var url = uploadURL;
                                        url += 'upload_inline_attachment.php';
                                        var data = {file: file};
                                        data.Type = state.current.name;

                                        file.uploader = Upload.upload({
                                            url: url,
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

                        modalInstance.result.then(function (imgUrl) {
                            if (!imgUrl)
                                return;

                            rangy.restoreSelection(savedSelection);

                            var embed = '<a href="' + imgUrl + '" target="_blank">' +
                                '<img class="img-responsive link attachment-inline-img" '
                                + ' src="' + imgUrl + '"  /></a>';
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

var persian = {0: '۰', 1: '۱', 2: '۲', 3: '۳', 4: '۴', 5: '۵', 6: '۶', 7: '۷', 8: '۸', 9: '۹'};
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

var activeElement = function (parent, name) {
    if (name) {
        var elem = $(name);
        elem.addClass('active').siblings().removeClass('active');
    }
    var elemP = $(parent);
    elemP.addClass('active').siblings().removeClass('active');
}

var cmsVars = {};
var hideCMS = function (hide) {
    if (hide) {
        cmsVars.v1 = $('.content-wrapper').css('marginRight');
        cmsVars.v2 = $('.main-footer').css('marginRight');
        cmsVars.v3 = $('.main-header').css('display');
        cmsVars.v4 = $('.main-sidebar').css('display');
        $('.content-wrapper').css('margin-right', '0');
        $('.main-footer').css('margin-right', '0');
        $('.main-header').css('display', 'none');
        $('.main-sidebar').css('display', 'none');
    }
    else {
        $('.content-wrapper').css('margin-right', cmsVars.v1);
        $('.main-footer').css('margin-right', cmsVars.v2);
        $('.main-header').css('display', cmsVars.v3);
        $('.main-sidebar').css('display', cmsVars.v4);
    }
}


app.factory("Extention", ['$http', '$timeout', '$rootScope', '$state', '$stateParams', 'toaster', '$uibModal',
    function ($http, $timeout, $rootScope, $state, $stateParams, toaster, $uibModal) { // This service connects to our REST API

        $rootScope.logout = function () {
            obj.post('logout').then(function (res) {
                if (res && res.Status == 'success') {
                    window.location = "../";
                } else {
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

                if (obj.debugMode) {
                    console.log(results.data);

                    if (results.status != 200)
                        obj.popModal(results);
                }
                obj.setBusy(false);

                if (results.data.AuthState && results.data.AuthState == 'UN_AUTH') {
                    console.log('State : UN_AUTHORIZED_USER');
                    window.location = '../';
                }

                return results.data;
            }, function (err) {

                if (obj.debugMode) {
                    console.log(err.data);
                    obj.popModal(err.data);
                }

                if (err.data.AuthState && err.data.AuthState == 'UN_AUTH') {
                    console.log('State : UN_AUTHORIZED_USER');
                    window.location = '../';
                }

                obj.setBusy(false);
                return err;
            });
        };

        obj.postAsync = function (q, object) {
            return $http.post(obj.serviceBase + q, object).then(function (results) {

                if (obj.debugMode && results.status != 200) {
                    obj.popModal(results);
                }
                return results.data;
            }, function (err) {
                if (obj.debugMode)
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
            return {authenticated: $rootScope.authenticated, isAdmin: $rootScope.isAdmin};
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
                scrollTo(0, stopY);
                return;
            }
            var speed = Math.round(distance / 10);
            if (speed >= 5) speed = 5;
            var step = Math.round(distance / 25);
            var leapY = stopY > startY ? startY + step : startY - step;
            var timer = 0;
            if (stopY > startY) {
                for (var i = startY; i < stopY; i += step) {
                    setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
                    leapY += step;
                    if (leapY > stopY) leapY = stopY;
                    timer++;
                }
                return;
            }
            for (var i = startY; i > stopY; i -= step) {
                setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
                leapY -= step;
                if (leapY < stopY) leapY = stopY;
                timer++;
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
                scrollTo(0, stopY);
                return;
            }
            var speed = Math.round(distance / 10);
            if (speed >= 5) speed = 5;
            var step = Math.round(distance / 25);
            var leapY = stopY > startY ? startY + step : startY - step;
            var timer = 0;
            if (stopY > startY) {
                for (var i = startY; i < stopY; i += step) {
                    setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
                    leapY += step;
                    if (leapY > stopY) leapY = stopY;
                    timer++;
                }
                return;
            }
            for (var i = startY; i > stopY; i -= step) {
                setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
                leapY -= step;
                if (leapY < stopY) leapY = stopY;
                timer++;
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
                }
                return y;
            }

        };

        return obj;
    }]);

app.factory("DateConverter", ['ADMdtpConvertor',
    function (ADMdtpConvertor) {
        var obj = {};
        obj.convertDateToISO = function (inputFullDate, isToDate) {
            if (inputFullDate.calType == "jalali") {
                var t = ADMdtpConvertor.toGregorian(inputFullDate.year, inputFullDate.month, inputFullDate.day);
                if (isToDate && inputFullDate.hour ==0) {
                    var IsoDate = t.year + '-' + obj.format(t.month) + '-' + obj.format(Number(t.day) - 1) + ' ' +
                        obj.format(Number(inputFullDate.hour) + 23) + ':' + obj.format(Number(inputFullDate.minute) + 59);
                    console.log(IsoDate);
                    return IsoDate;
                } else {
                    var IsoDate = t.year + '-' + obj.format(t.month) + '-' + obj.format(Number(t.day) - 1) + ' ' +
                        obj.format(inputFullDate.hour) + ':' + obj.format(inputFullDate.minute);
                    console.log(IsoDate);
                    return IsoDate;
                }
            } else {
                return inputFullDate.year + '-' + obj.format(inputFullDate.month) + '-' + obj.format(Number(inputFullDate.day) - 1) + ' ' +
                    obj.format(inputFullDate.hour) + ':' + obj.format(inputFullDate.minute);
            }
        }
        obj.format = function (input) {
            return ((input < 10) ? '0' + input : input);
        }
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

app.run(function ($rootScope, $templateCache, $state, $location, Extention, OnlineSocket) {

    $rootScope.spinner = {};

    $rootScope.$on("$stateChangeSuccess", function () {
        Extention.setBusy(false);
    });
    $rootScope.$on('$stateChangeError',
        function (event, toState, toParams, fromState, fromParams, error) {
            Extention.setBusy(false);
        });
    $rootScope.$on('$stateNotFound',
        function (event, unfoundState, fromState, fromParams) {
            Extention.setBusy(false);
        })

    $rootScope.$on("$stateChangeStart", function (event, next, current) {
        Extention.setBusy(true);
        $rootScope.globalSearchActive = false;
    });

});

app.filter('fileSizeFilter', function () {
    return function (bytes, precision) {
        if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
        if (typeof precision === 'undefined') precision = 1;
        var units = ['بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت', 'ترابایت', 'پتابایت'],
            number = Math.floor(Math.log(bytes) / Math.log(1024));
        return persianJs((bytes / Math.pow(1024, Math.floor(number))).toFixed(precision)).englishNumber().toString() +
            ' ' + units[number];
    }
});
app.filter('fileSizeFilterEnglish', function () {
    return function (bytes, precision) {
        if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
        if (typeof precision === 'undefined') precision = 1;
        var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PT'],
            number = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) + ' ' + units[number];
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
        return number.toString() + " سانتی متر";
    }
});

app.filter('Volume', function () {
    return function (input) {
        var number = Number(input);
        number /= 1000000;
        number = number.toFixed(1);
        return number.toString() + " متر مربع";
    }
});

app.filter('Volume2', function () {
    return function (input) {
        var number = Number(input);
        number /= 1000000;
        number = number.toFixed(1);
        return number.toString();
    }
});

app.filter('Weight', function () {
    return function (input) {
        if (input.length > 3) {
            var number = Number(input);
            number /= 1000;
            return number.toString() + " تن";
        } else
            return input + " تن";
    }
});
app.filter('Toman', function () {
    return function (input) {
        return input + "  هزار تومان";
    }
});
app.filter('Time', function () {
    return function (input) {
        var myDate = new Date(input);

        return myDate.getHours() + ":" + myDate.getMinutes();
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
