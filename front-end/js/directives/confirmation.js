angular.module('enquete').directive('confirm', function () {
    return {
        require: 'ngModel',
        restrict: "A",
        link: function (scope, elem, attrs, ctrl) {
            function matchValidator(value) {
                scope.$watch(attrs.confirm, function (newValue, oldValue) {
                    var isValid = value === scope.$eval(attrs.confirm) || !scope.$eval(attrs.confirm);
                    ctrl.$setValidity('confirm', isValid);
                });
                return value;
            }
            ctrl.$parsers.push(matchValidator);
        }
    };
});