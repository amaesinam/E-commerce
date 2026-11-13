
<?php

require_once __DIR__ . "/../classes/CartClass.php";

class CartController
{
    private $cart;

    public function __construct()
    {
        $this->cart = new CartClass();
    }

    public function isInCart($product_id, $ip)
    {
        return $this->cart->isInCart($product_id, $ip);
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        return $this->cart->addToCart($product_id, $qty, $ip, $customer_id);
    }

    public function getCartItems($ip, $customer_id = null)
    {
        return $this->cart->getCartItems($ip, $customer_id);
    }

    public function getCartCount($ip)
    {
        return $this->cart->getCartCount($ip);
    }

    public function getCartTotal($ip, $customer_id = null)
    {
        return $this->cart->getCartTotal($ip, $customer_id);
    }

    public function removeFromCart($product_id, $ip, $customer_id)
    {
        return $this->cart->removeFromCart($product_id, $ip, $customer_id);
    }

    public function updateQty($product_id, $new_qty, $ip, $customer_id)
    {
        return $this->cart->updateQty($product_id, $new_qty, $ip, $customer_id);
    }

    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        return $this->cart->processPayment($customer_id, $ip, $reference, $amount);
    }

    public function getOrderById($order_id, $customer_id)
    {
        return $this->cart->getOrderById($order_id, $customer_id);
    }
}
