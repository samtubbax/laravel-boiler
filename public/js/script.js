jsApp = {
    init: function () {
        jsApp.initFacebook();
        jsApp.initGoogle();
    },

    initGoogle: function () {
        $('.googleLogin').on('click', function (e) {
            e.preventDefault();

            gapi.auth.signIn({
                callback: jsApp.googleLogin
            });
        });
    },

    googleLogin: function (authResult) {
        if (authResult['status']['signed_in']) {
            window.location = '/users/google?code=' + authResult.code + '&state=' + gData.state;
        } else {
            console.log('Sign-in state: ' + authResult['error']);
        }
    },

    initFacebook: function () {
        $('.facebookLogin').on('click', function (e) {
            e.preventDefault();
            $target = $(this);

            var redirectURL = $target.data('url');
            if(typeof($target.data('url')) == 'undefined')
            {
                redirectURL = '/';
            }

            FB.login(function(response) {
                if (response.authResponse) {
                    window.location = '/users/facebook?redirect=' + redirectURL;
                }
            }, {scope: 'email,user_friends'})
        });

        $('.sendMessage').on('click', function (e) {
            e.preventDefault();

            FB.ui({
                method: 'send',
                link: $(this).data('url'),
                to: $(this).data('id')
            });
        })
    },
}
$(jsApp.init);