<?php declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Controls the HTTP route that your GraphQL server responds to.
    | You may set `route` => false, to disable the default route
    | registration and take full control.
    |
    */

    'route' => [
        /*
         * The URI the endpoint responds to, e.g. mydomain.com/graphql.
         */
        'uri' => '/graphql',

        /*
         * Lighthouse creates a named route for convenient URL generation and redirects.
         */
        'name' => 'graphql',

        /*
         * Beware that middleware defined here runs before the GraphQL execution phase,
         * make sure to return spec-compliant responses in case an error is thrown.
         */
        'middleware' => [
            // Ensures the request is not vulnerable to cross-site request forgery.
            // Nuwave\Lighthouse\Http\Middleware\EnsureXHR::class,

            // Always set the `Accept: application/json` header.
            Nuwave\Lighthouse\Http\Middleware\AcceptJson::class,

            Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

            // Logs in a user if they are authenticated. In contrast to Laravel's 'auth'
            // middleware, this delegates auth and permission checks to the field level.
            Nuwave\Lighthouse\Http\Middleware\AttemptAuthentication::class,

            // Logs every incoming GraphQL query.
            // Nuwave\Lighthouse\Http\Middleware\LogGraphQLQueries::class,
        ],

        /*
         * The `prefix`, `domain` and `where` configuration options are optional.
         */
        // 'prefix' => '',
        // 'domain' => '',
        // 'where' => [],
    ],
];
