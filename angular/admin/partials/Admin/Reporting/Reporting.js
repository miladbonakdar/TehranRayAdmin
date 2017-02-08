
angular.module(appName).controller('ReportingCtrl', function ($scope, $rootScope, $routeParams, $state, $location, $timeout, $stateParams, $uibModal, Extention) {
    $scope.pagingParams = { Position: null };
    $scope.pagingController = {};
    $scope.users = [];
    $scope.Position= {selected : null};
    $scope.Forum= {selected : null};
    $scope.Forums= [];

    Extention.postAsync('getAllPositions', {}).then(function (msg) {
        $scope.allPositions = msg;
    });
    Extention.postAsync('getAllForumTypes', {}).then(function (msg) {
        $scope.Forums = msg.Data;
    });

    $scope.search = function () {
        $scope.pagingController.update();
    }

    $scope.changeForum = function () {
        $scope.pagingParams.ForumMainSubjectID = ($scope.Forum.selected) ? $scope.Forum.selected.ID : null;
        $scope.search();
    }
    $scope.changePosition = function () {
        $scope.pagingParams.OrganizationID = ($scope.Position.selected) ? $scope.Position.selected.ID : null;
        $scope.search();
    }

    $scope.getBGColor = function(id) {
        id = id % 15 + 1;
        switch (id) {
            case 1:
                return 'bg-red-active';
            case 2:
                return 'bg-yellow-active';
            case 3:
                return 'bg-aqua-active';
            case 4:
                return 'bg-blue-active';
            case 5:
                return 'bg-light-blue-active';
            case 6:
                return 'bg-green-active';
            case 7:
                return 'bg-navy-active';
            case 8:
                return 'bg-teal-active';
            case 9:
                return 'bg-olive-active';
            case 10:
                return 'bg-lime-active';
            case 11:
                return 'bg-orange-active';
            case 12:
                return 'bg-fuchsia-active';
            case 13:
                return 'bg-purple-active';
            case 14:
                return 'bg-maroon-active';
            case 15:
                return 'bg-black-active';
            default:
                return 'bg-red-active';
        }
    }

    activeElement('#SReporting','#SPersonReport');
});