angular.module("enquete").service("contaAPI", [
    "$http", "CONSTANTS", "OAuth", "OAuthToken", "localStorageService",
    function ($http, CONSTANTS, OAuth, OAuthToken, localStorageService) {
        this.logout = function () {
            localStorageService.remove("conta");

            OAuthToken.removeToken();
        };

        this.criar = function (conta) {
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
            return localStorageService.get('conta');
        };
        this.set = function (conta) {
            delete conta.password;
            delete conta.plainPassword;
            localStorageService.set('conta', conta);
        };
        this.isAuthenticated = function () {
            if (!localStorageService.get('conta')) {
                OAuthToken.removeToken();
            }
            return OAuth.isAuthenticated();
        };
    }]);