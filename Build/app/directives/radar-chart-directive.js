/**
 * Created by -MR- on 29/05/2016.
 */

angular.module('am-charts').directive('radarChart', function () {
    return {
        restrict: 'ECA',
        scope:{
            chartData : '=',
            chartHeight : '@',
            chartGraphs : '=',
            chartId : '@',
            delay : '@'
        },
        template: '<div id="{{chartId}}" style="width: 100%; height: {{chartHeight}}px;"></div>',
        controller : function ($scope,$timeout) {

            $scope.$watch('chartData',function (oval , nval) {

                if(!$scope.chartData)
                    return;

                $timeout(function () {

                    var chart = AmCharts.makeChart($scope.chartId, {
                        type: "radar",
                        dataProvider : $scope.chartData ,

                        categoryField: "Title",
                        startDuration: 1,
                        colors : ["#00BBCC","#dd4b39"],

                        valueAxes: [{
                            labelFunction :function formatLabel(value, valueString){
                                return persianJs(valueString).englishNumber().toString();
                            },
                            axisAlpha: 0.15,
                            minimum: 0,
                            dashLength: 3,
                            axisTitleOffset: 20,
                        }],

                        graphs: $scope.chartGraphs,
                        export : {
                            enabled : true
                        }
                    });

                });
            });

        },
        // link : function ($scope) {
        //
        // }

    };
});