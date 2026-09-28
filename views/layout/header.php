```php
<?php
require_once __DIR__ . "/../../core/core.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn</title>
</head>

<body>

<header>

    <h1>Shoppn</h1>

    <nav>
        <?php if (is_logged_in()): ?>

            <a href="/shoppn/index.php">
                Welcome <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
            </a>

            |

            <a href="/shoppn/views/account/index.php">
                My Account
            </a>

            |

            <a href="/shoppn/actions/logout_action.php">
                Logout
            </a>

        <?php else: ?>

            <a href="/shoppn/views/register.php">Register</a>

            |

            <a href="/shoppn/views/login.php">Login</a>

        <?php endif; ?>
    </nav>

    <br>

    <form action="#" method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search products..."
        >
        <button type="submit">Search</button>
    </form>

</header>

<hr>
```
