<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$id_cus='';

    // Query to get ID_CUS from users_cus table using ID_USER
    $query = 'SELECT ID_CUS FROM users_cus WHERE ID_USER = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id); // Use $user_id instead of $id_user
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_cus = $row['ID_CUS']; // Assign the fetched ID_CUS
    } else {
        echo "<script>alert('Customer ID not found. Please contact support.'); window.location.href='login.php';</script>";
        exit();
    }
// Add to cart function
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Retrieve product price from database
    $query = 'SELECT GIA FROM sanpham WHERE ID_TS = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $price = $product['GIA'];

    $total_price = $price * $quantity;

    // Insert or update the cart in the database
    $check_query = 'SELECT * FROM giohang WHERE ID_CUS = ? AND ID_TS = ?';
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('ss', $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing cart item
        $update_query = 'UPDATE giohang SET SOLUONG = SOLUONG + ?, THANHTIEN = THANHTIEN + ? WHERE ID_CUS = ? AND ID_TS = ?';
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('ddss', $quantity, $total_price, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Insert new cart item
        $insert_query = 'INSERT INTO giohang (ID_CUS, ID_TS, SOLUONG, THANHTIEN) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('ssid', $user_id, $product_id, $quantity, $total_price);
        $stmt->execute();
    }

    echo "<script>alert('Product added to cart!');</script>";
}
// Update cart quantity
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Retrieve product price
    $query = 'SELECT GIA FROM sanpham WHERE ID_TS = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $price = $product['GIA'];

    $total_price = $price * $quantity;

    // Update quantity and total price
    $update_query = 'UPDATE giohang SET SOLUONG = ?, THANHTIEN = ? WHERE ID_CUS = ? AND ID_TS = ?';
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('idss', $quantity, $total_price, $id_cus, $product_id);
    $stmt->execute();

    echo "<script>alert('Giỏ hàng đã được cập nhật!'); window.location.href='giohang.php';</script>";
}

// Remove from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    $delete_query = 'DELETE FROM giohang WHERE ID_CUS = ? AND ID_TS = ?';
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param('ss', $id_cus, $product_id);
    $stmt->execute();

    echo "<script>alert('Sản phẩm đã được xóa khỏi giỏ hàng!'); window.location.href='giohang.php';</script>";
}

    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ Pandora</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        body {
        font-family: 'Times New Roman', Times, serif, sans-serif;
        background-color: #fff;
        margin: 0;
        padding: 0;
        }
        .search-box input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .product {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            transition: background-color 0.3s ease;
            margin: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product:hover {
            background-color: #ffcccb;
            
        }

        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
            margin-left: auto;
            margin-right: auto;
        }

        .details-button {
            display: inline-block;
            background-color: pink;
            color: black;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .details-button:hover {
            background-color: #333;
            color: pink;
        }

        .footer {
            background-color: #ffcccb;
            color: #000;
            text-align: center;
            padding: 10px 0;
            width: 100%;
        }

        
        /*-------------------------------------*/
        .form-group {
            display: flex;
            align-items: center;
        }

        .form-control {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            /* Thêm các thuộc tính CSS cần thiết cho input */
        }

        .btn {
            
            background-color: pink;
            color: black;
            padding: 5px 10px;

        }
        /*--------------------------------------------------------------------------------------------------*/
        /* Navbar menu list */
        .header {
            background-color: #ffcccb;
            color: #000;
            text-align: center;
            padding: 15px 0;
            margin-top: 20px;
        }

        .new-header-top-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .new-header-top-logo {
            margin-right: auto; /* Đẩy new-header-top-logo về bên trái */
            margin-left: 20px;
            margin-bottom: 10px;
        }

        .new-header-top-actions {
            margin-left: auto; /* Đẩy new-header-top-actions về bên phải */
        }

        .new-header-top-actions-list ul {
            display: flex;
            list-style: none;
            padding: 0;
        }

        .new-header-top-actions-list ul li {
            margin-right: 20px; /* Khoảng cách giữa các mục trong danh sách */
        }
        .new-header-top-actions {
            display: flex; /* Sử dụng Flexbox để sắp xếp các đối tượng trên một hàng ngang */
            align-items: center; /* Canh giữa các đối tượng theo chiều dọc */
        }

        .new-header-search,
        .new-header-top-actions-list {
            margin-right: 20px; /* Khoảng cách giữa các đối tượng */
        }

        .new-header-search form,
        .new-header-search-ovl,
        .new-header-top-actions-account,
        .new-header-top-actions-cart {
            display: flex; /* Sử dụng Flexbox để sắp xếp các phần tử con trên một hàng ngang */
            align-items: center; /* Canh giữa các phần tử con theo chiều dọc */
        }
        
        .new-header-menu-list {
        list-style: none;
        padding: 0;
        display: flex;
        align-items: center;
        background-color: #ffcad4; /* Màu nền hồng */
        padding: 10px 20px; /* Khoảng cách giữa các box và độ rộng của background */
        }

        .new-header-menu-list-item {
            margin-right: 20px; /* Khoảng cách giữa các mục menu */
            position: relative;
        }

        .new-header-menu-mega,
        .new-header-menu-mega-sub {
            display: none;
            position: absolute;
            top: 0;
            left: 100%;
            background-color: #fff;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.1);
            z-index: 1;
            width: 200px; /* Độ rộng của dropdown menu và submenu */
            padding: 10px;
            border: 1px solid #ccc; /* Border cho dropdown menu và submenu */
        }

        .new-header-menu-list-item:hover .new-header-menu-mega,
        .new-header-menu-mega-item:hover .new-header-menu-mega-sub {
            display: block;
        }

        .new-header-menu-mega-list,
        .new-header-menu-mega-sub-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column; /* Hiển thị submenu theo chiều dọc */
        }

        .new-header-menu-mega-item,
        .new-header-menu-mega-sub-item {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .new-header-menu-mega-item:last-child,
        .new-header-menu-mega-sub-item:last-child {
            border-bottom: none;
        }

        /* Loại bỏ gạch chân cho các liên kết */
        a,a:hover {
            text-decoration: none;
            color: black;
        }
        /*-------------------------------------------------------------- */
        .new-index-item2-wrap {
                padding: 20px;
            }
        .new-index-item2-item {
            margin-bottom: 20px;
        }

        .new-index-item2-item img {
            width: 100%;
            height: auto;
        }

        .new-index-item2-item .content {
            background-color: white;
            color: black;
            text-align: center;
            padding: 10px;
            position: relative;
        }

        .new-index-item2-item .content span {
            text-decoration: underline pink;
            text-decoration-thickness: 2px;
            text-underline-offset: 5px;
        }
        /*-------------------------------------------------------------- */
        .new-index-item1-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            flex-direction: row;
        }

        .new-index-item1-item {
            text-align: center;
            margin: 0 15px;
        }

        .new-index-item1-item img {
            width: 130px;
            height: 130px;
        }
        @media (max-width: 768px) {
        .new-index-item1-wrap {
        flex-direction: column;
        align-items: flex-start;
            }
        }
        .new-index-item1-item .card-body span {
            text-decoration: underline pink;
            text-decoration-thickness: 2px;
            text-underline-offset: 5px;
        }
        .cart-container {
            margin: 20px auto;
            max-width: 900px;
        }

        .cart-header {
            background-color: #ffcccb;
            padding: 15px;
            border-radius: 5px;
            color: white;
            text-align: center;
        }

        .cart-table {
            margin-top: 20px;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
        }

        .cart-table th, 
        .cart-table td {
            vertical-align: middle;
        }

        .update-btn, .remove-btn {
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .update-btn {
            background-color: #17a2b8;
        }

        .update-btn:hover {
            background-color: #138496;
        }

        .remove-btn {
            background-color: #dc3545;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }

        .total-container {
            text-align: right;
            padding: 20px 0;
        }

        .footer {
            background-color: #ffcccb;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 15px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <header id="new-header" class="stickystack">
        <div class="new-header-top">
            <div class="new-container">
                <div class="new-header-top-wrap"> 
                    <div class="new-header-top-logo">
                        <a href="index.php">
                            <img class="dt-width-auto" width="170" height="35" src="https://file.hstatic.net/200000103143/file/pandora_acf7bd54e6534a07be748b51c51c637c.svg" alt="Pandora Việt Nam"/>
                        </a>
                    </div>
                    <div class="new-header-top-actions">
                        <div class="new-header-search">
                            <form action="search.php" method="GET">
                                <input type="hidden" name="type" value="product">
                                <div class="form-group search-input-wrap">
                                    <input type="text" class="form-control js-search-input" name="q" placeholder="Tìm sản phẩm..." autocomplete="off" required>
                                    <button type="submit" class="btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <div class="search-suggest">
                                    </div>
                                </div> 
                            </form>
                            <a href="#" class="new-header-search-ovl"></a>
                        </div>
                        <div class="new-header-top-actions-list">
                            <ul>
                                <li class="new-header-top-actions-account">
                                    <a href="login.php">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </li>
                                <li class="new-header-top-actions-cart">
                                    <a href="giohang.php">
                                        <i class="fas fa-shopping-cart"></i>
                                        
                                    </a>
                                    <div class="popupCart"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="new-header-bottom">
            <div class="new-container">
                <div class="new-header-menu">
                    <ul class="new-header-menu-list">
                        
                        <li class="new-header-menu-list-item has-child">
                        <a href="#">
                            <span>Bộ sưu tập mới</span>
                        </a>
                            <div class="new-header-menu-mega">
                                <ul class="new-header-menu-mega-list"> 
                                    <li class="new-header-menu-mega-item">
                                        <a href="#">
                                            <span>Bộ sưu tập</span>								
                                        </a>									
                                        <ul class="new-header-menu-mega-sub">								
                                            <li><a href="">New Arrivals</a></li>										
                                            <li><a href="">Pandora Moments</a></li>										
                                        </ul>
                                    </li>
                                    <li class="new-header-menu-mega-item">
                                        <a href="#">
                                            <span>Chủ đề</span>							
                                        </a>
                                        <ul class="new-header-menu-mega-sub">
                                            <li><a href="#">Bà</a></li>									
                                            <li><a href="#">Bạn Bè</a></li>	
                                            <li><a href="#">Chị em gái</a></li>
                                            <li><a href="#">Còn gái</a></li>	
                                            <li><a href="#">Người yêu</a></li>										
                                        </ul>
                                    </li>
                                    <li class="new-header-menu-mega-item">
                                        <a href="#">
                                            <span>Theo Cung - mệnh</span>
                                        </a>
                                        <ul class="new-header-menu-mega-sub">
                                            <li><a href="">Cung Hoàng đạo</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="new-header-menu-list-item has-child">
                            <a href="#">
                                <span>Trang sức</span>
                            </a>                           
                            <div class="new-header-menu-mega">
                                <ul class="new-header-menu-mega-list">                                    
                                    <li class="new-header-menu-mega-item">
                                        <a href="#">
                                            <span>Charms</span>                                           
                                        </a>                                        
                                        <ul class="new-header-menu-mega-sub">
                                            <li><a href="#">Tất cả</a></li>                                            
                                            <li><a href="#">Charm chặn</a></li>
                                        </ul>
                                    </li>
                                    <li class="new-header-menu-mega-item">
                                        <a href="#">
                                            <span>Vòng</span>
                                        </a>
                                        <ul class="new-header-menu-mega-sub">
                                            <li><a href="#">Tất cả</a></li>
                                            <li><a href="#">Vòng mềm</a></li>
                                        </ul>
                                    </li>
                                    <li class="new-header-menu-mega-item">
                                        <a href="#">
                                            <span>Dây Chuyền</span>
                                        </a>
                                        <ul class="new-header-menu-mega-sub">
                                            <li><a href="#">Tất cả</a></li>
                                            <li><a href="#">Dây chuyền</a></li>
                                        </ul>
                                    </li>
                                    <li class="new-header-menu-mega-item">
                                        <a href="#">
                                            <span>Hoa Tai</span>
                                        </a>
                                        <ul class="new-header-menu-mega-sub">
                                            <li><a href="#">Tất cả</a></li>
                                            <li><a href="#">Kiểu tròn</a></li>
                                            <li><a href="#">Bông tai nụ</a></li>
                                            <li><a href="#">Kiểu rơi</a></li>
                                        </ul>
                                    </li>
                                    <li class="new-header-menu-mega-item">
                                        <a href="#">
                                            <span>Nhẫn</span>
                                        </a>
                                        <ul class="new-header-menu-mega-sub">
                                            <li><a href="#">Tất cả</a></li>
                                            <li><a href="#">Nhẫn bạc</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="new-header-menu-list-item has-child">
                            <a href="/collections/charms-va-vong">
                                <span>Vòng & Charm</span>
                            </a>
                            <div class="new-header-menu-mega">
                                <ul class="new-header-menu-mega-list"> 
                                    <li class="new-header-menu-mega-item">
                                        <a href="/collections/pandora-moments">
                                            <span>Pandora Moments</span>
                                        </a>
                                        <ul class="new-header-menu-mega-sub">
                                            <li class="back-menu">
                                                <a href="#">
                                                    <span>Pandora Moments</span>
                                                </a>
                                            </li>
                                            <li><a href="/collections/charms">Charms</a></li>
                                            <li><a href="/collections/vong-pandora-moments">Vòng</a></li>
                                            <li><a href="/collections/phu-kien-pandora">Phụ kiện</a></li>
                                        </ul>
                                    </li>
                                    <li class="new-header-menu-mega-item">
                                        <a href="/collections/pandora-reflexions">
                                            <span>Pandora Reflexions</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>	
                        </li>	
                    </ul>
                </div>
            </div>
        </div>
    </header>
  
        <?php
        // Display Cart Items
        $query = 'SELECT g.ID_TS, p.TENTS, g.SOLUONG, g.THANHTIEN FROM giohang g JOIN sanpham p ON g.ID_TS = p.ID_TS WHERE g.ID_CUS = ?';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $id_cus);
        $stmt->execute();
        $result = $stmt->get_result();

        // Calculate total cart value
        $total_query = 'SELECT SUM(THANHTIEN) as total FROM giohang WHERE ID_CUS = ?';
        $stmt = $conn->prepare($total_query);
        $stmt->bind_param('s', $id_cus);
        $stmt->execute();
        $total_result = $stmt->get_result();
        $total_row = $total_result->fetch_assoc();
        $total_cart_value = $total_row['total'];
        ?>

    <div class="container cart-container">
        <div class="cart-header">
            <h1>Giỏ Hàng Của Bạn</h1>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped cart-table">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Sản Phẩm</th>
                        <th>Số Lượng</th>
                        <th>Thành Tiền</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1;
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td>
                            <strong><?php echo $row['TENTS']; ?></strong>
                        </td>
                        <td>
                            <form method="post" action="" class="d-flex">
                                <input type="hidden" name="product_id" value="<?php echo $row['ID_TS']; ?>">
                                <input type="number" name="quantity" class="form-control w-50 me-2" value="<?php echo $row['SOLUONG']; ?>" min="1">
                                <button type="submit" name="update_cart" class="btn update-btn">Cập Nhật</button>
                            </form>
                        </td>
                        <td><?php echo number_format($row['THANHTIEN']); ?> VNĐ</td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="<?php echo $row['ID_TS']; ?>">
                                <button type="submit" name="remove_from_cart" class="btn remove-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total-container">
                <h3>Tổng Giá Trị: <span class="text-danger"><?php echo number_format($total_cart_value); ?> VNĐ</span></h3>
            </div>

            <div class="text-center">
                <a href="user.php" class="btn btn-secondary">Tiếp Tục Mua Sắm</a>
                <a href="checkout.php" class="btn btn-primary">Thanh Toán</a>
            </div>

        <?php else: ?>
            <div class="alert alert-warning text-center mt-4">
                <strong>Giỏ hàng của bạn đang trống!</strong>
            </div>
            <div class="text-center">
                <a href="user.php" class="btn btn-success">Bắt Đầu Mua Sắm</a>
            </div>
        <?php endif; ?>
    </div>




</div>
    <div class="footer">
        <div class="container">
            &copy; Công ty Pandora Việt Nam
        </div>
    </div>
<?php
mysqli_close($conn);
?>
</body>
</html>
