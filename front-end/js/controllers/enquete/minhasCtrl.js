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