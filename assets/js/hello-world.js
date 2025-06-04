(function ($) {
    'use strict';
    $(document).ready(function () {
        function helloWorldWidget($scope, $) {
            console.log($scope,$)
        }
        elementorFrontend.hooks.addAction('frontend/element_ready/hello_world.default', helloWorldWidget);
    });
})(jQuery);
