angular.module("enquete").controller("loginCtrl", [
    "$rootScope", "$scope", "$location", "CONSTANTS", "MESSAGES", "contaAPI", "Flash",
    function ($rootScope, $scope, $location, CONSTANTS, MESSAGES, contaAPI, Flash) {

        $scope.disableButton = false;

        $scope.login = function (credencial) {


            $scope.disableButton = true;

            contaAPI.login(credencial.email, credencial.password).then(
                // success
                function (data) {
                    contaAPI.get().then(function (conta) {

                        contaAPI.set(conta.data);

                        Flash.create('info', MESSAGES.sucessoLogin);

                        $location.path("/");
                    }, function (erro) {

                        $scope.disableButton = false;

                        Flash.create('warning', MESSAGES.errorGetconta, MESSAGES.infinity);
                    });
                },
                // error
                function (data) {

                    $scope.loginForm.$setPristine();

                    // atualmente o clear não funciona com flash sem id
                    // abri uma solicitação de mudança no github do autor da biblioteca
                    //Flash.clear();
                    $rootScope.flashes = [];
                    console.log(data);
                    if (data.status === 400) {
                        Flash.create('warning', MESSAGES.erroLogin);
                    } else if (data.status === 500) {
                        Flash.create('danger', MESSAGES.error);
                    } else if (data.status == -1) {
                        Flash.create('danger', MESSAGES.erroServidorOff);
                    }

                    $scope.disableButton = false;
                }
            );

        };
        // chamado ao validar se o botão de submit deve ser desabilitado ou não
        $scope.disableSubmission = function (form) {
            return form.$invalid || $scope.disableButton
        };

    }
]);