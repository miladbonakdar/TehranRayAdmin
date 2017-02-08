;(function (window) {
  var angular = window.angular;

  /** TEMPLATE /template/treasure-overlay-spinner/treasure-overlay-spinner.html
   *  <div class="treasure-overlay-spinner-content">
   *    <div class="treasure-overlay-spinner-container">
   *      <div class="treasure-overlay-spinner"></div>
   *    </div>
   *    <ng-transclude></ng-transclude>
   *  </div>
   */
/**
 *
 *
 <div class="loader">
 <svg class="circular" viewBox="25 25 50 50">
 <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
 </svg>
 </div>
 *
 *
 *
 * */
  // constants
  var TEMPLATE_PATH = '/template/treasure-overlay-spinner/treasure-overlay-spinner.html';
  var TEMPLATE = '';
  TEMPLATE += '<div class="treasure-overlay-spinner-content">';
  TEMPLATE +=   '<div class="treasure-overlay-spinner-container" style="height: 10000000px;">';
  TEMPLATE +=     '<div class="loader" style="width:{{spinnerSize}}px">'+
                    '<svg class="circular" viewBox="25 25 50 50">'+
                    '<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="{{spinnerStorkeWidth}}" stroke-miterlimit="10"/>'+
                    '</svg>'+
                  '</div>';
  TEMPLATE +=   '</div>';
  TEMPLATE +=   '<ng-transclude></ng-transclude>';
  TEMPLATE += '</div>';

  // module
  angular.module('treasure-overlay-spinner', ['ngAnimate']);

  // directive
  angular.module('treasure-overlay-spinner').directive('treasureOverlaySpinner', overlaySpinner);
  overlaySpinner.$inject = ['$animate'];
  function overlaySpinner ($animate) {
    return {
      templateUrl: TEMPLATE_PATH,
      scope: {
          active: '=',
          spinnerStorkeWidth : '@',
          spinnerSize : '@'
      },
      transclude: true,
      restrict: 'E',
      link: link
    };

    function link (scope, iElement) {
      scope.$watch('active', statusWatcher);
      function statusWatcher (active) {
        $animate[active ? 'addClass' : 'removeClass'](iElement, 'treasure-overlay-spinner-active');
      }
    }
  }

  // template
  angular.module('treasure-overlay-spinner').run(overlaySpinnerTemplate);
  overlaySpinnerTemplate.$inject = ['$templateCache'];
  function overlaySpinnerTemplate ($templateCache) {
    $templateCache.put(TEMPLATE_PATH, TEMPLATE);
  }

}.call(this, window));

