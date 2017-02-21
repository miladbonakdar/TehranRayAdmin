/**
 * Created by -MR- on 29/05/2016.
 */

angular.module('am-charts', []).directive('serialChart', function () {
    return {
        restrict: 'ECA',
        scope:{
            chartData : '=',
            chartHeight : '@',
            chartGraphs : '=',
            enableScrollBar : '=',
            enableExport : '=',
            chartId : '@',
            delay : '@'
        },
        template: '<div id="{{chartId}}" style="width: 100%; height: {{chartHeight}}px;"></div>',
        controller : function ($scope,$timeout) {

            $scope.$watch('chartData',function (oval , nval) {

                if(!$scope.chartData)
                    return;

                $timeout(function () {

                    if(angular.isDefined($scope.chart)){
                        $scope.chart.dataProvider = $scope.chartData;
                        $scope.chart.validateData();

                        return;
                    }

                    var opt= {

                        type: "serial",
                        dataProvider: $scope.chartData,

                        categoryField: "date",

                        addClassNames: true,
                        categoryAxis: {
                            parseDates: true,
                            minPeriod: "DD",
                            gridAlpha: 0.1,
                            minorGridAlpha: 0.1,
                            axisAlpha: 0,
                            minorGridEnabled: true,
                            inside: false,
                            labelFunction :function formatLabel(value, valueString, axis){
                                var date = moment(valueString);
                                var d=  date.format('jYY/jMM/jDD');
                                return persianJs(d).englishNumber().toString();
                            }
                        },
                        valueAxes: [{
                            labelFunction :function formatLabel(value, valueString, axis){
                                return persianJs(valueString).englishNumber().toString();
                            },
                            tickLength: 0,
                            axisAlpha: 0,
                            showFirstLabel: false,
                            showLastLabel: false,
                            inside: false,

                            guides: [{
                            }]

                        }],

                        graphs: $scope.chartGraphs,

                        // GRAPH
                        chartCursor: {
                            limitToGraph:"g1",
                            cursorColor:"#aaa",
                            categoryBalloonEnabled : false
                        },
                        mouseWheelZoomEnabled: true,
                        "export": {
                            "enabled": $scope.enableExport ? true : false
                        }
                    };

                    if ($scope.enableScrollBar){
                        opt.chartScrollbar= {};
                    }

                    $scope.chart = AmCharts.makeChart($scope.chartId, opt);
                    $scope.chartMaked = true;

                });
            });

        }
    };
});


angular.module('am-charts').directive('stackBarChart', function () {
    return {
        restrict: 'ECA',
        scope:{
            chartData : '=',
            chartHeight : '@',
            chartGraphs : '=',
            enableScrollBar : '=',
            chartId : '@',
            delay : '@'
        },
        template: '<div id="{{chartId}}" style="width: 100%; height: {{chartHeight}}px;"></div>',
        controller : function ($scope,$timeout) {

            $scope.$watch('chartData',function (oval , nval) {

                if(!$scope.chartData)
                    return;

                $timeout(function () {

                    if(angular.isDefined($scope.chart)){
                        $scope.chart.dataProvider = $scope.chartData;
                        $scope.chart.validateData();
                        return;
                    }

                    $scope.chart = AmCharts.makeChart($scope.chartId,
                        {
                        "type": "serial",
                        "theme": "dark",
                        "rotate": true,
                        "marginBottom": 20,
                        "colors": [
                            "#FF6600",
                            "#B0DE09",
                            "#D6F661",
                            "#FFA364",
                        ],
                        "dataProvider": $scope.chartData,
                        "startDuration": 1,
                        "graphs": $scope.chartGraphs,
                        "categoryField": "OrganizationName",
                        "categoryAxis": {
                            "gridPosition": "start" ,
                            "gridAlpha": 0.2 ,
                            "axisAlpha": 0,
                            "labelFunction": function(value) {
                                if(value)
                                    return persianJs(value).englishNumber().toString();
                                return '';
                            }
                        },
                        "valueAxes": [{
                            "gridAlpha": 0,
                            "position": "top" ,
                            "ignoreAxisWidth": true,
                            "labelFunction": function(value) {
                                return persianJs(Math.abs(value).toString()).englishNumber().toString() ;
                            }
                        }],
                        "balloon": {
                            "fixedPosition": true
                        },
                        "chartCursor": {
                            "valueBalloonsEnabled": false,
                            "cursorAlpha": 0.1,
                            "fullWidth": true
                        }
                    });
                    $scope.chartMaked = true;

                });
            });

        },
        // link : function ($scope) {
        //
        // }

    };
});