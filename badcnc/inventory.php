<?php
include("database/db.php");

$category = isset($_GET['category']) ? $_GET['category'] : 'Meat';

// Fetch category id
$stmt = $conn->prepare("SELECT id FROM inventory_category WHERE name = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$stmt->bind_result($category_id);
$stmt->fetch();
$stmt->close();

$items = [];
if ($category_id) {
    $stmt = $conn->prepare("SELECT name, quantity, unit FROM inventory_item WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->bind_result($name, $quantity, $unit);
    while ($stmt->fetch()) {
        $items[] = ['product' => $name, 'quantity' => $quantity . ' ' . $unit];
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" />
    <style>
        .low-stock {
            color: red;
            font-weight: bold;
        }
    </style>
    <script src="jquery-3.7.1.min.js"></script>
    <script src="actions/javaq.js"></script>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="menu-icon-button">
            <button id="menu-toggle">&#9776;</button> <!-- Menu icon -->
        </div>
        <img src="Images/cnclogo.png" alt="Logo" class="logo">
        <div class="brand-name">Cake n' Coat</div>
        <?php
            session_start();
            if (!isset($_SESSION['user'])) {
                header("location: actions/login.php");
                exit();
            }
        ?>
        <div class="username">
            <?php echo htmlspecialchars($_SESSION['user']); ?>
            <div><button id="signOut">Sign out</button></div>
        </div>
    </div>

     <!-- Sidebar -->
     <div id="sidebar" class="sidebar hidden">
        <button id="close-sidebar" class="close-sidebar-button">X</button> <!-- Close button -->
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="orderHistory.php">Orders</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>
    
    <div class="main">
        <h1>Inventory</h1>
        <div class="inventory-container">
            <!-- Category Sidebar -->
            <div class="inventory-category">
                <h2>Category</h2>
                <ul>
                    <li>
                        <span class="category-header">Kitchen &#9660;</span>
                        <ul class="subcategory" style="display:none;">
                            <li><a href="?category=Meat">Meat</a></li>
                            <li><a href="?category=Seasoning">Seasoning</a></li>
                            <li><a href="?category=Liquids">Liquids</a></li>
                            <li><a href="?category=Dairy">Dairy</a></li>
                            <li><a href="?category=Grains">Grains & Starches</a></li>
                            <li><a href="?category=Vegetables">Vegetables</a></li>
                            <li><a href="?category=Fruits">Fruits</a></li>
                            <li><a href="?category=Condiments">Condiments</a></li>
                            <li><a href="?category=Canned">Canned</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="category-header">Bar &#9660;</span>
                        <ul class="subcategory" style="display:none;">
                            <li><a href="?category=Coffee">Coffee</a></li>
                            <li><a href="?category=Canned">Non Coffee</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Inventory Table -->
            <form id="inventory-form" method="POST" action="actions/update_inventory.php">
                <div class="inventory-table">
                    <table id="inventory-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows inserted here -->
                             <?php foreach ($items as $item): ?>
                                <?php
                                    // Separate quantity number and unit from the string
                                    preg_match('/^(\d+(?:\.\d+)?)\s*(.*)$/', $item['quantity'], $matches);
                                    $qty = isset($matches[1]) ? floatval($matches[1]) : 0;
                                    $unit = $matches[2] ?? 'kg';

                                    // Determine if low stock (quantity <= 1)
                                    $lowStockClass = ($qty <= 1) ? 'low-stock' : '';
                                ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product']) ?></td>
                                <td>
                                    <input
                                        type="number"
                                        name="quantity[<?= htmlspecialchars($item['product']) ?>]"
                                        value="<?= htmlspecialchars($qty) ?>"
                                        step="0.01"
                                        min="0"
                                        style="width:60px;"
                                    />
                                    <select name="unit[<?= htmlspecialchars($item['product']) ?>]">
                                    <?php
                                        $units = ['kg', 'g', 'L', 'ml', 'packs', 'pack', 'bottles', 'bottle', 'jars', 'jar', 'cans', 'can', 'pcs', 'loaves', 'boxes', 'heads'];
                                        foreach ($units as $u): ?>
                                            <option value="<?= $u ?>" <?= $u === $unit ? 'selected' : '' ?>><?= $u ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <button type="submit">Update Inventory</button>
            </form>
        </div>
    </div>









    
    <script>
        // Inventory data for subcategories (with sample quantities and capitalized)
        const inventoryData = {
            "<?= htmlspecialchars($category) ?>": <?= json_encode($items) ?>
            };

        function makeQuantitiesEditable() {
            document.querySelectorAll('.inventory-table td:nth-child(2)').forEach(cell => {
                cell.style.cursor = 'pointer';
                cell.addEventListener('click', function handleClick() {
                    if (cell.querySelector('input')) return;

                    const match = cell.textContent.trim().match(/^(\d+)\s*(kg|packs?|bottles?|jars?|cans?|pcs?|loaves?|boxes?|heads?|ml|g|L)$/i);
                    let value = match ? match[1] : '';
                    let unit = match ? match[2] : 'kg';

                    // Normalize units for select
                    const units = [
                        'kg', 'g', 'L', 'ml', 'packs', 'pack', 'bottles', 'bottle', 'jars', 'jar', 'cans', 'can', 'pcs', 'loaves', 'boxes', 'heads'
                    ];
                    if (!units.includes(unit)) unit = 'kg';

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.value = value;
                    input.style.width = '50px';

                    const select = document.createElement('select');
                    units.forEach(opt => {
                        const option = document.createElement('option');
                        option.value = opt;
                        option.textContent = opt;
                        if (opt === unit) option.selected = true;
                        select.appendChild(option);
                    });

                    // Track focus
                    let focusCount = 0;
                    function onFocus() { focusCount++; }
                    function onBlur() {
                        focusCount--;
                        setTimeout(() => {
                            if (focusCount <= 0) save();
                        }, 0);
                    }

                    input.addEventListener('focus', onFocus);
                    select.addEventListener('focus', onFocus);
                    input.addEventListener('blur', onBlur);
                    select.addEventListener('blur', onBlur);

                    function save() {
                        const newValue = input.value;
                        const newUnit = select.value;
                        if (newValue) {
                            cell.textContent = `${newValue} ${newUnit}`;
                            // Add low-stock class if quantity is 1 or less
                            if (
                                (['kg', 'L', 'packs', 'pack', 'bottles', 'bottle', 'jars', 'jar', 'cans', 'can', 'pcs', 'loaves', 'boxes', 'heads'].includes(newUnit) && Number(newValue) <= 1)
                            ) {
                                cell.classList.add('low-stock');
                            } else {
                                cell.classList.remove('low-stock');
                            }
                        } else {
                            cell.textContent = `${value} ${unit}`;
                        }
                    }

                    cell.textContent = '';
                    cell.appendChild(input);
                    cell.appendChild(select);
                    input.focus();

                    input.addEventListener('keydown', e => {
                        if (e.key === 'Enter') {
                            save();
                            input.blur();
                            select.blur();
                        }
                    });
                });
            });
        }

        // Helper to render inventory table
        function renderInventoryTable(category) {
    const tbody = document.querySelector('#inventory-table tbody');
    tbody.innerHTML = '';
    if (!inventoryData[category]) return;
    inventoryData[category].forEach(item => {
        const tr = document.createElement('tr');

        const tdProduct = document.createElement('td');
        tdProduct.textContent = item.product;

        const tdQuantity = document.createElement('td');

        // Create quantity input
        const inputQuantity = document.createElement('input');
        inputQuantity.type = 'number';
        inputQuantity.name = `quantity[${item.product}]`;
        inputQuantity.value = parseFloat(item.quantity) || 0;
        inputQuantity.step = '0.01';
        inputQuantity.min = '0';
        inputQuantity.style.width = '60px';

        // Extract unit
        const unitMatch = item.quantity.match(/[a-zA-Z ]+$/);
        let unit = unitMatch ? unitMatch[0].trim() : 'kg';

        const selectUnit = document.createElement('select');
        selectUnit.name = `unit[${item.product}]`;
        const units = ['kg', 'g', 'L', 'ml', 'packs', 'pack', 'bottles', 'bottle', 'jars', 'jar', 'cans', 'can', 'pcs', 'loaves', 'boxes', 'heads'];
        units.forEach(u => {
            const option = document.createElement('option');
            option.value = u;
            option.textContent = u;
            if (u === unit) option.selected = true;
            selectUnit.appendChild(option);
        });

        tdQuantity.appendChild(inputQuantity);
        tdQuantity.appendChild(selectUnit);

        tr.appendChild(tdProduct);
        tr.appendChild(tdQuantity);
        tbody.appendChild(tr);
    });
}


        // Sidebar expand/collapse logic
        document.querySelectorAll('.category-header').forEach(header => {
            header.addEventListener('click', () => {
                const subcategory = header.nextElementSibling;
                if (subcategory && subcategory.classList.contains('subcategory')) {
                    if (subcategory.style.display === 'none') {
                        subcategory.style.display = 'block';
                    } else {
                        subcategory.style.display = 'none';
                    }
                }
            });
        });
    </script>
</body>
</html>