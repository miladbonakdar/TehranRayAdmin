angular.module(appName).controller('DashboardCtrl', function ($scope, ADMdtpConvertor, $rootScope, Extention, $state, $timeout) {
    $scope.getStone = function () {
        Extention.post('getStone',{}).then(function (res) {
        console.log(res);
        });
    }
    activeElement('#SDashboard');
});