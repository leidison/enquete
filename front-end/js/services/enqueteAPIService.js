angular.module("enquete").factory("enqueteAPI", [
    "$resource", "CONSTANTS",
    function ($resource, CONSTANTS) {
        return $resource(CONSTANTS.baseUrl + "enquete/:id", {}, {
            getOne: {method: 'GET', headers: {'Authorization': ''}},
            get: {method: 'GET', isArray: true, headers: {'Authorization': ''}},
            getMinhas: {method: 'GET', url: CONSTANTS.baseUrl + "enquete/minhas", isArray: true},
            save: {method: 'POST'},
            delete: {method: 'DELETE'},
            update: {method: 'PUT'}
        });
    }]);