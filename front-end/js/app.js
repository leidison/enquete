angular.module("enquete", [
        "ngRoute",
        // Gerencia a reutilização das mensagens de validação de formulario
        "ngMessages",
        // Gerencia as flash messages da aplicação
        "ngFlash",
        // Barra de progresso em requisições XHR oriundas do angular
        "angular-loading-bar",

        "angular-oauth2",
        // usado para guardar os dados do usuário nos cookies
        "LocalStorageModule"
    ])
    .run(['$rootScope', function ($rootScope) {
        // Usado para facilitar a verificação de erro nos formulários
        $rootScope.hasError = function (element) {
            return element.$invalid && !element.$pristine && element.$error.parse == undefined;
        };
    }]);
