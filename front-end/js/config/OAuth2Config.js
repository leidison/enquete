angular.module("enquete").config([
    'OAuthProvider', 'OAuthTokenProvider', 'CONSTANTS',
    function (OAuthProvider, OAuthTokenProvider, CONSTANTS) {
        OAuthProvider.configure({
            baseUrl: CONSTANTS.baseUrl,
            clientId: CONSTANTS.auth.clientId,
            // o certo é não usar o client secret, mas n consegui
            // desativar isso no fosoauthbundle do symfony
            clientSecret: '4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k',
            grantPath: CONSTANTS.auth.grantPath
        });

        OAuthTokenProvider.configure({
            name: 'token',
            options: {
                secure: false
            }
        });
    }]).run(['$rootScope', '$window', 'OAuth', 'CONSTANTS', function ($rootScope, $window, OAuth, CONSTANTS) {
    $rootScope.$on('oauth:error', function (event, rejection) {

        // Ignore `invalid_grant` error - should be catched on `LoginController`.
        if ('invalid_grant' === rejection.data.error) {
            return;
        }

        // Refresh token when a `invalid_token` error occurs.
        if ('invalid_token' === rejection.data.error) {
            return OAuth.getRefreshToken();
        }

        // Redirect to `/login` with the `error_reason`.
        return $window.location.href = CONSTANTS.auth.grantPath + '?error_reason=' + rejection.data.error;
    });
}]);