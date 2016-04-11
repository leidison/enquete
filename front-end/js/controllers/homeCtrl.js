angular.module("enquete").controller("homeCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "enqueteAPI", "Flash", "localStorageService",
    function ($rootScope, $scope, $location, MESSAGES, enqueteAPI, Flash, localStorageService) {

        $scope.paginacao = {pagina: 1};

        $scope.enquetes = [];

        $scope.carregaLista = function (paginacao) {
            // o infinit scroll está com um bug. Mesmo que eu vá para outra pagina, a biblioteca continua
            // disparando as funções. Então enquanto não encontro outra biblioteca ou tento corrigir, vou deixar a valiação abaixo

            if (localStorageService.get('urlAtual') != '/') {
                console.log('ng infinit scroll bugado. Tela "home"');
                return false;
            }
            enqueteAPI.get(paginacao, function (enquetes) {
                if (enquetes.length > 0) {
                    $scope.enquetes = $scope.enquetes.concat(enquetes);
                    paginacao.pagina += 1;
                }
            }, function (errorResponse) {
                Flash.clear();
                Flash.create('danger', MESSAGES.erroListaMinhasEnquetes);
            });
        };
    }]);