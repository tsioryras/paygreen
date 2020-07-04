<?php

namespace Paygreen;

class PaygreenOAuthHelper
{
    private static $paygreenInstance = null;

    /**
     * @param PaygreenAPI|null $paygreenInstance
     */
    public static function setPaygreenInstance($paygreenInstance = null): void
    {
        self::$paygreenInstance = $paygreenInstance ?? new PaygreenAPI();
    }

    /**
     * @return PaygreenAPI|null
     */
    public static function getPaygreenInstance()
    {
        return self::$paygreenInstance;
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
        return self::getPaygreenInstance()->getHost() . '/auth/' . $action;
    }

}