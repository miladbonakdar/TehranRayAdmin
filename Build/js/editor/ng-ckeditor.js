'use strict';

(function (angular, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['angular', 'ckeditor'], function (angular) {
            return factory(angular);
        });
    } else {
        return factory(angular);
    }
}(angular || null, function (angular) {
    var app = angular.module('ngCkeditor', []);
    var $defer, loaded = false;

    app.run(['$q', '$timeout', function ($q, $timeout) {
        $defer = $q.defer();

        if (angular.isUndefined(CKEDITOR)) {
            throw new Error('CKEDITOR not found');
        }
        CKEDITOR.disableAutoInline = true;
        function checkLoaded() {
            if (CKEDITOR.status === 'loaded') {
                loaded = true;
                $defer.resolve();
            } else {
                checkLoaded();
            }
        }

        CKEDITOR.on('loaded', checkLoaded);
        $timeout(checkLoaded, 100);
    }]);

    app.directive('ckeditor', ['$timeout', '$q', function ($timeout, $q) {

        return {
            restrict: 'AC',
            require: ['ngModel', '^?form'],
            scope: false,
            link: function (scope, element, attrs, ctrls) {
                var ngModel = ctrls[0];
                var form = ctrls[1] || null;
                var EMPTY_HTML = '<p></p>',
                    isTextarea = element[0].tagName.toLowerCase() === 'textarea',
                    data = [],
                    isReady = false;

                if (!isTextarea) {
                    element.attr('contenteditable', true);
                }

                var onLoad = function () {
                    var options = {
                        toolbar: 'full',
                        toolbar_full: [
		{ name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
		{ name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
		{ name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
		{ name: 'forms', items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] },
		
		{ name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
		{ name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
		{ name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
		{ name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
		
		{ name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
		{ name: 'colors', items: ['TextColor', 'BGColor'] },
		{ name: 'tools', items: ['Maximize', 'ShowBlocks'] },
		{ name: 'about', items: ['About'] }
                        ],
                        disableNativeSpellChecker: false,
                        height: '300px',
                        width: '100%'
                    };
                    options = angular.extend(options, scope[attrs.ckeditor]);

                    var instance = (isTextarea) ? CKEDITOR.replace(element[0], options) : CKEDITOR.inline(element[0], options),
                        configLoaderDef = $q.defer();

                    element.bind('$destroy', function () {
                        if (instance && CKEDITOR.instances[instance.name]) {
                            CKEDITOR.instances[instance.name].destroy();
                        }
                    });
                    var setModelData = function (setPristine) {
                        var data = instance.getData();
                        if (data === '') {
                            data = null;
                        }
                        $timeout(function () { // for key up event
                            if (setPristine !== true || data !== ngModel.$viewValue) {
                                ngModel.$setViewValue(data);
                            }

                            if (setPristine === true && form) {
                                form.$setPristine();
                            }
                        }, 0);
                    }, onUpdateModelData = function (setPristine) {
                        if (!data.length) {
                            return;
                        }

                        var item = data.pop() || EMPTY_HTML;
                        isReady = false;
                        instance.setData(item, function () {
                            setModelData(setPristine);
                            isReady = true;
                        });
                    };

                    instance.on('pasteState',   setModelData);
                    instance.on('change', setModelData);
                    instance.on('blur', setModelData);
                    //instance.on('key',          setModelData); // for source view

                    instance.on('instanceReady', function () {
                        scope.$broadcast('ckeditor.ready');
                        scope.$apply(function () {
                            onUpdateModelData(true);
                        });

                        instance.document.on('keyup', setModelData);
                    });
                    instance.on('customConfigLoaded', function () {
                        configLoaderDef.resolve();
                    });

                    ngModel.$render = function () {
                        data.push(ngModel.$viewValue);
                        if (isReady) {
                            onUpdateModelData();
                        }
                    };
                };

                if (CKEDITOR.status === 'loaded') {
                    loaded = true;
                }
                if (loaded) {
                    onLoad();
                } else {
                    $defer.promise.then(onLoad);
                }
            }
        };
    }]);

    return app;
}));