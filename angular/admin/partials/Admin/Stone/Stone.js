
angular.module(appName).controller('StoneCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout,$stateParams, Extention) {
	$scope.stone = null;

    ($scope.getStoneByID = function () {
        Extention.post("getStone", { StoneID: $stateParams.id }).then(function (res) {
            if (res.Status == 'success') {
                $scope.stone = res.Data;
                document.getElementById("stoneImage").setAttribute("src","data:image/png;base64,"+$scope.stone.Image);
                console.log(res);
            } else {
                $scope.stone = null;
                console.log(res);
            }
        });
    })();
});