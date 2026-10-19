
<?php

require_once __DIR__ . "/../classes/ProductClass.php";

class ProductController
{
    private $product;

    public function __construct()
    {
        $this->product = new ProductClass();
    }


    /* =========================
       BRAND
    ========================= */

    public function addBrand($name)
    {
        return $this->product->addBrand($name);
    }

    public function getAllBrands()
    {
        return $this->product->getAllBrands();
    }

    public function getBrandById($id)
    {
        return $this->product->getBrandById($id);
    }

    public function updateBrand($id, $name)
    {
        return $this->product->updateBrand($id, $name);
    }


    /* =========================
       CATEGORY
    ========================= */

    public function addCategory($name)
    {
        return $this->product->addCategory($name);
    }

    public function getAllCategories()
    {
        return $this->product->getAllCategories();
    }

    public function getCategoryById($id)
    {
        return $this->product->getCategoryById($id);
    }

    public function updateCategory($id, $name)
    {
        return $this->product->updateCategory($id, $name);
    }


    /* =========================
       ADD PRODUCT
    ========================= */

    public function addProduct(
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $image_filename,
        $keywords
    ) {
        return $this->product->addProduct(
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );
    }


    /* =========================
       UPDATE PRODUCT
    ========================= */

    public function updateProduct(
        $id,
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $image_filename,
        $keywords
    ) {
        return $this->product->updateProduct(
            $id,
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );
    }


    /* =========================
       GET ONE PRODUCT
    ========================= */

    public function getProductById($id)
    {
        return $this->product->getProductById($id);
    }


    /* =========================
       GET ALL PRODUCTS
    ========================= */

    public function getAllProducts()
    {
        return $this->product->getAllProducts();
    }


    /* =========================
       HOMEPAGE PRODUCTS
    ========================= */

    public function getFeaturedProducts($limit = 6)
    {
        return $this->product->getFeaturedProducts($limit);
    }


    /* =========================
       FILTER BY CATEGORY
    ========================= */

    public function getProductsByCategory($category_id)
    {
        return $this->product->getProductsByCategory(
            (int) $category_id
        );
    }


    /* =========================
       FILTER BY BRAND
    ========================= */

    public function getProductsByBrand($brand_id)
    {
        return $this->product->getProductsByBrand(
            (int) $brand_id
        );
    }


    /* =========================
       SEARCH PRODUCTS
    ========================= */

    public function searchProducts($keyword)
    {
        return $this->product->searchProducts(
            trim($keyword)
        );
    }


    /* =========================
       ADD TO CART
    ========================= */

    public function addToCart($productId, $ipAddress, $customerId)
    {
        return $this->product->addToCart(
            (int) $productId,
            $ipAddress,
            (int) $customerId
        );
    }
}

?>
