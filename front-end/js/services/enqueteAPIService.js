angular.module("enquete").factory("enqueteAPI", [
    "$resource", "CONSTANTS",
    function ($resource, CONSTANTS) {
        return $resource(CONSTANTS.baseUrl + "enquete/:id", {}, {
            get: {method: 'GET'},
            save: {method: 'POST'},
            delete: {method: 'DELETE'},
            update: {method: 'PUT'}
        });
    }]);