<?php
    function bbq_get_salesforce_data( $endpoint = false, $request_type = 'get', $action = false, $post_data = [] ) {

        $endpoint = 'x';
        if ( false != $endpoint ) {
            $api_info = [
                'user'      => 'info@berryplasman.com',
                'pass'      => 'MP4@EP*8ZzU_-WGjmyvXdQAN',
                'token_url' => 'https://login.salesforce.com/services/oauth2/token',
            ];
            $oauth_array = get_oauth_token_reflex( $api_info );
            echo '<pre>'; var_dump($oauth_array); echo '</pre>'; exit;
            $curl_url    = $api_info[ 'api_url' ] . $endpoint;

            if ( isset( $oauth_array[ 'access_token' ] ) ) {
                $authentication = array(
                    'Authorization: Bearer ' . $oauth_array[ 'access_token' ],
                );

                $curl = curl_init();
                curl_setopt_array( $curl, array(
                    CURLOPT_URL            => $curl_url,
                    CURLOPT_HTTPHEADER     => $authentication,
                    CURLOPT_CUSTOMREQUEST  => strtoupper( $request_type ),
                    CURLOPT_RETURNTRANSFER => true,
                ) );

                if ( 'post' == $request_type ) {
                    curl_setopt( $curl, CURLOPT_POST, true );
                    if ( ! empty( $post_data ) ) {
                        // available parameters @ http://92.64.232.89:7000/swagger/ui/index#!/SaleArticle/SaleArticle_GetSaleArticles
                        curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $post_data ) );
                    }
                }

                $curl_response = curl_exec( $curl );
                $curl_array = json_decode( $curl_response, true );

            }
        }
    }

    bbq_get_salesforce_data();

    function get_oauth_token_reflex( $api_info ) {
        // echo '<pre>'; var_dump($api_info); echo '</pre>'; exit;
        $authorization = base64_encode( ':' );
        $token_headers = array(
            "Authorization: Basic {$authorization}",
            'Content-Type: application/x-www-form-urlencoded',
        );

        if ( isset( $api_info[ 'user' ] ) && isset( $api_info[ 'pass' ] ) && isset( $api_info[ 'token_url' ] ) ) {
            $token_content = 'grant_type=password&username=' . $api_info[ 'user' ] . '&password=' . $api_info[ 'pass' ];

            $curl = curl_init();
            curl_setopt_array( $curl, array(
                CURLOPT_URL            => $api_info[ 'token_url' ],
                CURLOPT_HTTPHEADER     => $token_headers,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $token_content,
                CURLOPT_RETURNTRANSFER => true,
            ) );

            $curl_response = curl_exec( $curl );
            curl_close ($curl);

            if ( $curl_response ) {
                $response = json_decode( $curl_response, true );
                return $response;
            }
        }

        return false;
    }
