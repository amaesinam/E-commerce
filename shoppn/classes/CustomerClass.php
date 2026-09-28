<?php

require_once __DIR__ . "/../core/db_class.php";

class Customer extends Database
{
    public function emailExists($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT customer_email FROM customer WHERE customer_email = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        $exists = ($result->num_rows > 0);

        $stmt->close();

        return $exists;
    }


    public function insertCustomer($name, $email, $pass, $country, $city, $contact, $image, $role)
    {
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO customer
            (customer_name, customer_email, customer_pass, customer_country,
             customer_city, customer_contact, customer_image, user_role)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sssssssi",
            $name,
            $email,
            $hashedPass,
            $country,
            $city,
            $contact,
            $image,
            $role
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }


    public function getCustomerByEmail($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM customer WHERE customer_email = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        $customer = $result->fetch_assoc();

        $stmt->close();

        return $customer;
    }


    public function login($email, $pass)
    {
        $customer = $this->getCustomerByEmail($email);

        if (!$customer) {
            return false;
        }

        if (password_verify($pass, $customer['customer_pass'])) {
            return $customer;
        }

        return false;
    }


    public function findByEmail($email)
    {
        return $this->getCustomerByEmail($email);
    }


    public function getAllCustomers()
    {
        $result = $this->conn->query(
            "SELECT * FROM customer ORDER BY customer_id DESC"
        );

        $customers = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }

        return $customers;
    }
}

?>