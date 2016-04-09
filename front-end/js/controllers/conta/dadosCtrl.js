angular.module("enquete").controller("dadosContaCtrl", [
    "$rootScope", "$scope", "$location", "CONSTANTS", "MESSAGES", "contaAPI", "Flash",
    function ($rootScope, $scope, $location, CONSTANTS, MESSAGES, contaAPI, Flash) {

        $scope.disableButton = false;

        // chamado ao submeter o formulário já validado
        $scope.submit = function (conta) {

            // usado para bloquear o botão enquanto a tela não é redirecionada
            $scope.disableButton = true;

            contaAPI.criar(conta).success(function (data) {


                // usuário criado com sucesso. Agora tentarei logar ele
                contaAPI.login(conta.email, conta.password).then(
                    // sucesso ao autenticar
                    function (data) {
                        Flash.create('success', MESSAGES.contaRegistrada, MESSAGES.infinity, {class: "oneChanceToClose"});

                        // login com sucesso. Agora jogarei na sessão os dados dele
                        contaAPI.set(conta);

                        $location.path("/minhas-enquetes");
                    },
                    // erro ao tentar autenticar.
                    // continua na pagina para tentar autenticar
                    function (data) {
                        Flash.create('success', MESSAGES.contaRegistradaSemLogin, MESSAGES.infinity);

                        $scope.disableButton = false;
                    }
                );
            }).error(function (errorResponse) {

                Flash.clear();
                console.log(errorResponse[0].message);
                if (errorResponse && errorResponse[0].message == "fos_user.username.already_used") {
                    Flash.create('warning', MESSAGES.dadosAcessosExistentes, MESSAGES.infinity);
                } else {
                    Flash.create('danger', MESSAGES.error, MESSAGES.infinity);
                }
                $scope.disableButton = false;
            });
        };

        // chamado ao validar se o botão de submit deve ser desabilitado ou não
        $scope.disableSubmission = function (form) {
            return form.$invalid || form.plainPassword.$invalid || $scope.disableButton
        };
    }]);