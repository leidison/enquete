angular.module("enquete").controller("minhasEnquetesCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "enqueteAPI", "Flash", "localStorageService",
    function ($rootScope, $scope, $location, MESSAGES, enqueteAPI, Flash, localStorageService) {

        $rootScope.setTituloPagina("Minhas dúvidas");

        $scope.paginacao = {pagina: 1};

        $scope.enquetes = [];

        $scope.carregaLista = function (paginacao) {

            // o infinit scroll está com um bug. Mesmo que eu vá para outra pagina, a biblioteca continua
            // disparando as funções. Então enquanto não encontro outra biblioteca ou tento corrigir, vou deixar a valiação abaixo
            if (localStorageService.get('urlAtual') != '/minhas-enquetes') {
                console.log('ng infinit scroll bugado. Tela "minhas-enquetes"');
                return false;
            }
            enqueteAPI.getMinhas(paginacao, function (enquetes) {
                if (enquetes.length > 0) {
                    $scope.enquetes = $scope.enquetes.concat(enquetes);
                    paginacao.pagina += 1;
                }
            }, function (errorResponse) {
                Flash.clear();
                Flash.create('danger', MESSAGES.erroListaMinhasEnquetes);
            });
        };

        $scope.deleteEnquete = function (enquetes, enquete) {
            Flash.clear();
            enqueteAPI.delete({id: enquete.id},
                function () {
                    Flash.create("success", MESSAGES.sucessoExcluirEnquete);
                    enquetes.splice(enquetes.indexOf(enquete), 1);
                }, function (erro) {
                    Flash.create("danger", MESSAGES.erroExcluirEnquete);
                }
            );
        };
    }]);