
<?php

require_once __DIR__ . "/../core/db_class.php";

class ProductClass extends Database
{
    /* =========================
       BRAND
    ========================= */

    public function addBrand($name)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO brands (brand_name) VALUES (?)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $name);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getAllBrands()
    {
        $result = $this->conn->query(
            "SELECT * FROM brands ORDER BY brand_name ASC"
        );

        $brands = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }
        }

        return $brands;
    }

    public function getBrandById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM brands WHERE brand_id = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $brand = $result->fetch_assoc();

        $stmt->close();

        return $brand ?: null;
    }

    public function updateBrand($id, $name)
    {
        $stmt = $this->conn->prepare(
            "UPDATE brands SET brand_name = ? WHERE brand_id = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("si", $name, $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }


    /* =========================
       CATEGORY
    ========================= */

    public function addCategory($name)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO categories (cat_name) VALUES (?)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $name);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getAllCategories()
    {
        $result = $this->conn->query(
            "SELECT * FROM categories ORDER BY cat_name ASC"
        );

        $categories = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }

        return $categories;
    }

    public function getCategoryById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM categories WHERE cat_id = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $category = $result->fetch_assoc();

        $stmt->close();

        return $category ?: null;
    }

    public function updateCategory($id, $name)
    {
        $stmt = $this->conn->prepare(
            "UPDATE categories SET cat_name = ? WHERE cat_id = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("si", $name, $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
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
        $stmt = $this->conn->prepare(
            "INSERT INTO products
            (
                product_cat,
                product_brand,
                product_title,
                product_price,
                product_desc,
                product_image,
                product_keywords
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "iisdsss",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
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
        $stmt = $this->conn->prepare(
            "UPDATE products
             SET product_cat = ?,
                 product_brand = ?,
                 product_title = ?,
                 product_price = ?,
                 product_desc = ?,
                 product_image = ?,
                 product_keywords = ?
             WHERE product_id = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "iisdsssi",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords,
            $id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }


    /* =========================
       GET ONE PRODUCT
    ========================= */

    public function getProductById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT
                p.*,
                c.cat_name,
                b.brand_name
             FROM products p
             LEFT JOIN categories c
                ON p.product_cat = c.cat_id
             LEFT JOIN brands b
                ON p.product_brand = b.brand_id
             WHERE p.product_id = ?"
        );

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        $stmt->close();

        return $product ?: null;
    }


    /* =========================
       GET ALL PRODUCTS
    ========================= */

    public function getAllProducts()
    {
        $result = $this->conn->query(
            "SELECT
                p.*,
                c.cat_name,
                b.brand_name
             FROM products p
             LEFT JOIN categories c
                ON p.product_cat = c.cat_id
             LEFT JOIN brands b
                ON p.product_brand = b.brand_id
             ORDER BY p.product_id DESC"
        );

        $products = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        return $products;
    }


    /* =========================
       GET FEATURED PRODUCTS
    ========================= */

    public function getFeaturedProducts($limit = 6)
    {
        $limit = max(1, min(24, (int) $limit));

        $result = $this->conn->query(
            "SELECT
                p.*,
                c.cat_name,
                b.brand_name
             FROM products p
             LEFT JOIN categories c
                ON p.product_cat = c.cat_id
             LEFT JOIN brands b
                ON p.product_brand = b.brand_id
             ORDER BY RAND()
             LIMIT $limit"
        );

        $products = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        return $products;
    }


    /* =========================
       FILTER BY CATEGORY
    ========================= */

    public function getProductsByCategory($cat_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT
                p.*,
                c.cat_name,
                b.brand_name
             FROM products p
             LEFT JOIN categories c
                ON p.product_cat = c.cat_id
             LEFT JOIN brands b
                ON p.product_brand = b.brand_id
             WHERE p.product_cat = ?
             ORDER BY p.product_id DESC"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $cat_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();

        return $products;
    }


    /* =========================
       FILTER BY BRAND
    ========================= */

    public function getProductsByBrand($brand_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT
                p.*,
                c.cat_name,
                b.brand_name
             FROM products p
             LEFT JOIN categories c
                ON p.product_cat = c.cat_id
             LEFT JOIN brands b
                ON p.product_brand = b.brand_id
             WHERE p.product_brand = ?
             ORDER BY p.product_id DESC"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $brand_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();

        return $products;
    }


    /* =========================
       SEARCH PRODUCTS
    ========================= */

    public function searchProducts($query)
    {
        $search = "%" . trim($query) . "%";

        $stmt = $this->conn->prepare(
            "SELECT
                p.*,
                c.cat_name,
                b.brand_name
             FROM products p
             LEFT JOIN categories c
                ON p.product_cat = c.cat_id
             LEFT JOIN brands b
                ON p.product_brand = b.brand_id
             WHERE p.product_title LIKE ?
                OR p.product_keywords LIKE ?
             ORDER BY p.product_id DESC"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();

        return $products;
    }


    /* =========================
       ADD TO CART
    ========================= */

    public function addToCart($productId, $ipAddress, $customerId)
    {
        $productId = filter_var($productId, FILTER_VALIDATE_INT);

        if ($productId === false || $productId <= 0) {
            return false;
        }

        // Use NULL for guests instead of customer ID 0.
        $customerId = (
            $customerId !== null &&
            filter_var($customerId, FILTER_VALIDATE_INT) !== false &&
            (int) $customerId > 0
        ) ? (int) $customerId : null;

        // Verify that the product exists.
        $checkProduct = $this->conn->prepare(
            "SELECT product_id FROM products WHERE product_id = ?"
        );

        if (!$checkProduct) {
            return false;
        }

        $checkProduct->bind_param("i", $productId);
        $checkProduct->execute();

        $productResult = $checkProduct->get_result();
        $productExists = $productResult->num_rows > 0;
        $checkProduct->close();

        if (!$productExists) {
            return false;
        }

        // Find an existing cart item for this product and customer.
        if ($customerId === null) {
            $checkCart = $this->conn->prepare(
                "SELECT qty FROM cart
                 WHERE p_id = ? AND ip_add = ? AND c_id IS NULL
                 LIMIT 1"
            );

            if (!$checkCart) {
                return false;
            }

            $checkCart->bind_param("is", $productId, $ipAddress);
        } else {
            $checkCart = $this->conn->prepare(
                "SELECT qty FROM cart
                 WHERE p_id = ? AND ip_add = ? AND c_id = ?
                 LIMIT 1"
            );

            if (!$checkCart) {
                return false;
            }

            $checkCart->bind_param(
                "isi",
                $productId,
                $ipAddress,
                $customerId
            );
        }

        $checkCart->execute();

        $cartResult = $checkCart->get_result();
        $existingItem = $cartResult->fetch_assoc();

        $checkCart->close();

        // If the item already exists, increase its quantity.
        if ($existingItem) {
            $newQuantity = (int) $existingItem['qty'] + 1;

            if ($customerId === null) {
                $updateCart = $this->conn->prepare(
                    "UPDATE cart
                     SET qty = ?
                     WHERE p_id = ? AND ip_add = ? AND c_id IS NULL"
                );

                if (!$updateCart) {
                    return false;
                }

                $updateCart->bind_param(
                    "iis",
                    $newQuantity,
                    $productId,
                    $ipAddress
                );
            } else {
                $updateCart = $this->conn->prepare(
                    "UPDATE cart
                     SET qty = ?
                     WHERE p_id = ? AND ip_add = ? AND c_id = ?"
                );

                if (!$updateCart) {
                    return false;
                }

                $updateCart->bind_param(
                    "iisi",
                    $newQuantity,
                    $productId,
                    $ipAddress,
                    $customerId
                );
            }

            $success = $updateCart->execute();
            $updateCart->close();

            return $success;
        }

        // Otherwise, insert a new cart item.
        if ($customerId === null) {
            $insertCart = $this->conn->prepare(
                "INSERT INTO cart (p_id, ip_add, c_id, qty)
                 VALUES (?, ?, NULL, 1)"
            );

            if (!$insertCart) {
                return false;
            }

            $insertCart->bind_param("is", $productId, $ipAddress);
        } else {
            $insertCart = $this->conn->prepare(
                "INSERT INTO cart (p_id, ip_add, c_id, qty)
                 VALUES (?, ?, ?, 1)"
            );

            if (!$insertCart) {
                return false;
            }

            $insertCart->bind_param(
                "isi",
                $productId,
                $ipAddress,
                $customerId
            );
        }

        $success = $insertCart->execute();
        $insertCart->close();

        return $success;
    }
}

?>
