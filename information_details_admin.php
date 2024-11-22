<?php
    include 'config.php';

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $name = $gt = $email = $phone = $address = "";

    $error="";
    session_start();
    if (isset($_SESSION['user_id'])) {
        // Lấy thông tin người dùng từ cơ sở dữ liệu
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT * FROM USERS_CUS WHERE ID_USER = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Hiển thị thông tin người dùng
            $row = $result->fetch_assoc();
            $name = $row["FIRSTNAME_CUS"] . " " . $row["LASTNAME_CUS"];
            $email = $row["EMAIL_CUS"];
            $phone = $row["PHONE_CUS"];
            $gt = $row["GioiTinh"];
            $address = $row["ADDRESS_CUS"];
        } else {
            echo $error = "Không tìm thấy thông tin người dùng";
        }
    } else {
        // Người dùng chưa đăng nhập, thực hiện các hành động khác (ví dụ: chuyển hướng đến trang đăng nhập)
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        body {
            height: 100vh;
            background-color: white;
        }

        .container {
    /* background: url(images/Pandora-logo-history.jpg) center no-repeat; */
    margin-top: 5rem;
    height: 100%;
    background-size: cover;
}
h2 {
    margin-top: 2rem; /* Thêm khoảng cách 2rem cho phần tử h2 bên dưới header */
}

.header {
    height: 5rem;
    display: grid;
    background: white;
    background-size: cover;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    border-bottom: 2px solid pink;
}

        nav {
            width: 80%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            color: #fff;
            margin: auto;
        }

        .left-section img {
            width: 140px;
            cursor: pointer;
            position: relative;
            left: -100%;
        }

        .right-section {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-link {
            list-style: none;
            cursor: pointer;
            font-size: 14px;
            position: relative;
        }
        .nav-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    background-color: pink;
    padding: 10px;
    display: none;
    z-index: 999;

}
.footer {
        border-top: 2px solid pink;
        background-color: white;
        color: #000;
        text-align: center;
        padding: 10px;
        width: 100%;
    }

        .nav-link:hover .nav-dropdown {
            display: block;
        }

        .nav-dropdown-item {
            padding: 5px 0;
        }

        a {
            text-decoration: none;
            color: #fff;
            font-weight: 600;
            position: relative;
        }

        a:hover {
            color: #00bfff;
        }

        a::before {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -5px;
            margin: auto;
            width: 0%;
            content: '';
            color: transparent;
            background: linear-gradient(45deg, #e1d5f5, #63a9fe);
            height: 1px;
            transition: all 0.3s;
        }

        /* a:hover::before {
            width: 100%;
        } */
        .action a {
        color: black; /* Đổi màu chữ thành màu đen */
        margin-top: 50px; /* Tăng khoảng cách lề trên lên 50px */
        }
        /* body {
            font-family: 'Montserrat', sans-serif !important;
            background-color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        main{
            flex: 1;
        }

        .left-links a {
            color: #000;
            text-decoration: none;
            padding: 14px 16px;
            margin-right: 20px;
        }

        .right-icons {
            display: flex;
            align-items: center;
        }

        .search-box input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .right-icons i {
            font-size: 24px;
            margin-left: 20px;
            cursor: pointer;
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
        .title_account{
            max-width: 400px;
            margin: 20px auto;
        }
        .profile form {
            max-width: 400px;
            margin: 20px auto;
            margin-bottom: 98px;
        }
        .profile label, input, span{
            display: block;
            margin-bottom: 10px;
        }
        .profile input{
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        } */
    </style>
</head>
<body>
<header class="header">
            <nav>
                <div class="left-section">
                    <img src="images/images/logo.jpg" href="TrangchuAdmin.php"width="100px" height="70" alt="logo">
                    <!-- <h1>Logo</h1> -->
                </div>
                <div class="right-section">
                    <ul class="nav-links">
                        <li class="nav-link">
                            <a href="#" style="color: black">Tài khoản user</a>
                            <div class="nav-dropdown">
                                <div class="nav-dropdown-item">
                                    <a href="general1.php">General</a>
                                </div>
                                <div class="nav-dropdown-item">
                                    <a href="info_cus.php">Thông tin khách hàng</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-link">
                            <a href="#" style="color: black">Sản phẩm</a>
                            <div class="nav-dropdown">
                            <div class="nav-dropdown-item">
                                    <a href="loaits.php">Loại trang sức</a>
                                </div>
                                <div class="nav-dropdown-item">
                                    <a href="sanpham.php">Danh sách sản phẩm</a>
                              </div>
                                <div class="nav-dropdown-item">
                                    <a href="NCC.php">Nhà cung cấp</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-link">
                            <a href="#" style="color: black">Quản lý nhân sự</a>
                            <div class="nav-dropdown">
                            <div class="nav-dropdown-item">
                                    <a href="department.php">Phòng ban</a>
                                </div>
                                <div class="nav-dropdown-item">
                                    <a href="position.php">Vị trí</a>
                              </div>
                                <div class="nav-dropdown-item">
                                    <a href="info_NV.php">Thông tin nhân viên</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-link">
                            <a href="order.php" style="color: black">Quản lý đơn hàng</a>
                        </li>
                
                    </ul>
                    <div class="action">
                    <a href="information_details_admin.php">
                        <i class="fas fa-user"></i>
                        <span><?php echo $_SESSION['user_name'] ?></span>
                    </a>
                    </div>
                </div>
            </nav>
        </header>
    <br>
    <div class="profile">
        <div align="center" class="title_account">
            <h2>THÔNG TIN TÀI KHOẢN</h2>
        </div>
        <form action="" method="post">
            <label for="name">Họ và tên:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

            <label for="name">Giới tính:</label>
            <select name="gt" class="form-control" required>
              <option value="">Chọn giới tính</option>
              <option value="0" <?php echo ($gt == 0) ? 'selected' : ''; ?>>Nam</option>
              <option value="1" <?php echo ($gt == 1) ? 'selected' : '';?>>Nữ</option>
            </select>

            <label for="phone">Số điện thoại:</label>
            <input type="number" id="phone" name="phone" value="<?php echo $phone; ?>">

            <label for="address">Địa Chỉ:</label>
            <input id="address" name="address" value="<?php echo $address; ?>">
            <label><?php echo $error; ?></label>
            <div align="center">
                <a href="logout.php" class="btn">Đăng xuất</a>
            </div>
        </form>
    </div>
    <div class="footer">
        <div>
            &copy; 2023 Tên Công Ty Của Bạn. Đã đăng ký bản quyền.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>