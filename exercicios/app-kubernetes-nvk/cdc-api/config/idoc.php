<?php

return [
    /*
    |--------------------------------------------------------------------------
    | iDoc Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where the documentation will be accessible from.
    | If the setting is null, iDoc will reside under the same domain as the
    | application. otherwise, this value will be used as the subdomain.
    |
     */

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | iDoc Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where the documentation will be accessible from.
    | Feel free to change this path to anything you like.
    |
     */

    'path' => 'idoc',

    /*
    |--------------------------------------------------------------------------
    | iDoc Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to the iDoc route, giving you
    | the chance to add your own or change the idoc middleware.
    |
     */

    'middleware' => [
        'web',
    ],

    /*
    |--------------------------------------------------------------------------
    | iDoc logo url
    |--------------------------------------------------------------------------
    |
    | This is the logo configuration for the documentation. The logo expects
    | an absolute or relative url to a logo image while the color will
    | fill any space left depending on the log size.
    |
     */

    'logo' => 'https://www.penze.com.br/novosite/wp-content/uploads/2018/09/banner-transforma%C3%A7%C3%A3o-digital-1024x805.png',

    'color' => '#fcfcfc',

    /*
    |--------------------------------------------------------------------------
    | iDoc Title / Description
    |--------------------------------------------------------------------------
    |
    | This is the title and description that will be visible on the
    | documentation.
    |
     */

    'title' => 'CDC Digital API Reference | Penze Diferente',

    'description' => 'CDC Digital specification and documentation, by Penze Diferente.',

    'version' => 'v1',

    /*
    |--------------------------------------------------------------------------
    | iDoc collection/output path
    |--------------------------------------------------------------------------
    |
    | The output path for the generated Open API 3.0 collection file.
    | This path is relative to the public path.
    |
    | In order To disable the  the open-api-3 download button
    | on the  documentation, the `hide_download_button`
    | option should be set to true.
    |
     */

    'output' => '',

    'hide_download_button' => false,

    /*
    |--------------------------------------------------------------------------
    | iDoc router
    |--------------------------------------------------------------------------
    |
    | The application's router.  (Laravel or Dingo).
    |
     */

    'router' => 'laravel',

    /*
    |--------------------------------------------------------------------------
    | iDoc servers
    |--------------------------------------------------------------------------
    |
    | The servers that should be added to the documentation. Each should have
    | a server hostname (and path if neccessary) and a discription of the
    | host. eg: one for test and another for production.
    |
     */

    'servers' => [
        [
            'url' => 'http:://api.casadiconti.com.br'/*config('app.url')*/,
            'description' => 'Production Server',
        ],
        [
            'url' => 'http://sandbox.casadiconti.com.br',
            'description' => 'Test Server',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | iDoc languages tab.
    |--------------------------------------------------------------------------
    | Each tab is used to generate a request template for a given language.
    | New languages can be added and the existing ones modified after.
    |
    | You can add or edit new languages tabs by publishing the view files
    | and editing them or adding custom view files to:
    |
    |    'resources/views/vendor/idoc/languages/*.blade.php',
    |
    | where * is the name of the language you wish to add.
    |
    | Don't forget to add here too when done.
    |
     */

    'language-tabs' => [
        'bash' => 'Bash',
        'javascript' => 'Javascript',
        'php' => 'PHP',
    ],

    /*
    |--------------------------------------------------------------------------
    | iDoc routes: The routes for which documentation should be generated.
    |--------------------------------------------------------------------------
    | Each group contains rules defining which routes should be included
    | ('match', 'include' and 'exclude' sections) and rules which
    | should be applied to them ('apply' section).
    |
     */

    'routes' => [
        [
            /*
             * Specify conditions to determine what routes will be parsed in this group.
             * A route must fulfill ALL conditions to pass.
             */
            'match' => [

                /*
                 * Match only routes whose domains match this pattern (use * as a wildcard to match any characters).
                 */
                'domains' => [
                    '*',
                    // 'domain1.*',
                ],

                /*
                 * Match only routes whose paths match this pattern (use * as a wildcard to match any characters).
                 */
                'prefixes' => [
                    'api/*',
                ],

                /*
                 * Match only routes registered under this version. This option is ignored for Laravel router.
                 * Note that wildcards are not supported.
                 */
                'versions' => [
                    'v1',
                ],
            ],

            /*
             * Include these routes when generating documentation,
             * even if they did not match the rules above.
             * Note that the route must be referenced by name here (wildcards are supported).
             */
            'include' => [
                // 'users.index', 'healthcheck*'
                'login.oauth'
            ],

            /*
             * Exclude these routes when generating documentation,
             * even if they matched the rules above.
             * Note that the route must be referenced by name here (wildcards are supported).
             */
            'exclude' => [
                // 'users.create', 'admin.*'
            ],

            /*
             * Specify rules to be applied to all the routes in this group when generating documentation
             */
            'apply' => [
                /*
                 * Specify headers to be added to the example requests
                 */
                'headers' => [
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYxZDQ1YmJiMGJkYzEyYzYzMDEyM2Q1Y2E2ZDUwZDFkNTc0NTA5NDhhNDIxNDZhYzg3YWZiMTI3MjY3OTVjOTQxMzdlM2ZmM2EwMWVjYmM1In0.eyJhdWQiOiIxIiwianRpIjoiNjFkNDViYmIwYmRjMTJjNjMwMTIzZDVjYTZkNTBkMWQ1NzQ1MDk0OGE0MjE0NmFjODdhZmIxMjcyNjc5NWM5NDEzN2UzZmYzYTAxZWNiYzUiLCJpYXQiOjE1NjU2MzI2ODEsIm5iZiI6MTU2NTYzMjY4MSwiZXhwIjoxNTk3MjU1MDgxLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.mozGdYtcs6BjLBQQ5flDS6JW6uu5pUjsuyYXA7yaMpuVPbsiTHWfmaZEMVjWcWjjAgB8gWXsMmSA1DGbffZYxXNR0V7ih0FnRPUhJHURuNBB-Nx44cEgISf-yYicAWhu7W7wcV-0iaeW-7DWDKu6zQ55xkZbBgLKsj6LF3rS_zHHPuBT2CeymF50lp4Ha5WUWDSytAgFgbjX2l-OqUYOmnzFJ4Utw-MUF_YDLht-fwiAZ3tXflSfafp8_S-KXQ93039J6vRzn2xFiFXe29S_g4-n0CZqlvJgZVwSBNuEDo8-DxnTNPLn_pYDB5P3etJkCYj_skQ1lChgZN7LYuGu6z6vAiGkeffU35uCOrToja_GQv4BDNjGB2eJUXSPHEGcakmrGUXPEuZlvKQMxuqVVHDXZImZ5EzI3tHfqrcrFDvNuy5iHNu9Rnuk-QJtTR7D1dQjFneJh4NHOzS9OefOdFBPmLH6cwgGyp8zdSp8POyXRK75Mgpe4nW59CSUgqaHhIPszLbLKn_fjbI63brY-E3ZIhWq89k-J7VmDB1SQTQPHPYNVllmY1l7sdlrsJJLWYcDTL176REXbUMyzEBI_NesYKHu73sG3xzY5aBaYtitrana3c7-hqazdvumpEjD1u7IHLhooOov7HxjUprRzoJh8xETVQwALC41lkLVsLg',
                    // 'Api-Version' => 'v2',
                ],

                /*
                 * If no @response or @transformer declaratons are found for the route,
                 * we'll try to get a sample response by attempting an API call.
                 * Configure the settings for the API call here,
                 */
                'response_calls' => [
                    /*
                     * API calls will be made only for routes in this group matching these HTTP methods (GET, POST, etc).
                     * List the methods here or use '*' to mean all methods. Leave empty to disable API calls.
                     */
                    'methods' => ['*'],

                    /*
                     * For URLs which have parameters (/users/{user}, /orders/{id?}),
                     * specify what values the parameters should be replaced with.
                     * Note that you must specify the full parameter, including curly brackets and question marks if any.
                     */
                    'bindings' => [
                        // '{user}' => 1
                    ],

                    /*
                     * Environment variables which should be set for the API call.
                     * This is a good place to ensure that notifications, emails
                     * and other external services are not triggered during the documentation API calls
                     */
                    'env' => [
                        'APP_ENV' => 'documentation',
                        'APP_DEBUG' => false,
                        // 'env_var' => 'value',
                    ],

                    /*
                     * Headers which should be sent with the API call.
                     */
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        // 'key' => 'value',
                    ],

                    /*
                     * Query parameters which should be sent with the API call.
                     */
                    'query' => [
                        // 'key' => 'value',
                    ],

                    /*
                     * Body parameters which should be sent with the API call.
                     */
                    'body' => [
                        // 'key' => 'value',
                    ],
                ],
            ],
        ],
    ],
];
