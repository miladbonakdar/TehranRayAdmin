angular.module(appName).controller('DashboardCtrl', function ($scope, ADMdtpConvertor, $rootScope, Extention,DateConverter, $state, $timeout) {

    $scope.data = {};
    $scope.count = {};
    $scope.searchTypes = [{Name: 'تمام سنگ ها', ID: 1}, {Name: 'سنگ های موجود', ID: 2}, {
        Name: 'سنگ های فروخته شده',
        ID: 3
    }];
    $scope.options = {
        chart: {
            type: 'pieChart',
            height: 500,
            x: function (d) {
                return d.Name;
            },
            y: function (d) {
                return d.Count;
            },
            showLabels: true,
            duration: 500,
            labelThreshold: 0.03,
            labelSunbeamLayout: true,
            legend: {
                margin: {
                    top: 20,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            }
        }
    };

    $scope.incrementChartOptions = [{
        id: "g1",
        type: "smoothedLine",
        lineColor: "#00BBCC",
        valueField: "ISoldCount",
        fillColors: "#00BBCC",
        fillAlphas: 0.2,
        bullet: "round",
        bulletColor: "#FFFFFF",
        bulletBorderAlpha: 1,
        bulletBorderThickness: 2,
        bulletSize: 7,
        useLineColorForBulletBorder: true,
        lineThickness: 2,
        balloon: {
            drop: false
        },
        balloonFunction: function (graphDataItem, graph) {
            var value = graphDataItem.values.value;
            var date = moment(graphDataItem.category);
            var d = date.format('jYYYY/jMM/jDD');

            return "<b style=\"font-size: 13px\">" +
                persianJs(value + " فروش <br>" + '<span class="text-muted">' +
                    d + '</span>').englishNumber().toString() + "</b>";
        }
    },
        {
            id: "g2",
            type: "smoothedLine",
            lineColor: "#e74c3c",
            valueField: "IStoneCount",
            fillColors: "#e74c3c",
            fillAlphas: 0.2,
            bullet: "round",
            bulletColor: "#FFFFFF",
            bulletBorderAlpha: 1,
            bulletBorderThickness: 2,
            bulletSize: 7,
            useLineColorForBulletBorder: true,
            lineThickness: 2,
            balloon: {
                drop: false
            },
            balloonFunction: function (graphDataItem, graph) {
                var value = graphDataItem.values.value;
                var date = moment(graphDataItem.category);
                var d = date.format('jYYYY/jMM/jDD');

                return "<b style=\"font-size: 13px\">" +
                    persianJs(value + " تولید <br>" + '<span class="text-muted">' +
                        d + '</span>').englishNumber().toString() + "</b>";
            }
        }
    ];

    $scope.dailyChartOptions = [{
        id: "g1",
        type: "smoothedLine",
        lineColor: "#00BBCC",
        valueField: "SoldCount",
        fillColors: "#00BBCC",
        bullet: "round",
        bulletColor: "#FFFFFF",
        bulletBorderAlpha: 1,
        bulletBorderThickness: 2,
        bulletSize: 7,
        useLineColorForBulletBorder: true,
        lineThickness: 2,
        classNameField: "classNameQ",
        balloon: {
            drop: false
        },
        balloonFunction: function (graphDataItem, graph) {
            var value = graphDataItem.values.value;
            var date = moment(graphDataItem.category);
            var d = date.format('jYYYY/jMM/jDD');

            return "<b style=\"font-size: 13px\">" +
                persianJs(value + " فروش <br>" + '<span class="text-muted">' +
                    d + '</span>').englishNumber().toString() + "</b>";
        }
    },
        {
            id: "g2",
            type: "smoothedLine",
            lineColor: "#e74c3c",
            valueField: "StoneCount",
            fillColors: "#e74c3c",
            bullet: "round",
            bulletColor: "#FFFFFF",
            bulletBorderAlpha: 1,
            bulletBorderThickness: 2,
            bulletSize: 7,
            useLineColorForBulletBorder: true,
            lineThickness: 2,
            classNameField: "classNameA",
            balloon: {
                drop: false
            },
            balloonFunction: function (graphDataItem, graph) {
                var value = graphDataItem.values.value;
                var date = moment(graphDataItem.category);
                var d = date.format('jYYYY/jMM/jDD');

                return "<b style=\"font-size: 13px\">" +
                    persianJs(value + " تولید <br>" + '<span class="text-muted">' +
                        d + '</span>').englishNumber().toString() + "</b>";
            }
        }
    ];

    $scope.stackChartOptions = [{
        "fillAlphas": 0.8,
        "lineAlpha": 0.2,
        "type": "column",
        "valueField": "StoneTotal",
        "labelText": "[[value]]",
        "clustered": false,
        "labelFunction": function (item) {
            return persianJs(item.values.value.toString()).englishNumber().toString();
        },
        "balloonFunction": function (item) {
            return "<b style=\"font-size: 15px\">" +
                persianJs(item.category).englishNumber().toString()
                + "</b><br><span class='pull-right' style='font-size=15px'> &nbsp;" +
                persianJs(item.values.value.toString()).englishNumber().toString()
                + "</span><span style='font-size=15px'>" + 'اسلب موجود' + "</span>";
        }
    }, {
        "fillAlphas": 0.8,
        "lineAlpha": 0.2,
        "type": "column",
        "valueField": "SellTotal",
        "labelText": "[[value]]",
        "clustered": false,
        "labelFunction": function (item) {
            return persianJs((-item.values.value).toString()).englishNumber().toString();
        },
        "balloonFunction": function (item) {
            return "<b style=\"font-size: 15px;\">" +
                persianJs(item.category).englishNumber().toString()
                + "</b><br><span class='pull-right' style='font-size=15px'> &nbsp;" +
                persianJs((-item.values.value).toString()).englishNumber().toString()
                + "</span><span style='font-size=15px'>" + 'اسلب فروخته شده' + "</span>";
            ;
        }
    }];

    Extention.postAsync('getDashboardData', {}).then(function (msg) {
        for (var i = 0; i < msg.Data.pieChart.length; i++) {
            msg.Data.pieChart[i].Count = Number(msg.Data.pieChart[i].Count);
        }
        $scope.data = msg.Data;
    });


    $scope.updateCounts = function () {
        $timeout(function () {
            var data = {};
            if (angular.isDefined($scope.countFilter.to) && $scope.countFilter.to != "")
                data.toDate =DateConverter.convertDateToISO($scope.countFilter.toFull, true);

            if (angular.isDefined($scope.countFilter.from) && $scope.countFilter.from != "")
                data.fromDate = DateConverter.convertDateToISO($scope.countFilter.fromFull);
            Extention.postAsync('getDashboardCounts', data).then(function (msg) {
                $scope.count = msg.Data;
                $scope.updateExcelData(msg.Data);
            });
        });
    }
    $scope.exelData = [];
    $scope.updateExcelData = function (data) {
        $scope.exelData = [];
        if (angular.isDefined($scope.countFilter.from) && $scope.countFilter.from != "")
        {
            var date = persianDate($scope.countFilter.fromFull.unix).format("dddd, MMMM DD YYYY"); //moment(DateConverter.convertDateToISO($scope.countFilter.fromFull));
            $scope.exelData.push(['از تاریخ',date]);
        }
        if (angular.isDefined($scope.countFilter.to) && $scope.countFilter.to != "")
        {
            var date = persianDate($scope.countFilter.toFull.unix).format("dddd, MMMM DD YYYY");
            $scope.exelData.push(['تا تاریخ',date]);
        }
        if (data.Cope.cuttedCopes)
        {
            $scope.exelData.push(["تعداد بلوک بریده شده" , data.Cope.cuttedCopes]);
        }
        if (data.Cope.total)
        {
            $scope.exelData.push(['تعداد کل بلوک وارد شده',data.Cope.total]);
        }
        if (data.Stone.MachineAArea)
        {
            $scope.exelData.push(['متراژ بریده شده با دستگاه A',(Number(data.Stone.MachineAArea) / 1000000).toFixed(1) , 'متر مربع']);
        }
        if (data.Stone.MachineACount)
        {
            $scope.exelData.push(['تعداد اسلب های بریده شده با دستگاه A',data.Stone.MachineACount]);
        }
        if (data.Stone.MachineBArea)
        {
            $scope.exelData.push(['متراژ بریده شده با دستگاه B',(Number(data.Stone.MachineBArea) / 1000000).toFixed(1) , 'متر مربع']);
        }
        if (data.Stone.MachineBCount)
        {
            $scope.exelData.push(['تعداد اسلب های بریده شده با دستگاه B',data.Stone.MachineBCount]);
        }
        if (data.Stone.MachineCArea)
        {
            $scope.exelData.push(['متراژ بریده شده با دستگاه C',(Number(data.Stone.MachineCArea) / 1000000).toFixed(1) , 'متر مربع']);
        }
        if (data.Stone.MachineCCount)
        {
            $scope.exelData.push(['تعداد اسلب های بریده شده با دستگاه C',data.Stone.MachineCCount]);
        }
        if (data.Stone.notSoldCount)
        {
            $scope.exelData.push(['تعداد اسلب های موجود',data.Stone.notSoldCount]);
        }
        if (data.Stone.notSoldMetricCount)
        {
            $scope.exelData.push(['متراژ اسلب های موجود',(Number(data.Stone.notSoldMetricCount) / 1000000).toFixed(1) , 'متر مربع']);
        }
        if (data.Stone.soldCount)
        {
            $scope.exelData.push(['تعداد اسلب های فروخته شده',data.Stone.soldCount]);
        }
        if (data.Stone.soldMetricCount)
        {
            $scope.exelData.push(['متراژ اسلب های فروخته شده',(Number(data.Stone.soldMetricCount) / 1000000).toFixed(1) , 'متر مربع']);
        }
        if (data.Stone.totalMetricStones)
        {
            $scope.exelData.push(['متراژ کل اسلب ها',(Number(data.Stone.totalMetricStones) / 1000000).toFixed(1) , 'متر مربع']);
        }
        if (data.Stone.totalStones)
        {
            $scope.exelData.push(['تعداد کل اسلب ها',data.Stone.totalStones]);
        }
    }
    $scope.updateCounts();

    $scope.updateNewQuestionDiagram = function () {
        $timeout(function () {
            var data = {};

            if (angular.isDefined($scope.SerialGraph.stoneType))
                data.StoneTypeID = $scope.SerialGraph.stoneType.StoneTypeID;

            if (angular.isDefined($scope.SerialGraph.to) && $scope.SerialGraph.to != "")
                data.toDate = DateConverter.convertDateToISO($scope.SerialGraph.toFull, true);

            if (angular.isDefined($scope.SerialGraph.from) && $scope.SerialGraph.from != "")
                data.fromDate = DateConverter.convertDateToISO($scope.SerialGraph.fromFull);

            Extention.post('getSerialGraphData', data).then(function (res) {
                $scope.data.serialChart = res;
            });

        });
    }
    $scope.pieChart = {};
    $scope.updatePieChart = function () {
        $timeout(function () {
            var data = {};

            if (angular.isDefined($scope.pieChartDate.to) && $scope.pieChartDate.to != "")
                data.toDatePieChart = DateConverter.convertDateToISO($scope.pieChartDate.toFull, true);

            if (angular.isDefined($scope.pieChartDate.from) && $scope.pieChartDate.from != "")
                data.fromDatePieChart = DateConverter.convertDateToISO($scope.pieChartDate.fromFull);

            if (angular.isDefined($scope.pieChart.searchType)) {
                data.SearchTypeID = $scope.pieChart.searchType.ID;
            }


            Extention.postAsync('getDashboardData', data).then(function (res) {
                $scope.data.pieChart = res.Data;
            });
        });
    }

    $scope.updateNewQuestionDiagram();

    activeElement('#SDashboard');
});