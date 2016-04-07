/**
 * Created by Leidison Siqueira Barbosa on 04/01/16.
 */
angular.module("enquete").config(function ($routeProvider, $httpProvider, $locationProvider) {
    // Desativo o coringa (#).
    $locationProvider.html5Mode({enabled: true, requireBase: false});


    // requireLogin e redirectToIfLogged é contralado no arquivo OAuth2Config.js

    //$routeProvider.when("/", {
    //    templateUrl: "view/home.html",
    //    controller: "homeCtrl",
    //    data: {
    //        requireLogin: false
    //    }
    //});
    $routeProvider.when("/acesso", {
        templateUrl: "view/conta/login.html",
        controller: "loginCtrl",
        data: {
            redirectToIfLogged: '/minhas-enquetes'
        }
    });
    //$routeProvider.when("/logout", {
    //    controller: "logoutCtrl",
    //    template: '',
    //    data: {
    //        requireLogin: false
    //    }
    //});
    //$routeProvider.when("/minhas-enquetes", {
    //    templateUrl: "view/enquete/minhas.html",
    //    controller: "minhasEnquetesCtrl",
    //    data: {
    //        requireLogin: true
    //    }
    //});
    //$routeProvider.when("/minhas-enquetes/cadastro", {
    //    templateUrl: "/view/enquete/dados.html",
    //    controller: "dadosEnqueteCtrl",
    //    data: {
    //        requireLogin: true
    //    }
    //});
    //$routeProvider.when("/minhas-enquetes/{id}/edicao", {
    //    templateUrl: "view/enquete/dados.html",
    //    controller: "dadosEnqueteCtrl",
    //    data: {
    //        requireLogin: true
    //    }
    //});
    //$routeProvider.when("/enquete/{id}/colaborando", {
    //    templateUrl: "/view/enquete/colaborando.html",
    //    controller: "colaborandoEnqueteCtrl",
    //    data: {
    //        requireLogin: false
    //    }
    //});
    //$routeProvider.when("/enquete/resultado", {
    //    templateUrl: "/view/enquete/resultado.html",
    //    controller: "resultadoEnqueteCtrl",
    //    data: {
    //        requireLogin: false
    //    }
    //});
    //$routeProvider.otherwise({
    //    redirectTo: "/"
    //});

    $httpProvider.defaults.withCredentials = true;
    delete $httpProvider.defaults.headers.common["X-Requested-With"];

});