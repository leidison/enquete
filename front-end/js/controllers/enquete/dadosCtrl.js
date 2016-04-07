angular.module("enquete").controller("dadosEnqueteCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "contaAPI", "Flash",
    function ($rootScope, $scope, $location, MESSAGES, contaAPI, Flash) {

        $scope.disableButton = false;

        // chamado ao submeter o formulário já validado
        $scope.submit = function (conta) {

            // usado para bloquear o botão enquanto a tela não é redirecionada
            $scope.disableButton = true;

            contaAPI.create(conta).success(function (account) {

                // usuário criado com sucesso. Agora tentarei logar ele
                contaAPI.login(conta.email, conta.password).then(
                    // sucesso ao autenticar
                    function (data) {
                        Flash.create('success', MESSAGES.contaRegistrada);

                        // login com sucesso. Agora jogarei na sessão os dados dele
                        contaAPI.set(account);

                        $location.path("/minhas-enquetes");
                    },
                    // erro ao tentar autenticar.
                    // continua na pagina para tentar autenticar
                    function (data) {
                        Flash.create('success', MESSAGES.contaRegistradaSemLogin);

                        $scope.disableButton = false;
                    }
                );
            }).error(function (errorResponse) {
                Flash.create('danger', MESSAGES.error);
                $scope.disableButton = false;
            });
        };

        // chamado ao validar se o botão de submit deve ser desabilitado ou não
        $scope.disableSubmission = function (form) {
            return form.$invalid || form.plainPassword.$invalid || $scope.disableButton
        };
    }]);