angular.module("enquete").controller("dadosEnqueteCtrl", [
    "$rootScope", "$scope", "$location", "$routeParams", "MESSAGES", "enqueteAPI", "Flash",
    function ($rootScope, $scope, $location, $routeParams, MESSAGES, enqueteAPI, Flash) {

        $rootScope.setTituloPagina("Dados da enquete");

        // Preenche os dados da enquete ou popula com valores default
        if ($routeParams.id) {
            enqueteAPI.get({id: $routeParams.id},
                function (enquete) {
                    $scope.enquete = enquete;
                    // buscou com sucesso
                }, function (erro) {
                    // ocorreu um erro
                    Flash.clear();
                    Flash.create("danger", MESSAGES.erroBuscaEnquete, MESSAGES.infinity, MESSAGES.mostrarNaProximaPagina);
                    $location.path("/minhas-enquetes");
                });
        } else {
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
        }

        $scope.disableButton = false;

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

        // chamado ao validar se o botão de submit deve ser desabilitado ou não
        $scope.disableSubmission = function (form) {
            return form.$invalid || $scope.disableButton
        };

        $scope.submit = function (enquete) {

            $scope.disableButton = true;

            // limpa as mensagens da tela
            Flash.clear();

            // basicamente irá limpar os paineis não preenchidos
            trataSubmit(enquete);

            // se é uma edição
            if (enquete.id) {
                enqueteAPI.update({id: enquete.id}, enquete,
                    function success(response) {
                        $scope.disableButton = false;
                        Flash.create('success', MESSAGES.sucessoEdicaoEnquete, MESSAGES.default, MESSAGES.mostrarNaProximaPagina);
                        $location.path("/minhas-enquetes");
                    },
                    function error(errorResponse) {
                        $scope.disableButton = false;
                        Flash.create('danger', MESSAGES.erroEdicaoEnquete, MESSAGES.infinity);
                    }
                );
            } else {
                enqueteAPI.save({}, enquete,
                    function success(response) {
                        $scope.disableButton = false;
                        Flash.create('success', MESSAGES.sucessoCadastroEnquete, MESSAGES.default, MESSAGES.mostrarNaProximaPagina);
                        $location.path("/minhas-enquetes");
                    },
                    function error(errorResponse) {
                        Flash.create('danger', MESSAGES.erroCadastroEnquete, MESSAGES.infinity);
                        $scope.disableButton = false;
                    }
                );

            }
        };

        var limpaEdicaoPergunta = function (objetoEdicao) {
            console.log(objetoEdicao);
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