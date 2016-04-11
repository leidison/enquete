angular.module("enquete").controller("homeCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "enqueteAPI", "Flash",
    function ($rootScope, $scope, $location, MESSAGES, enqueteAPI, Flash) {

        $scope.paginacao = {pagina: 1};

        $scope.enquetes = [];

        $scope.carregaLista = function (paginacao) {

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