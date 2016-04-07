angular.module("enquete").controller("minhasEnquetesCtrl", [
    "$rootScope", "$scope", "$location", "MESSAGES", "contaAPI", "Flash",
    function ($rootScope, $scope, $location, MESSAGES, contaAPI, Flash) {

        $rootScope.setTituloPagina("Minhas dúvidas");

    }]);