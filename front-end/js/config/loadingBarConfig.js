/**
 * Created by Leidison Siqueira Barbosa on 04/01/16.
 */
angular.module("enquete").run(function ($rootScope) {
    $rootScope.$on('cfpLoadingBar:started', function () {
        if (angular.element('button[type="submit"]').hasClass('disabled')) {
            $rootScope.enquete = {
                dontRemoveDisable: true
            };
        } else {
            angular.element('button[type="submit"]').addClass('disabled');
        }
    });
    $rootScope.$on('cfpLoadingBar:completed', function () {
        if ($rootScope.enquete && $rootScope.enquete.dontRemoveDisable) {
            delete $rootScope.enquete.dontRemoveDisable;
        } else {
            angular.element('button[type="submit"]').removeClass('disabled');
        }
    });
});