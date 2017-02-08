
angular.module(appName).controller('AllUsersCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, Extention , $uibModal) {

    $scope.pagingParams = { userType: null };
	$scope.pagingController = {};
	$scope.user = {};
    $scope.dropDwonTitle = 'نمایش اعضا';
    $scope.genderDropDwonTitle = 'جنسیت افراد';
    $scope.Position = {};
	Extention.postAsync('getAllPositions', {}).then(function (msg) {
	    $scope.allPositions = msg;
	});

    $scope.openUserModal = function (user) {
         $uibModal.open({
            animation: true,
            templateUrl: 'UserDetail.html',
            controller: function ($scope, $uibModalInstance) {
                $scope.user = user;
                console.log($scope.user);
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
            },
            size: 'md'
        });
    }

	$scope.search = function () {
		$scope.pagingController.update();
	}
	$scope.changeUserState = function (uid , s) {
		Extention.post('changeUserAccepted',{State : s,UserID:uid}).then(function (res) {
			if(res && res.Status=='success'){
				Extention.popSuccess("وضعیت کاربر با موفقیت تغییر کرد!");
				$scope.pagingController.update();
			}else{
				Extention.popError("مشکل در تغییر وضعیت کاربر ، لطفا دوباره تلاش کنید.");
			}
		});
	}
	$scope.removeUser = function (uid) {
		Extention.post('deleteUser',{UserID:uid}).then(function (res) {
			if(res && res.Status=='success'){
				Extention.popSuccess("کاربر با موفقیت حذف شد!");
				$scope.pagingController.update();
			}else{
				Extention.popError("مشکل در حذف کاربر ، لطفا دوباره امتحان کنید.");
			}
		});
	}

	$scope.changeTypeFilter = function(type) {
	    $scope.pagingParams.userType = type;
	    switch (type) {
	        case null:
	            $scope.dropDwonTitle = 'همه ی اعضا';
	            break;
            case 1:
                $scope.dropDwonTitle = 'اعضای تایید شده';
                break;
            case 0:
                $scope.dropDwonTitle = 'اعضا در انتظار تایید';
                break;
            case -1:
                $scope.dropDwonTitle = 'اعضای تایید نشده';
                break;
	        default:
	            $scope.dropDwonTitle = 'نمایش اعضا';
                break;
	    }
	    $scope.search();
	}

	$scope.changeGenderFilter = function (type) {
	    $scope.pagingParams.genderType = type;
	    switch (type) {
	        case null:
	            $scope.genderDropDwonTitle = 'جنسیت افراد';
	            break;
            case 1:
                $scope.genderDropDwonTitle = 'اعضای زن';
                break;
            case 0:
                $scope.genderDropDwonTitle = 'اعضای مرد';
                break;
	        default:
	            $scope.genderDropDwonTitle = 'جنسیت افراد';
                break;
	    }
	    $scope.search();
	}

	$scope.changePosition = function () {
	    
	    $scope.pagingParams.OrganizationID = ($scope.Position.selected) ? $scope.Position.selected.ID : null;
	    $scope.search();
	}
	
	activeElement('#SUsers');
});