angular.module("enquete").controller("loginCtrl", [
    "$rootScope", "$scope", "$location", "CONSTANTS", "MESSAGES", "contaAPI", "Flash",
    function ($rootScope, $scope, $location, CONSTANTS, MESSAGES, contaAPI, Flash) {

        $scope.disableButton = false;

        $scope.login = function (credencial) {

            $scope.disableButton = true;

            contaAPI.login(credencial.email, credencial.password).then(
                // success
                function (data) {
                    $scope.disableButton = false;
                    contaAPI.set(credencial);
                    $location.path("/minhas-enquetes");
                },
                // error
                function (data) {

                    $scope.loginForm.$setPristine();

                    Flash.clear();
                    $rootScope.flashes = [];
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