<?php

namespace Paygreen;


class PaygreenTransactionHelper
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
        return self::$paygreenInstance ?? new PaygreenAPI();
    }

    /**
     * @param string $action
     * @param mixed|null $data
     * @return bool|mixed|object
     */
    public static function transactionFunctions($action, $data = null)
    {
        switch ($action) {
            case 'refund':
                if (!isset($data['pid'])) {
                    return false;
                }

                if ($data['amount'] != null) {
                    $data['content'] = ['amount' => $data['amount'] * 100];
                }
                break;
            case 'validate-shop':
                $data = ['content' => ['activate' => $data['activate']]];
                break;
            default:
                break;
        }

        return self::getPaygreenInstance()->requestApi($action, $data);
    }

    /**
     * @return bool|mixed|object
     */
    public static function validIdShop()
    {
        $valid = self::transactionFunctions('are-valid-ids');

        if ($valid != false) {
            if (isset($valid->error)) {
                return $valid;
            }
            if ($valid->success == 0) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * @param $accountType
     * @return array|bool
     */
    public static function getAccountInfos()
    {
        $infosAccount = [];
        $accounts = ['account', 'bank', 'shop'];
        foreach ($accounts as $accountType) {
            $account = self::transactionFunctions('get-data', ['type' => $accountType]);
            if (self::getPaygreenInstance()->isContainsError($account)) {
                return $account->error;
            }
            if ($account == false) {
                return false;
            }
            switch ($accountType) {
                case 'account':
                    $infosAccount['siret'] = $account->data->siret;
                    break;
                case 'bank':
                    foreach ($accountType->data as $rib) {
                        if ($rib->isDefault == "1") {
                            $infosAccount['IBAN'] = $rib->iban;
                        }
                    }
                    break;
                case 'shop':
                    $infosAccount['url'] = $account->data->url;
                    $infosAccount['modules'] = $account->data->modules;
                    $infosAccount['solidarityType'] = $account->data->extra->solidarityType;
                    if (isset($account->data->businessIdentifier)) {
                        $infosAccount['siret'] = $account->data->businessIdentifier;
                    }
                    break;
                default:
                    break;
            }
        }
        $infosAccount['valide'] = (empty($infosAccount['url']) && empty($infosAccount['siret']) && empty($infosAccount['IBAN']));
        return $infosAccount;
    }

    /**
     * @param $action
     * @param $allData
     * @return mixed|object
     */
    public static function roundingFunction($action, $allData)
    {
        $transaction = self::transactionFunctions($action, $allData);
        if (self::getPaygreenInstance()->isContainsError($transaction)) {
            return $transaction->error;
        }
        return $transaction;
    }
}