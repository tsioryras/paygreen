<?php

namespace Paygreen;

class PaygreenOAuthHelper
{
    private static $paygreenOAuthInstance = null;

    /**
     * @param PaygreenOAuth|null $paygreenOAuthInstance
     */
    public static function setPaygreenInstance($paygreenOAuthInstance = null): void
    {
        self::$paygreenOAuthInstance = $paygreenOAuthInstance ?? new PaygreenOAuth();
    }

    /**
     * @return PaygreenOAuth|null
     */
    public static function getPaygreenOAuthInstance()
    {
        return self::$paygreenOAuthInstance;
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $ipAddress
     * @return bool|mixed|object
     */
    public static function getOAuthServerAccess($email, $name, $ipAddress)
    {
        $subParam = [
            "ipAddress" => $ipAddress,
            "email" => $email,
            "name" => $name
        ];
        $datas['content'] = $subParam;

        return PaygreenActionHelper::actionFunctions('oAuth-access', $datas);
    }

    /**
     * @param string $action
     * @return string
     */
    public static function getEndpoint($action)
    {
        $action = $action == 'declare' ? '' : $action;
        return self::getPaygreenOAuthInstance()->getPaygreenApiInstance()->getHost() . '/auth/' . $action;
    }

}