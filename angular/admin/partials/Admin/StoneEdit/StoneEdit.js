
angular.module(appName).controller('StoneEditCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout,$stateParams, Extention) {
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

});