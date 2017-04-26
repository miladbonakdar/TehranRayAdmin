
angular.module(appName).controller('CopeCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {
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
});