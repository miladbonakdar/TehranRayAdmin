angular.module('myApp').controller('MainCtrl', function($scope, $templateCache, $state, $rootScope, $routeParams, $uibModal, Extention ,$cookies) {

    $scope.notShowAdminForm = true;
    $scope.userNameRegex = (/[أ-ي]/);
    $scope.signIn ={Username:null , Password:null};
    $scope.logInUser = function () {
        getPage('Admin');
    }

    $scope.signOutUser = function () {
        Extention.post('logout', {}).then(function (msg) {
            consolee.log(msg);
            $rootScope.userSession = {};
        });
    }
    $scope.logInGuest = function () {
        $scope.signIn ={Username:null , Password:null};
        $scope.signIn.Username = "guestUser@gmail.com";
        $scope.signIn.Password = "tehranrey123456";
        $scope.signInFunc();
    }
    $scope.signInFunc = function () {
        if ($scope.signInForm.$valid) {
            Extention.post('signInUser', $scope.signIn).then(function (msg) {
                //console.log(msg);
                if (msg.Status == 'success') {
                    if (msg.IsAdmin) {
                        getPage('Admin');
                    }
                } else {
                    console.log(msg);
                    Extention.popError('اطلاعات شما نا معتبر است.');
                }
            });
        }
    }

    $scope.mainOptions = {
        anchors: ['firstPage'],
        menu: '#menu'
    };
});