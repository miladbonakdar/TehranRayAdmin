
angular.module(appName).controller('CopeCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {
    $scope.pagingParams = {};
    $scope.pagingController = {};
    $scope.cope ={};
    $scope.StoneType ={selected:null};
    $scope.StoneTypes =[];

    Extention.postAsync('getAllStoneTypes', {}).then(function (msg) {
        $scope.StoneTypes = msg.Data;
        console.log(msg);
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
                Extention.popError(res.Message);
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