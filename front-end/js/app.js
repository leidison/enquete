angular.module("enquete", [
    "ngRoute",
    // Gerencia a reutilização das mensagens de validação de formulario
    "ngMessages",
    // Gerencia as flash messages da aplicação
    "ngFlash",
    // Barra de progresso em requisições XHR oriundas do angular
    "angular-loading-bar",

    'ngResource',

    "angular-oauth2",
    // usado para guardar os dados do usuário nos cookies
    "LocalStorageModule",
    // usado para carregar elementos na tela. Dando aquela impressão de scroll infinito
    "infinite-scroll"
]);