<?php


namespace Paygreen;

class PaygreenOAuth
{
    private $paygreenApiInstance = null;
    private $name;
    private $email;
    private $ipAddress;
    private $phone;

    /**
     * PaygreenOAuth constructor.
     * @param string|null $name
     * @param string|null $email
     * @param PaygreenAPI|null $paygreenApiInstance
     * @param string|null $ipAddress
     * @param string|null $phone
     */
    public function __construct($name = null, $email = null, $ipAddress = null, $phone = null, $paygreenApiInstance = null)
    {
        $this->name = $name ?? $name;
        $this->email = $email ?? $email;
        $this->ipAddress = $ipAddress ?? $_SERVER['ADDR'];
        $this->phone = $phone;
        $this->paygreenApiInstance = $paygreenApiInstance ?? new PaygreenAPI();
    }

    /**
     * @return PaygreenAPI|null
     */
    public function getPaygreenApiInstance()
    {
        return $this->paygreenApiInstance;
    }

    /**
     * @param PaygreenAPI|null $paygreenApiInstance
     */
    public function setPaygreenApiInstance($paygreenApiInstance = null): void
    {
        $this->paygreenApiInstance = $paygreenApiInstance ?? new PaygreenAPI();
    }

    /**
     * @return string|mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|mixed $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|mixed|mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|mixed|null
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }


    /**
     * @param string|null $ipAddress
     */
    public function setIpAddress(?string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

}