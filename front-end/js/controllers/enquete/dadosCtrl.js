angular.module("enquete").controller("dadosEnqueteCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "enqueteAPI", "Flash",
    function ($rootScope, $scope, $location, MESSAGES, enqueteAPI, Flash) {

        $rootScope.setTituloPagina("Dados da enquete");

        $scope.enquete = {
            perguntas: [{
                respostas: []
            }]
        };

        $scope.addResposta = function (aux, respostas) {
            if (aux && aux.descricao) {
                // verifica se a resposta repete
                var resposta = respostas.filter(function (element) {
                    return element.descricao == aux.descricao;
                });
                if (!resposta.length) {
                    // adiciona a resposta
                    respostas.push({
                        descricao: aux.descricao
                    });
                }
                // limpa o campo de preenchimento
                aux.descricao = '';
            }
        };

        $scope.removeResposta = function (key, respostas) {
            respostas.splice(key, 1);
        };

        $scope.addDuvida = function (perguntas) {
            perguntas.push({respostas: []});
        };

        $scope.removeDuvida = function (key, perguntas) {
            perguntas.splice(key, 1);
        };


        $scope.submit = function (enquete) {
            trataSubmit(enquete);
            if(enquete.id) {
                enqueteAPI.alterar(enquete)
            }else{
                enqueteAPI.criar(enquete)
            }
        };

        // chamado ao validar se o botão de submit deve ser desabilitado ou não
        $scope.disableSubmission = function (form) {
            return form.$invalid || $scope.disableButton
        };

        var trataSubmit = function (enquete) {
            enquete.perguntas = enquete.perguntas.filter(function (pergunta) {
                /*
                 * mesmo com a função disableSubmission algumas duvidas podem ficar sem preenchimento
                 * isso acontece quando o usuário adiciona uma duvida e não preenche nenhuma informação
                 * (ou quando ele apaga todas)
                 * então eu estou deixando apenas as que possuem nome
                 */
                return pergunta.nome;
            });
            if (!!enquete.perguntas) {
                enquete.perguntas = [{
                    respostas: []
                }];
            }
        };
    }]);