<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            'credentials' => [
                "type" => "service_account",
                "project_id" => "osama-consultant",
                "private_key_id" => "5379418c998958d5c6ea7187759d7d7373e83432",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCVU2SwmYztWiTq\n2D08QkvWm0TYtUyBoZM4Tn+zmb5dHHIQZkQLx8jBD6qB/SeJcValhXD5dC9o7WuY\nNxFtOkOA+lLS8ob71a+lbKlpuB3EMPs6RMy7nYvma4rvghxhEegXvR9ej0W6euvK\nuvyI2YLwwYQ6NdEjer/v7ecZt7epS0nWdi3XqMnJPBpz3JZUw0GTP75fAcj5uO6J\nXB7sYQIo7c0dDf618lBU6GfzrC1mdAu3viRRe6CgV5kp0bYrSpHhLRIl6WWthjmF\n4/61EQrkBXU0c+Xuu/SZQ7c/+GKExMTbe+APuhYg6XQOZHjYVjd5A95UJuwcFGar\nXOI3Zp2/AgMBAAECggEACDKx3ngvooBGcpY4lmxVWYzgtL0PXDaQrJCythuEtfvv\nzzKuHa90OBUyGTzaxNLyHNg8oZVdXPdb5tlvHX4LYrNbhLVH79+FayGSE3nGOJUv\n1b0dOFen7JiO4iNUh4fCJDt/02MuQtSoyVKGtsSqSal2JvP5bWc5zmRTQbgyXxrr\njFOH82992oWE+NCm5mziLNhgM0ee4hpCOfUVN1QodwOm8RxeLjEbbf6UJ2bGthfm\nTDgQXWZwobxElhfDDGy3lxawImg9nG6TFVipdr79t82M75u67gm/KbQJWLWQmSch\nfl7Srz9OatTglNdRo8bj6mzpeiVDTbwLIIhilqVNoQKBgQDOyECFZSawnRZbqH3C\nL74272xBkoZ3vFHKIJuGj1X+E5xhA9pdRgwF3cqPlnxwrGKvAMOtLWbIAKaHwZTJ\nQEXyIiHE0777SSmrK/Vs/OUvCOn43yXS6T2fb7h10bYvJTOF8UrvQ40/sGlcVexx\nS81/eyYeg9yuifkFIwn3RG4EyQKBgQC43i1igjN00EPy/sSYKLwj/SbxpxG329hk\nAKMOKNzszgRMT5whU31xF8YtUbx3FGK7DVRbtwV5skTlaREoYv6lc78549sCm0bR\nhevOzwypbLvOgDuJbr+QQaZcRce6gip1TzPal/ODnDyvAcLYgvZQBKNFN4xOt9z9\n4XBvyP/6RwKBgDMpWsVmdH8oLrr44jykgWSoMlm6/igr4PSowrI1TqpxgbSDNojz\nZtAgwxhpvFpIXJY+EywK2q5LcuiN3dk5XNT+2uoPFeaogHXIh42yuKW9h946n+oi\nKUwEYVhNTc02YBwYyliOlykV5cuhusGBZtPOzWypZXWNz54UwEd8l9f5AoGAEqDw\n2dhc7wx9jGL31I1mSAoefoNjWa+hopId3DNp78Li0/3BseoD3f2TTsXJxAYd3NN/\nCK7sslKwdp2byQIQvwNm5aJ8U0rqW/quxGxAzmHSmwB4/2RVkWfMJ8gwVC8BsEiG\n5SQiucRoqQn4ZHDt+So+eZ8bWGNWtY6eEeWjRckCgYAaV1FLJVZj7EWlNHI1XUJT\nsOCkO7qew6Fe4HRdeOeCQSxCWKftjx7pC+UqRkjSg1LLUudx6isE8Wxum+zc8qWK\nioIllGw14/xl1XLb5Fgw+UJjnvW3d1bW/Dhgew6XZiT7GAxSkpA2uuG2GFylG7P4\nFCTeCvKfCfwGi4oQlHiK0g==\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-d8w7a@osama-consultant.iam.gserviceaccount.com",
                "client_id" => "104462190131216537384",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-d8w7a%40osama-consultant.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
