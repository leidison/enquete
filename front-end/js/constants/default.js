/**
 * Created by Leidison Siqueira on 04/01/16.
 */
angular.module("enquete").constant('CONSTANTS', {
    baseUrl: "http://localhost:8000/api/",
    recaptchaKey: "6Le3nBcTAAAAALYKosDVLB_I8l-aKNe68hnOgtPG",
    auth: {
        clientId: "1_3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4",
        grantPath: "/oauth/v2/token"
    },
    country: {
        brazil: 1,
        unitedStatesOfAmerica: 2
    },
});