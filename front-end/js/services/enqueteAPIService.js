angular.module("enquete").service("enqueteAPI", [
    "$http", "CONSTANTS",
    function ($http, CONSTANTS) {

        this.criar = function (enquete) {
            return $http.post(CONSTANTS.baseUrl + "conta", enquete);
        };
        this.get = function (id) {
            return $http.get(CONSTANTS.baseUrl + "enquete/" + id)
        };
    }]);