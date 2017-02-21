
angular.module(appName).controller('UserCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {
    $scope.pagingParams = {};
    $scope.pagingController = {};
    $scope.user ={};
    $scope.search = function () {
        $scope.pagingController.update();
    }

    $scope.insertNewUser= function() {

        if (!$scope.user.FullName || $scope.user.FullName.length < 3) {Extention.popError('نام کامل وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.Email || $scope.user.Email.length < 3) {Extention.popError('ایمیل وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.Username || $scope.user.Username.length < 3) {Extention.popError('نام کاربری وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.pass || $scope.user.pass.length < 3) {Extention.popError('پسورد وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if (!$scope.user.passRe || $scope.user.passRe.length < 3) {Extention.popError('تکرار پسورد وارد نشده یا تعداد کاراکتر ها کم می باشد');return}
        if ($scope.user.passRe != $scope.user.pass) {Extention.popError('پسورد با تکرار آن برابر نیست');return}
        Extention.post('savePerson', $scope.user).then(function (res) {
            console.log(res);
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
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
});