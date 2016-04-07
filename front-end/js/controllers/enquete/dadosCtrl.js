angular.module("enquete").controller("dadosEnqueteCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "contaAPI", "Flash",
    function ($rootScope, $scope, $location, MESSAGES, contaAPI, Flash) {

        $rootScope.setTituloPagina("Dados da enquete");

        $scope.enquete = {
            perguntas: [{
                respostas: []
            }]
        };

        $scope.addResposta = function (aux, respostas) {
            var resposta = respostas.filter(function (element) {
                return element.descricao == aux.descricao;
            });
            if (!resposta.length) {
                respostas.push({
                    descricao: aux.descricao
                });
            }
            aux.descricao = '';
        };

        $scope.addDuvida = function (perguntas) {
            perguntas.push({respostas: []});
        };

        $scope.removeDuvida = function (key, perguntas) {
            perguntas.splice(key, 1);
        };

        // chamado ao validar se o botão de submit deve ser desabilitado ou não
        $scope.disableSubmission = function (form) {
            return form.$invalid || $scope.disableButton
        };
    }]);