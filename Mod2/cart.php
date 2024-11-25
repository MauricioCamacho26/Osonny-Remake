<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
    $menu_id = $_POST['menu_id'];
    $menu_name = $_POST['menu_name'];
    $menu_price = $_POST['menu_price'];
    $menu_image = $_POST['menu_image'];
    
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $menu_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $menu_id,
            'name' => $menu_name,
            'price' => $menu_price,
            'image' => $menu_image,
            'quantity' => 1
        ];
    }
    header("Location: cart.php");
    exit;
}

if (isset($_POST['remove_from_cart'])) {
    $menu_id = $_POST['menu_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $menu_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']); 
    header("Location: cart.php");
    exit;
}

if (isset($_POST['update_quantity'])) {
    $menu_id = $_POST['menu_id'];
    $new_quantity = (int)$_POST['quantity'];
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $menu_id) {
            $item['quantity'] = max(1, $new_quantity); 
            break;
        }
    }
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="cssM2/cart.css">
</head>
<body>
    <div class="container">
        <h1>Carrito de Compras</h1>
        <a href="javascript:history.back()" class="back-button">← Regresar</a>
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="cart-items">
                <?php $total = 0; ?>
                <?php $comision_total = 0; ?>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-image">
                        <div class="cart-info">
                            <h3><?php echo $item['name']; ?></h3>
                            <p>Precio: MX$<?php echo number_format($item['price'], 2); ?></p>
                            <form method="post" action="cart.php" class="quantity-form">
                                <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                <label for="quantity-<?php echo $item['id']; ?>">Cantidad:</label>
                                <input type="number" id="quantity-<?php echo $item['id']; ?>" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                <button type="submit" name="update_quantity">Actualizar</button>
                            </form>
                            <?php
                            $subtotal = $item['price'] * $item['quantity'];
                            $comision = $subtotal * 0.016;
                            $comision_total += $comision;
                            ?>
                            <p>Subtotal: MX$<?php echo number_format($subtotal, 2); ?></p>
                            <p>Comisión: MX$<?php echo number_format($comision, 2); ?></p>
                            <form method="post" action="cart.php">
                                <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="remove_from_cart">Eliminar</button>
                            </form>
                        </div>
                    </div>
                    <?php $total += $subtotal; ?>
                <?php endforeach; ?>
            </div>
            <h2>Total: MX$<?php echo number_format($total + $comision_total, 2); ?> (incluye comisión)</h2>
        <?php else: ?>
            <p>El carrito está vacío.</p>
        <?php endif; ?>
    </div>
</body>
</html>
