
angular.module(appName).controller('TagCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention) {
    $scope.pagingParams = {};
	$scope.pagingController = {};

	$scope.search = function () {
		$scope.pagingController.update();
	}

	$scope.insertTag= function() {
	    if ($scope.tagName) {
	        Extention.post('insertTag', { Text: $scope.tagName }).then(function (res) {
	            if (res && res.Status == 'success') {
	                Extention.popSuccess("تگ اضافه شد");
	                $scope.pagingController.update();
	            } else {
	                Extention.popError("مشکل در وارد کردن تگ ، لطفا دوباره تلاش کنید.");
	            }
	        });
	    }
	}

	$scope.removeTag = function (uid) {
	    Extention.post('deleteTag', { ID: uid }).then(function (res) {
			if(res && res.Status=='success'){
			    Extention.popSuccess("تگ با موفقیت حذف شد!");
				$scope.pagingController.update();
			}else{
			    Extention.popError("مشکل در حذف تگ ، لطفا دوباره امتحان کنید.");
			}
		});
	}
	activeElement('#SMeta', '#STag');
});