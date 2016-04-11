angular.module("enquete").factory("avaliacaoAPI", [
    "$resource", "CONSTANTS",
    function ($resource, CONSTANTS) {
        return $resource(CONSTANTS.baseUrl + "enquete/:id/avaliacao", {}, {
            save: {method: 'POST', headers: {'Authorization': ''}}
        });
    }]);