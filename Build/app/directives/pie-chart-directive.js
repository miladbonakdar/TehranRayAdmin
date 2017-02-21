/**
 * Created by -MR- on 29/05/2016.
 */

angular.module('am-charts').directive('pieChart', function () {
    return {
        restrict: 'ECA',
        scope:{
            chartData : '=',
            chartHeight : '@',
            chartOptions : '=',
            chartId : '@',
            delay : '@'
        },
        template: '<div id="{{chartId}}" style="width: 100%; height: {{chartHeight}}px;direction: ltr"></div>',
        controller : function ($scope,$timeout) {

            $scope.$watch('chartData',function (oval , nval) {

                if(!$scope.chartData || !$scope.chartOptions)
                    return;

                $timeout(function () {
                    $scope.chartOptions.dataProvider = $scope.chartData;
                    AmCharts.makeChart($scope.chartId,$scope.chartOptions);
                });
            });

        },
        // link : function ($scope) {
        //
        // }

    };
});