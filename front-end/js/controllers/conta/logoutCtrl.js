angular.module("enquete").controller("logoutCtrl", [
    "$location", "contaAPI", "Flash", "MESSAGES",
    function ($location, contaAPI, Flash, MESSAGES) {
        contaAPI.logout();
        Flash.create('success', MESSAGES.sucessoLogout, MESSAGES.infinity, {class: "oneChanceToClose"});
        $location.path("/");
    }
]);