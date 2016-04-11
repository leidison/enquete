angular.module("enquete").controller("colaborandoEnqueteCtrl", [
    "$rootScope", "$scope", "$location", "$routeParams", "$filter", "MESSAGES", "enqueteAPI", "avaliacaoAPI", "Flash",
    function ($rootScope, $scope, $location, $routeParams, $filter, MESSAGES, enqueteAPI, avaliacaoAPI, Flash) {
        enqueteAPI.getOne({id: $routeParams.id},
            function (enquete) {
                Flash.create('info', MESSAGES.ficouInteressadoColaborando, MESSAGES.infinity);

                $rootScope.setTituloPagina(enquete.titulo, $filter('date')(enquete.data, 'medium'));

                $scope.enquete = enquete;
                $scope.colaboracao = [];
                $scope.changeResposta = function (pergunta, resposta) {
                    var itemNovo = {
                        pergunta: pergunta,
                        resposta: resposta
                    };
                    // remove o item antigo
                    $scope.colaboracao = $scope.colaboracao.filter(function (itemAntigo) {
                        return itemAntigo.pergunta != itemNovo.pergunta;
                    });

                    $scope.colaboracao.push(itemNovo);
                };

                $scope.submit = function () {
                    Flash.clear();

                    avaliacaoAPI.save({id: $scope.enquete.id}, $scope.colaboracao,
                        function () {
                            Flash.create("success", MESSAGES.sucessoColaborar, MESSAGES.infinity, MESSAGES.mostrarNaProximaPagina);
                            $location.path("/enquete/" + $scope.enquete.id + "/resultado");
                        }, function (erro) {
                            Flash.create("danger", MESSAGES.erroColaborar, MESSAGES.infinity);
                        });
                }
            }, function (erro) {
                // ocorreu um erro
                Flash.clear();
                Flash.create("danger", MESSAGES.erroBuscaEnquete, MESSAGES.infinity, MESSAGES.mostrarNaProximaPagina);
                $location.path("/");
            });

    }]);