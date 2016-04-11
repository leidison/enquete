angular.module("enquete").config([
        'OAuthProvider', 'OAuthTokenProvider', 'CONSTANTS', '$provide', '$httpProvider',
        function (OAuthProvider, OAuthTokenProvider, CONSTANTS, $provide, $httpProvider) {
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

            // como o symfony está retornando um erro de autenticação diferente do parão da biblioteca,
            // então eu estou sobrescrevendo o interceptor
            $provide.factory('oauth2HttpInterceptor', function ($q, $rootScope, OAuthToken) {
                return {
                    // permanece igual à original
                    'request': function (config) {
                        config.headers = config.headers || {};
                        if (!config.headers.hasOwnProperty("Authorization") && OAuthToken.getAuthorizationHeader()) {
                            config.headers.Authorization = OAuthToken.getAuthorizationHeader();
                        }
                        return config;
                    },
                    responseError: function responseError(rejection) {
                        // permanece igual à original
                        if (400 === rejection.status && rejection.data && ("invalid_request" === rejection.data.error || "invalid_grant" === rejection.data.error)) {
                            OAuthToken.removeToken();
                            // sobrescrevendo o evento
                            $rootScope.$emit("oauth:error", rejection);
                        }
                        // adicionei uma verificação. Pois o symfony retorna status 401 quando o token vence junto com um error "invalid_grant"
                        if (401 === rejection.status && rejection.data && ("invalid_token" === rejection.data.error || "invalid_grant" === rejection.data.error) || rejection.headers("www-authenticate") && 0 === rejection.headers("www-authenticate").indexOf("Bearer")) {
                            // sobrescrevendo o evento
                            $rootScope.$emit("oauth:error", rejection);
                        }
                        return $q.reject(rejection);
                    }

                };
            });
            // adiciono o interceptador
            $httpProvider.interceptors.push('oauth2HttpInterceptor');
        }])
    .run(['$rootScope', '$window', 'OAuth', 'CONSTANTS', function ($rootScope, $window, OAuth, CONSTANTS) {
        $rootScope.$on('oauth:error', function (event, rejection) {

                // Refresh token when a `invalid_token` error occurs.
                if ('invalid_token' === rejection.data.error || 'invalid_grant' === rejection.data.error && 401 === rejection.status) {
                    return OAuth.getRefreshToken();
                } else if ('invalid_grant' === rejection.data.error) {
                    // Ignore `invalid_grant` error - should be catched on `LoginController`.
                    return;
                }
                // Redirect to `/login` with the `error_reason`.
                return $window.location.href = CONSTANTS.auth.grantPath + '?error_reason=' + rejection.data.error;
            }
        );
    }]);