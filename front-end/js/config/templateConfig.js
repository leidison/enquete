angular.module("enquete").run(["$rootScope", "contaAPI", function ($rootScope) {
    $rootScope.$on("$routeChangeSuccess", function () {

        //$rootScope.account = contaAPI.isAuthenticated() ? contaAPI.get(true) : null;

        $rootScope.header = {
            title: '',
            subtitle: ''
        };
    });
}]);