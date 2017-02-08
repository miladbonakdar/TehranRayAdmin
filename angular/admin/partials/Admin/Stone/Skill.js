
angular.module(appName).controller('SkillCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention) {
    $scope.pagingParams = {};
	$scope.pagingController = {};

	$scope.search = function () {
		$scope.pagingController.update();
	}

	$scope.insertSkill = function () {
	    if ($scope.skillName) {
	        Extention.post('insertSkill', { Skill: $scope.skillName }).then(function (res) {
	            if (res && res.Status == 'success') {
	                $scope.skillName = '';
	                Extention.popSuccess("مهارت اضافه شد");
	                $scope.pagingController.update();
	            } else {
	                Extention.popError("مشکل در وارد کردن مهارت ، لطفا دوباره تلاش کنید.");
	            }
	        });
	    }
	}

	$scope.removeSkill = function (uid) {
	    Extention.post('deleteSkill', { ID: uid }).then(function (res) {
			if(res && res.Status=='success'){
			    Extention.popSuccess("مهارت با موفقیت حذف شد!");
				$scope.pagingController.update();
			}else{
			    Extention.popError("مشکل در حذف مهارت ، لطفا دوباره امتحان کنید.");
			}
		});
	}
	activeElement('#SMeta', '#SSkill');
});