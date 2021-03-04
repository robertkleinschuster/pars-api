<?php

return [
    'mezzio-session-cache' => [
        // Detailed in the above section; allows using a different
        // cache item pool than the global one.
        'cache_item_pool_service' => 'SessionCache',

        'filesystem_folder' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'data', 'session']),

        'memcached_host' => 'localhost',
        'memcached_port' => 11211,

        'redis_host' => 'localhost',
        'redis_port' => 6379,

        // The name of the session cookie. This name must comply with
        // the syntax outlined in https://tools.ietf.org/html/rfc6265.html
        'cookie_name' => 'PAPISID',

        // The (sub)domain that the cookie is available to. Setting this
        // to a subdomain (such as 'www.example.com') will make the cookie
        // available to that subdomain and all other sub-domains of it
        // (i.e. w2.www.example.com). To make the cookie available to the
        // whole domain (including all subdomains of it), simply set the
        // value to the domain name ('example.com', in this case).
        // Leave this null to use browser default (current hostname).
        'cookie_domain' => null,

        // The path prefix of the cookie domain to which it applies.
        'cookie_path' => '/',

        // Indicates that the cookie should only be transmitted over a
        // secure HTTPS connection from the client. When set to TRUE, the
        // cookie will only be set if a secure connection exists.
        'cookie_secure' => false,

        // When TRUE the cookie will be made accessible only through the
        // HTTP protocol. This means that the cookie won't be accessible
        // by scripting languages, such as JavaScript.
        'cookie_http_only' => false,

        // Available since 1.4.0
        //
        // Asserts that a cookie must not be sent with cross-origin requests,
        // providing some protection against cross-site request forgery attacks (CSRF).
        //
        // Allowed values:
        // - Strict: The browser sends the cookie only for same-site requests
        //   (that is, requests originating from the same site that set the cookie).
        //   If the request originated from a different URL than the current one,
        //   no cookies with the SameSite=Strict attribute are sent.
        // - Lax: The cookie is withheld on cross-site subrequests, such as calls
        //   to load images or frames, but is sent when a user navigates to the URL
        //   from an external site, such as by following a link.
        // - None: The browser sends the cookie with both cross-site and same-site
        //   requests.
        'cookie_same_site' => 'Lax',

        // Governs the various cache control headers emitted when
        // a session cookie is provided to the client. Value may be one
        // of "nocache", "public", "private", or "private_no_expire";
        // semantics are the same as outlined in
        // http://php.net/session_cache_limiter
        'cache_limiter' => 'nocache',

        // When the cache and the cookie should expire, in seconds. Defaults
        // to 180 minutes.
        'cache_expire' => 86400000,

        // An integer value indicating when the resource to which the session
        // applies was last modified. If not provided, it uses the last
        // modified time of, in order,
        // - the public/index.php file of the current working directory
        // - the index.php file of the current working directory
        // - the current working directory
        'last_modified' => null,

        // A boolean value indicating whether or not the session cookie
        // should persist. By default, this is disabled (false); passing
        // a boolean true value will enable the feature. When enabled, the
        // cookie will be generated with an Expires directive equal to the
        // the current time plus the cache_expire value as noted above.
        //
        // As of 1.2.0, developers may define the session TTL by calling the
        // session instance's `persistSessionFor(int $duration)` method. When
        // that method has been called, the engine will use that value even if
        // the below flag is toggled off.
        'persistent' => false,
    ],
];
