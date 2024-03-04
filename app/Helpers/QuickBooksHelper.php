<?php

namespace App\Helpers;


use App\Models\QuickBooks\QuickbooksCredential;
use QuickBooksOnline\API\DataService\DataService;
class QuickBooksHelper
{


    /*
     * Documentation Link for the reference code and implementation
     * https://developer.intuit.com/app/developer/qbo/docs/develop/sdks-and-samples-collections/php
     * */

    /**
     * Check if the access token is expired then create new and save to the database
     * return array of the access token and refresh token
     * **/
    public static function refreshAccessToken(): array
    {
        $config = config('quickbooks');
        $quickBooksCredentials = QuickbooksCredential::where('status', 1)->first();
        if (isset($quickBooksCredentials) && $quickBooksCredentials->count() > 0) {
            $accessTokenExpiresAt = strtotime($quickBooksCredentials->access_token_expires_at);
            $currentDataTime = strtotime(date("Y/m/d H:i:s"));
            $accessToken = $quickBooksCredentials->access_token;
            $refreshToken = $quickBooksCredentials->refresh_token;
            // Check if the access token is expired or not by expire date
            if ($accessTokenExpiresAt > $currentDataTime) {
                return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
            }
            // Refreshing the access token
            $dataService = DataService::Configure([
                'auth_mode' => 'oauth2',
                'ClientID' => $config['client_id'],
                'ClientSecret' => $config['client_secret'],
                'RedirectURI' => $config['redirect_uri'],
                'accessTokenKey' => $accessToken,
                'refreshTokenKey' => $refreshToken,
                'QBORealmID' => $config['realm_id'],
                'baseUrl' => $config['base_url'],
            ]);
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $accessTokenObj = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
            $accessToken = $accessTokenObj->getAccessToken();
            $refreshToken = $accessTokenObj->getRefreshToken();
            // Save the refresh token data to the database
            $quickBooksCredentials->client_id = $accessTokenObj->getClientID();
            $quickBooksCredentials->client_secret = $accessTokenObj->getClientSecret();
            $quickBooksCredentials->realm_id = $accessTokenObj->getRealmID();
            $quickBooksCredentials->redirect_uri = $config['redirect_uri'];
            $quickBooksCredentials->base_url = $accessTokenObj->getBaseURL();
            $quickBooksCredentials->status = 1;
            $quickBooksCredentials->access_token = $accessToken;
            $quickBooksCredentials->refresh_token = $refreshToken;
            $quickBooksCredentials->access_token_expires_at = $accessTokenObj->getAccessTokenExpiresAt();
            $quickBooksCredentials->access_token_validation_period = $accessTokenObj->getAccessTokenValidationPeriodInSeconds();
            $quickBooksCredentials->refresh_token_expires_at = $accessTokenObj->getRefreshTokenExpiresAt();
            $quickBooksCredentials->refresh_token_validation_period = $accessTokenObj->getRefreshTokenValidationPeriodInSeconds();
            $quickBooksCredentials->save();
        } else {
            $accessToken = $config['access_token'];
            $refreshToken = $config['refresh_token'];
            QuickbooksCredential::create([
                'client_id' => $config['access_token'],
                'client_secret' => $config['access_token'],
                'realm_id' => $config['access_token'],
                'redirect_uri' => $config['redirect_uri'],
                'base_url' => $config['access_token'],
                'status' => 1,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'access_token_expires_at' => date("Y/m/d H:i:s"),
                'access_token_validation_period' => '3600',
                'refresh_token_expires_at' => date("Y/m/d H:i:s"),
                'refresh_token_validation_period' => '16340',
            ]);
        }
        return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
    }
}
