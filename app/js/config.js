define({
    environment: 'development',

    i18next : {
        resGetPath: 'locales/__lng__.json',
        useCookie: false,
        fallbackLng: 'en',
        getAsync: false // Synchronous loading in order to avoid uninitialized errors.
    },
    // TODO Load dynamically from server ?
    // Idea : if this option is a simple string, consider it as the URL for loading configuration from a PHP script
    // Otherwise take the array as it.
    langAvailable : [
        {
            "langcode": "en",
            "fullname":"English",
            "flagcode":"gb",
        },
        {
            "langcode": "fr",
            "fullname":"Français",
            "flagcode":"fr",
        },
        {
            "langcode": "es",
            "fullname":"Español",
            "flagcode":"es",
        },
    ],

    // Choose the starting language or enable autodetection if null.
    defaultLanguage : null,
    //defaultLanguage : "en",

    // Stores user data and proposes them the next time.
    enableRememberMe: true,

    api: {
        url: '/api',

        // TODO Force HTTPS option
        // alwaysUseHTTPS: true,

        // Authentication header name
        authHeaderName: 'X-Auth-Token',

        // Turn on `emulateHTTP` to support legacy HTTP servers. Setting this option
        // will fake `"PATCH"`, `"PUT"` and `"DELETE"` requests via the `_method` parameter and
        // set a `X-Http-Method-Override` header.
        emulateHTTP : false,

        // Turn on `emulateJSON` to support legacy servers that can't deal with direct
        // `application/json` requests ... will encode the body as
        // `application/x-www-form-urlencoded` instead and will send the model in a
        // form param named `model`.
        emulateJSON : false,
    },
});