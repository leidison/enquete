angular.module("enquete").factory("enqueteAPI", [
    "$resource", "CONSTANTS",
    function ($resource, CONSTANTS) {
        return $resource(CONSTANTS.baseUrl + "enquete/:id", {}, {
            get: {method: 'GET'},
            getMinhas: {method: 'GET', url: CONSTANTS.baseUrl + "enquete/minhas", isArray:true},
            save: {method: 'POST'},
            delete: {method: 'DELETE'},
            update: {method: 'PUT'}
        });
    }]);