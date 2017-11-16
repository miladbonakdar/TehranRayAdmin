
angular.module(appName).controller('ReportingCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, $stateParams, $uibModal, Extention) {
    $scope.pagingParams = { Position: null };
    $scope.pagingController = {};
    $scope.Machine ={selected :null};
    $scope.StoneType ={selected :[]};
    $scope.StoneState ={selected :null};
    $scope.inputStoneID = {stoneID:null};

    $scope.getStoneByID = function () {
        if($scope.inputStoneID.stoneID){
            Extention.post('checkStoneExist', {StoneID:$scope.inputStoneID.stoneID}).then(function (msg) {
                if(msg.Data === 'OKEY'){
                    $state.go('stone_page' , {id:$scope.inputStoneID.stoneID})
                }else
                {
                    Extention.popError('سنگ مورد نظر پیدا نشد');
                }
            });
        }else
        {
            Extention.popError('لطفا آی دی سنگ را وارد کنید');
        }
    }

    $scope.machines = [{
        Name : "ماشین A",
        ID : "1"
    },{
        Name : "ماشین B",
        ID : "2"
    }];

    $scope.StoneStates = [{
        Name : "فروخته شده",
        ID : "2"
    },{
        Name : "موجود",
        ID : "1"
    }];

    if($rootScope.user.AdminPermissionLevel =='viewStones'){
        $scope.StoneState.selected = $scope.StoneStates[1];
    }

    Extention.postAsync('getAllStoneTypes', {}).then(function (msg) {
        $scope.StoneTypes = msg.Data;
    });
    $scope.search = function () {
        $scope.pagingParams.MachineID = ($scope.Machine.selected) ? $scope.Machine.selected.ID : null;
        $scope.pagingParams.StoneStateID = ($scope.StoneState.selected) ? $scope.StoneState.selected.ID : null;
        if($scope.Height){
            $scope.pagingParams.Height = Number($scope.Height)*10;
        }
        if($scope.Width){
            $scope.pagingParams.Width = Number($scope.Width)*10;
        }
        if($scope.Area){
            $scope.pagingParams.Area = $scope.Area*1000000;
        }
        if($scope.EndDate){
            var EndDate = new Date($scope.toFullEnd.unix);
            $scope.pagingParams.EndDate  = EndDate;
        }
        if($scope.StartDate){
            var StartDate = new Date($scope.toFullStart.unix);
            $scope.pagingParams.StartDate  = StartDate;
        }

        if($scope.StoneType.selected.length > 0){
            $scope.pagingParams.StoneTypeID = "("+$scope.StoneType.selected[0].StoneTypeID;
            for(var i = 1 ; i < $scope.StoneType.selected.length ; i++){
                $scope.pagingParams.StoneTypeID += ","+$scope.StoneType.selected[i].StoneTypeID;
            }
            $scope.pagingParams.StoneTypeID += ")";
        }else
            $scope.pagingParams.StoneTypeID = null;
        $scope.pagingController.update();
    }

    $scope.getStoneImage = function (id , image) {
        $timeout(function () {
            document.getElementById("stoneImage"+id).setAttribute("src","data:image/png;base64,"+image);
        })
    }

    activeElement('#SReporting','#SPersonReport');
});