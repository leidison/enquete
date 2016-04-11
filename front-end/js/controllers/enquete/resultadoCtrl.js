angular.module("enquete").controller("resultadoEnqueteCtrl", [
    "$rootScope", "$scope", "$location", "$routeParams", "$filter", "MESSAGES", "enqueteAPI", "avaliacaoAPI", "Flash", "localStorageService",
    function ($rootScope, $scope, $location, $routeParams, $filter, MESSAGES, enqueteAPI, avaliacaoAPI, Flash, localStorageService) {
        enqueteAPI.getOne({id: $routeParams.id},
            function (enquete) {

                $rootScope.setTituloPagina(enquete.titulo, $filter('date')(enquete.data, 'medium'));

                $scope.enquete = enquete;

                $scope.urlVoltar = localStorageService.get('urlAnterior') === '/minhas-enquetes' ? '/minhas-enquetes' : '/';

                $scope.colaboracao = [];

                var maiorValor = 0;
                var maiorResposta = [];
                angular.forEach($scope.enquete.perguntas, function (pergunta, key) {
                    pergunta.quantidade = 0;
                    angular.forEach(pergunta.respostas, function (resposta, key) {
                        resposta.maior = false;
                        pergunta.quantidade += resposta.quantidade;
                        if (resposta.quantidade > maiorValor) {
                            maiorResposta = [key];
                            maiorValor = resposta.quantidade;
                        } else if (resposta.quantidade == maiorValor) {
                            maiorValor = resposta.quantidade;
                            maiorResposta.push(key);
                        }
                    });

                    angular.forEach(maiorResposta, function (index) {
                        pergunta.respostas[index].maior = true;
                    });
                });


            }, function (erro) {
                // ocorreu um erro
                Flash.clear();
                Flash.create("danger", MESSAGES.erroBuscaEnquete, MESSAGES.infinity, {class: "oneChanceToClose"});
                $location.path("/");
            });

    }]);