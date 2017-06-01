
angular.module(appName).controller('AddStoneCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout,$stateParams, Extention) {
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
    Extention.postAsync('getAllStoneTypes', {}).then(function (msg) {
        $scope.StoneTypes = msg.Data;
    });

    Extention.postAsync('getAllCopeTypes', {}).then(function (msg) {
        $scope.copeTypes = msg.Data;
    });

    $scope.addStone = function () {
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
        Extention.post('addStone', data).then(function (res) {
            if (res && res.Status == 'success') {
                Extention.popSuccess(res.Data);
                $state.go('stone');
            } else {
                console.log(res);
                Extention.popError("مشکل در ویرایش سنگ ، لطفا دوباره تلاش کنید.");
            }
        });
    }

    activeElement('#SAddStone');
});