angular.module("enquete").service("contaAPI", [
    "$http", "CONSTANTS", "OAuth", "OAuthToken", "localStorageService",
    function ($http, CONSTANTS, OAuth, OAuthToken, localStorageService) {
        this.logout = function () {
            localStorageService.remove("account");

            OAuthToken.removeToken();
        };

        this.criar = function (conta) {
            console.log(CONSTANTS.baseUrl);
            return $http.post(CONSTANTS.baseUrl + "conta", conta);
        };
        this.login = function (username, password) {
            // limpa os dados de um eventual usuário no cookie
            this.logout();
            return OAuth.getAccessToken({
                username: username,
                password: password
            });
        };
        this.get = function () {
            return localStorageService.get('account')
        };
        this.set = function (account) {
            localStorageService.set('account', account);
        };
        this.isAuthenticated = function () {
            return OAuth.isAuthenticated();
        };
    }]);