angular.module('ui.navbar', ['ui.bootstrap'])

    .directive('tree', function () {
        return {
            restrict: 'E',
            replace: true,
            scope: {
                tree: '='
            },
            controller: function ($scope,Extention) {

                $scope.markAsRead = function () {
                    Extention.postAsync("markAsReadComments").then(function(res) {
                         
                    });
                }
            },
            template: '<ul class="dropdown-menu" style="padding: 0">'+
                            '<div style="max-height: 300px; overflow: auto">'+
                                '<leaf ng-repeat="leaf in tree" leaf="leaf"></leaf>'+
                            '</div>'+
                            '<div class="text-center" style="padding: 5px;background-color:#ddd" >'+
                                '<button class="btn btn-success" style="font-weight: 700" ng-click="markAsRead()" >همه رو خوندم</button>'+
                            '</div>'+
                        '</ul>'
        };
    })

    .directive('leaf', ['$compile', function ($compile) {
        return {
            restrict: 'E',
            replace: true,
            scope: {
                leaf: '='
            },
            templateUrl: 'partials/Admin/MenuTmpl/navbar-li.html',
            link: function (scope, element, attrs) {
                if (angular.isArray(scope.leaf.subtree)) {
                    element.append('<tree tree=\"leaf.subtree\"></tree>');

                    // find the parent of the element
                    var parent = element.parent();
                    var classFound = false;

                    // check if in the hierarchy of the element exists a dropdown with class navbar-right
                    while(parent.length > 0 && !classFound) {
                        // check if the dropdown has been push to right
                        if(parent.hasClass('navbar-right')) {
                            classFound = true;
                        }
                        parent = parent.parent();
                    }

                    // add a different class according to the position of the dropdown
                    if(classFound) {
                        element.addClass('dropdown-submenu-right');
                    } else {
                        element.addClass('dropdown-submenu');
                    }

                    $compile(element.contents())(scope);
                }
            }
        };
    }]);
