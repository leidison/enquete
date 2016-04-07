/**
 * Created by Leidison Siqueira Barbosa on 04/01/16.
 */
angular.module("enquete").run(function ($rootScope) {
    $rootScope.$on("$routeChangeSuccess", function () {
        var messages = [];
        angular.forEach($rootScope.flashes, function (value, index) {
            // se a classe existir então não remove o flash message dessa vez.
            if (value.config && value.config.class && value.config.class.search("oneChanceToClose") != -1) {
                // removo a classe para que na proxima vez o flash message suma
                $rootScope.flashes[index].config.class = value.config.class.replace("oneChanceToClose", "");

                messages.push($rootScope.flashes[index]);
            }
        });

        $rootScope.flashes = messages;
    });
});