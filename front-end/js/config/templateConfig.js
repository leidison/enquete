angular.module("enquete").run(["$rootScope", "contaAPI", function ($rootScope, contaAPI) {

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