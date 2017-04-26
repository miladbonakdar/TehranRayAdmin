
angular.module(appName).controller('StoneTypesCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention) {
    $scope.pagingParams = {};
	$scope.pagingController = {};
	$scope.stoneType ={};
    $scope.editMode = false;
	$scope.search = function () {
		$scope.pagingController.update();
	}

	$scope.insertStoneType= function() {
	    if ($scope.stoneType.Name && $scope.stoneType.Name.length > 2) {
	        Extention.post('insertOrEditStoneType', $scope.stoneType).then(function (res) {
	            if (res && res.Status == 'success') {
	                Extention.popSuccess(res.Data);
	                $scope.pagingController.update();
                    $scope.stoneType ={Name:"",PreFix:""};
                    $scope.editMode = false;
	            } else {
	                Extention.popError("مشکل در وارد کردن تگ ، لطفا دوباره تلاش کنید.");
	            }
	        });
	    }
	}
	$scope.editStoneType = function (stoneType) {
        $scope.stoneType = stoneType;$scope.editMode = true;
    }

    $scope.removeStoneType = function (uid) {
        Extention.post('deleteStoneType', { StoneTypeID: uid }).then(function (res) {
            if(res && res.Status=='success'){
                Extention.popSuccess("نوع سنگ با موفقیت حذف شد!");
                $scope.search();
            }else{
                Extention.popError("مشکل در حذف نوع سنگ ، لطفا دوباره امتحان کنید.");
            }
        });
    }
	activeElement('#SStoneType');
});