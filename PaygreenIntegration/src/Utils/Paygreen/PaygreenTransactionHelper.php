<?php

class PaygreenTransactionHelper
{
    /**
     * @return PaygreenAPI|null
     */
    public static function getInstance()
    {
        return new PaygreenAPI();
    }

    /**
     * @param $action
     * @param null $data
     * @param null $activate
     * @param null $pid
     * @param null $amount
     * @return bool|mixed|object
     */
    public static function transactionFunctions($action, $data = null, $activate = null, $pid = null, $amount = null)
    {
        switch ($action) {
            case 'refund':
                if ($pid == null) {
                    return false;
                }

                $data = ['pid' => $pid];
                if ($amount != null) {
                    $data['content'] = ['amount' => $amount * 100];
                }
                break;
            case 'validate-shop':
                if ($activate != 1 && $activate != 0) {
                    return false;
                }
                $data = ['content' => ['activate' => $activate]];
                break;
            default:
                break;
        }

        return self::getInstance()->requestApi($action, $data);
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
    public static function getAccountInfos($accountType)
    {
        $infosAccount = [];
        $accounts = ['account', 'bank', 'shp'];
        foreach ($accounts as $accountType) {
            $account = self::transactionFunctions('get-data', ['type' => $accountType]);
            if (self::getInstance()->isContainsError($account)) {
                return $account->error;
            }
            if ($account == false) {
                return false;
            }
            switch ($accountType) {
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
                    $infosAccount['siret'] = $account->data->siret;
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
        if (self::getInstance()->isContainsError($transaction)) {
            return $transaction->error;
        }
        return $transaction;
    }
}