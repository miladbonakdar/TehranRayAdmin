
angular.module(appName).controller('AllStoneCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention) {
    $scope.pagingParams = {};
	$scope.pagingController = {};
	console.log($rootScope.user.AdminPermissionLevel);
	$scope.search = function () {
		$scope.pagingController.update();
	}

	$scope.removeStone = function (uid) {
	    Extention.post('deleteStone', { StoneID: uid }).then(function (res) {
			if(res && res.Status=='success'){
			    Extention.popSuccess("سنگ با موفقیت حذف شد!");
				$scope.pagingController.update();
			}else{
			    Extention.popError("مشکل در حذف سنگ ، لطفا دوباره امتحان کنید.");
			}
		});
	}
	activeElement('#SStone');
});