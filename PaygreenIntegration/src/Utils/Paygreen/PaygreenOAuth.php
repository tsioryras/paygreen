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
     * @param string $name
     * @param string $email
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
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
     * @return mixed|null
     */
    public function getIpAddress(): ?mixed
    {
        return $this->ipAddress;
    }


    /**
     * @param mixed|null $ipAddress
     */
    public function setIpAddress(?mixed $ipAddress): void
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
     * @param null $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

}