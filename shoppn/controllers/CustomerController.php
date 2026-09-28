<?php

require_once __DIR__ . "/../classes/CustomerClass.php";

class CustomerController
{
    private $customer;

    public function __construct()
    {
        $this->customer = new Customer();
    }


    public function insert($name, $email, $pass, $country, $city, $contact, $image, $role)
    {
        return $this->customer->insertCustomer(
            $name,
            $email,
            $pass,
            $country,
            $city,
            $contact,
            $image,
            $role
        );
    }


    public function emailExists($email)
    {
        return $this->customer->emailExists($email);
    }


    public function findByEmail($email)
    {
        return $this->customer->findByEmail($email);
    }


    public function login($email, $pass)
    {
        return $this->customer->login($email, $pass);
    }


    public function selectAll()
    {
        return $this->customer->getAllCustomers();
    }
}

?>