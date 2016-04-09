angular.module("enquete").controller("minhasEnquetesCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "enqueteAPI", "Flash",
    function ($rootScope, $scope, $location, MESSAGES, enqueteAPI, Flash) {

        $rootScope.setTituloPagina("Minhas dúvidas");

        $scope.paginacao = {pagina: 1};

        $scope.carregaLista = function (paginacao) {

            enqueteAPI.getMinhas(paginacao, function (response) {
                $scope.enquetes = response.data;
                paginacao.pagina += 1;
            }, function (errorResponse) {
                Flash.clear();
                Flash.create('danger', MESSAGES.erroListaMinhasEnquetes);
            });
        };
    }]);