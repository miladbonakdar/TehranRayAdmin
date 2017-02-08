angular.module('myApp').controller('MainCtrl', function($scope, $templateCache, $state, $rootScope, $routeParams, $uibModal, Extention ,$cookies) {


    $scope.userNameRegex = (/[أ-ي]/);

    $scope.logInUser = function () {
        getPage('Admin');
    }

    $scope.signOutUser = function () {
        Extention.post('logout', {}).then(function (msg) {
            $rootScope.userSession = {};
        });
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