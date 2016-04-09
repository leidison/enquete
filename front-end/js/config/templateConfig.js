angular.module("enquete").run(["$rootScope", "contaAPI", function ($rootScope, contaAPI) {

    // Usado para facilitar a verificação de erro nos formulários
    $rootScope.hasError = function (element) {
        return element.$invalid && !element.$pristine && element.$error.parse == undefined;
    };

    $rootScope.setTituloPagina = function (titulo, subtitulo) {
        $rootScope.pagina = {
            titulo: titulo,
            subtitulo: subtitulo
        };
    };

    $rootScope.$on("$routeChangeSuccess", function () {

        $rootScope.conta = contaAPI.isAuthenticated() ? contaAPI.get() : null;
        // limpa o titulo das paginas
        $rootScope.setTituloPagina();
    });
}]);

angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 2500);