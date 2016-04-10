angular.module("enquete").controller("minhasEnquetesCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "enqueteAPI", "Flash",
    function ($rootScope, $scope, $location, MESSAGES, enqueteAPI, Flash) {

        $rootScope.setTituloPagina("Minhas dúvidas");

        $scope.paginacao = {pagina: 1};

        $scope.enquetes = [];

        $scope.carregaLista = function (paginacao) {

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
    }]);