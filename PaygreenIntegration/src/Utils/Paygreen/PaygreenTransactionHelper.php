<?php

class PaygreenTransactionHelper
{


    public static function getUi()
    {
        return $_ENV['UI'];
    }

    public static function getCp()
    {
        return $_ENV['CP'];
    }

    public static function getHost()
    {
        return $_ENV['HOST'];
    }

    /**
     * @return PaygreenAPI|null
     */
    public static function getInstance()
    {
        return new PaygreenAPI(self::getUi(), self::getCp(), self::getHost());
    }

    /**
     * @param $pid
     * @return mixed|object
     */
    public static function validDeliveryPayment($pid)
    {
        return self::getInstance()->requestApi('delivery', ['pid' => $pid]);
    }

    /**
     * @param $data
     * @return mixed|object
     */
    public static function createCash($data)
    {
        return self::getInstance()->requestApi('create-cash', $data);
    }

    /**
     * @param $data
     * @return mixed|object
     */
    public static function createXTime($data)
    {
        return self::getInstance()->requestApi('create-xtime', $data);
    }

    /**
     * @param $data
     * @return mixed|object
     */
    public static function createSubscription($data)
    {
        return self::getInstance()->requestApi('create-subscription', $data);
    }

    public static function createTokenize($data)
    {
        return self::getInstance()->requestApi('create-tokenize', $data);
    }

    /**
     * @param $pid
     * @return mixed|object
     */
    public static function getTransactionInfo($pid)
    {
        return self::getInstance()->requestApi('get-datas', ['pid' => $pid]);
    }

    /**
     * @return mixed|object
     */
    public static function getStatusShop()
    {
        return self::getInstance()->requestApi('get-data', ['type' => 'shop']);
    }

    /**
     * @param $pid
     * @param $amount
     * @return bool|mixed|object
     */
    public static function refundOrder($pid, $amount)
    {
        if (empty($pid)) {
            return false;
        }

        $allData = ['pid' => $pid];
        if ($amount != null) {
            $allData['content'] = array('amount' => $amount * 100);
        }

        return self::getInstance()->requestApi('refund', $allData);
    }

    /**
     * @param $data
     * @return mixed|object
     */
    public static function sendFingerprintDatas($data)
    {
        $allData['content'] = $data;
        return self::getInstance()->requestApi('send-ccarbone', $allData);
    }

    /**
     * @param $activate
     * @return bool|mixed|object
     */
    public static function validateShop($activate)
    {
        if ($activate != 1 && $activate != 0) {
            return false;
        }
        $allData['content'] = array('activate' => $activate);
        return self::getInstance()->requestApi('validate-shop', $allData);
    }

    /**
     * @return bool|mixed|object
     */
    public static function validIdShop()
    {
        $valid = self::getInstance()->requestApi('are-valid-ids', null);

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
     * @return array|bool
     */
    public static function getAccountInfos()
    {
        $infosAccount = array();

        $account = self::getInstance()->requestApi('get-data', ['type' => 'account']);
        if (self::getInstance()->isContainsError($account)) {
            return $account->error;
        }
        if ($account == false) {
            return false;
        }
        $infosAccount['siret'] = $account->data->siret;

        $bank = self::getInstance()->requestApi('get-data', array('type' => 'bank'));
        if (self::getInstance()->isContainsError($bank)) {
            return $bank->error;
        }
        if ($bank == false) {
            return false;
        }

        foreach ($bank->data as $rib) {
            if ($rib->isDefault == "1") {
                $infosAccount['IBAN'] = $rib->iban;
            }
        }

        $shop = self::getInstance()->requestApi('get-data', ['type' => 'shop']);
        if (self::getInstance()->isContainsError($bank)) {
            return $shop->error;
        }
        if ($shop == false) {
            return false;
        }
        $infosAccount['url'] = $shop->data->url;
        $infosAccount['modules'] = $shop->data->modules;
        $infosAccount['solidarityType'] = $shop->data->extra->solidarityType;

        if (isset($shop->data->businessIdentifier)) {
            $infosAccount['siret'] = $shop->data->businessIdentifier;
        }

        $infosAccount['valide'] = true;

        if (empty($infosAccount['url']) && empty($infosAccount['siret']) && empty($infosAccount['IBAN'])) {
            $infosAccount['valide'] = false;
        }
        return $infosAccount;
    }

    /**
     * @param $allData
     * @return mixed|object
     */
    public static function getRoundingInfo($allData)
    {
        $transaction = self::getInstance()->requestApi('get-rounding', $allData);
        if (self::getInstance()->isContainsError($transaction)) {
            return $transaction->error;
        }
        return $transaction;
    }

    /**
     * @param $allData
     * @return mixed|object
     */
    public static function validateRounding($allData)
    {
        $validate = self::getInstance()->requestApi('validate-rounding', $allData);
        if (self::getInstance()->isContainsError($validate)) {
            return $validate->error;
        }
        return $validate;
    }

    /**
     * @param $allData
     * @return mixed|object
     */
    public static function refundRounding($allData)
    {
        $allData['content'] = array('paymentToken' => $allData['paymentToken']);
        $refund = self::getInstance()->requestApi('refund-rounding', $allData);
        if (self::getInstance()->isContainsError($refund)) {
            return $refund->error;
        }
        return $refund;
    }
}