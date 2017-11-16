
angular.module(appName).controller('CopeCtrl', function ($scope,DateConverter, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {
    $scope.pagingParams = {};
    $scope.pagingController = {};
    $scope.cope ={};
    $scope.StoneType ={selected:null};
    $scope.FilterStoneType ={selected:null};
    $scope.copeState ={selected:null};
    $scope.StoneTypes =[];
    $scope.copeDate = {to:null,toFull:null};
    $scope.copeStates = [
        {ID:1 , Name:'همه'},
        {ID:2 , Name:'بریده نشده'},
        {ID:3 , Name:'بریده شده'}
    ];

    $scope.updateData = function () {
        // console.log($scope.copeFilter.to);
        // if (angular.isDefined($scope.copeFilter.toFull.unix) && $scope.copeFilter.toFull.unix != "")
        //     $scope.pagingParams.to = new Date($scope.copeFilter.toFull.unix);
        // else
        //     $scope.pagingParams.to = null;
        //
        // if (angular.isDefined($scope.copeFilter.fromFull.unix) && $scope.copeFilter.fromFull.unix != "")
        //     $scope.pagingParams.from = new Date($scope.copeFilter.fromFull.unix);
        // else
        //     $scope.pagingParams.from = null;

        if ($scope.FilterStoneType.selected) {
            $scope.pagingParams.StoneTypeID = $scope.FilterStoneType.selected.StoneTypeID;
        }else{
            $scope.pagingParams.StoneTypeID = null;
        }
        if ($scope.copeState.selected) {
            $scope.pagingParams.CopeStateID = $scope.copeState.selected.ID;
        }else{
            $scope.pagingParams.CopeStateID = null;
        }
        console.log($scope.pagingParams);
        $scope.search();
    }

    Extention.postAsync('getAllStoneTypes', {}).then(function (msg) {
        $scope.StoneTypes = msg.Data;
        console.log(msg);
    });

    $scope.search = function () {
        $scope.pagingController.update();
    }
    $scope.editCope =function (cope) {
        $scope.cope = cope;
        $scope.copeDate.to = new Date(Date.parse(cope.CreationDate.replace('-','/','g'))).getTime();
        for (var i = 0 ; i<$scope.StoneTypes.length ; i++){
            if($scope.StoneTypes[i].StoneTypeID == cope.StoneTypeID){
                $scope.StoneType.selected =$scope.StoneTypes[i];
                break;
            }
        }
    }
    $scope.cancleEdite =function () {
        $scope.cope = {};
        $scope.copeDate.toFull.unix = null;
        $scope.StoneType.selected = null;
    }

    $scope.insertCope= function() {
        if (!$scope.cope.CopeName || $scope.cope.CopeName.length < 3) {Extention.popError('کد سنگ خام را وارد کنید');return}
        if (!$scope.cope.Weight) {Extention.popError('وزن سنگ را وارد کنید');return}
        if (!$scope.cope.UnitPrice) {Extention.popError('قیمت واحد را وارد کنید');return}
        if (!$scope.StoneType.selected) {Extention.popError('نوع سنگ را انتخاب کنید');return}
        if (!$scope.copeDate.toFull) {Extention.popError('تاریخ را انتخاب کنید');return}
        $scope.cope.StoneTypeID = $scope.StoneType.selected.StoneTypeID;
        $scope.cope.CreationDate = new Date($scope.copeDate.toFull.unix);
        Extention.post('insertOrEditCope', $scope.cope).then(function (res) {
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $scope.cope ={};
                $scope.StoneType.selected = null;
                $scope.copeDate.to = null;
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