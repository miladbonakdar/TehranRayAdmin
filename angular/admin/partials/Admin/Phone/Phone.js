
angular.module(appName).controller('PhoneCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {
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
});