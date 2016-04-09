angular.module("enquete").controller("dadosEnqueteCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "enqueteAPI", "Flash",
    function ($rootScope, $scope, $location, MESSAGES, enqueteAPI, Flash) {


        $rootScope.setTituloPagina("Dados da enquete");

        $scope.enquete = {
            titulo: 'teste',
            perguntas: [
                {
                    descricao: "pergunta1",
                    respostas: [
                        {descricao: 'resposta 1'},
                        {descricao: 'resposta 2'}
                    ]
                },
                {
                    descricao: "pergunta2",
                    respostas: [
                        {descricao: 'resposta 2'},
                        {descricao: 'resposta 3'}
                    ]
                }
            ]
        };

        $scope.addResposta = function (auxResposta, respostas) {
            // verifica se o campo foi preenchido
            if (auxResposta && auxResposta.descricao) {
                // verifica se é edicao ou cadastro.
                // se tiver "original" quer dizer que é edicao
                if (auxResposta.original) {
                    // pega a descricao digitada e passa para o objeto original
                    auxResposta.original.descricao = auxResposta.descricao;
                } else {
                    // verifica se a resposta repete
                    var resposta = respostas.filter(function (element) {
                        return element.descricao == auxResposta.descricao;
                    });
                    if (!resposta.length) {
                        // adiciona a resposta
                        respostas.push({
                            descricao: auxResposta.descricao
                        });
                    }
                }
                // limpa o campo de preenchimento
                limpaEdicaoPergunta(auxResposta);
            }
        };

        $scope.edicaoReposta = function (resposta, objetoEdicao) {
            // passei campo por campo em vez de um novo objeto,
            // para evitar de perder o gerencimento do angular
            objetoEdicao.descricao = resposta.descricao;
            objetoEdicao.original = resposta;
        };

        $scope.removeResposta = function (key, respostas, auxResposta) {
            limpaEdicaoPergunta(auxResposta);
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

            if (enquete.id) {
                enqueteAPI.update({}, enquete,
                    function success(response) {
                        console.log('leidison');
                    },
                    function error(errorResponse) {
                        console.log(errorResponse);
                    }
                );
            } else {
                enqueteAPI.save({}, enquete,
                    function success(response) {
                  console.log('leidison');
                    },
                    function error(errorResponse) {
                        console.log(errorResponse);
                    }
                );

            }
        };

        // chamado ao validar se o botão de submit deve ser desabilitado ou não
        $scope.disableSubmission = function (form) {
            return form.$invalid || $scope.disableButton
        };

        var limpaEdicaoPergunta = function (objetoEdicao) {
            // passei campo por campo em vez de um novo objeto,
            // para evitar de perder o gerencimento do angular
            delete objetoEdicao.id;
            delete objetoEdicao.descricao;
            delete objetoEdicao.original;
        };

        var trataSubmit = function (enquete) {
            enquete.perguntas = enquete.perguntas.filter(function (pergunta) {
                /*
                 * mesmo com a função disableSubmission algumas duvidas podem ficar sem preenchimento
                 * isso acontece quando o usuário adiciona uma duvida e não preenche nenhuma informação
                 * (ou quando ele apaga todas)
                 * então eu estou deixando apenas as que possuem descricao
                 */
                return !!pergunta.descricao;
            });
            // se não tiver sobrado nenhuma pergunta
            if (!enquete.perguntas) {
                // coloco um valor default para que os paineis apareçam na tela
                enquete.perguntas = [{
                    respostas: []
                }];
            }
        };
    }]);