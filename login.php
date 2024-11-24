<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlts";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, 'utf8');

session_start();

if(isset($_POST['dangnhap'])){
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']); // Nhận email hoặc tên tài khoản
    $pass = mysqli_real_escape_string($conn, $_POST['password']); // Mật khẩu

    // Kiểm tra xem đầu vào có phải là email hợp lệ không
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        // Nếu là email, tìm kiếm trong cột EMAIL
        $select = "SELECT * FROM users WHERE EMAIL = '$identifier' AND PASS = '$pass'";
    } else {
        // Nếu không phải là email, tìm kiếm trong cột NAME
        $select = "SELECT * FROM users WHERE NAME = '$identifier' AND PASS = '$pass'";
    }

    $result_user = mysqli_query($conn, $select);

    if (mysqli_num_rows($result_user) > 0) {
        $row = mysqli_fetch_array($result_user);

        // Store user information in the session
        $_SESSION['user_id'] = $row['ID_USER'];
        $_SESSION['user_name'] = $row['NAME'];
        $_SESSION['user_type'] = $row['TYPE_USER'];

        if ($row['TYPE_USER'] == 'Administration') {
            header('location: TrangchuAdmin.php');
        } elseif ($row['TYPE_USER'] == 'Customer') {
            header('location: user.php');
        }
    } else {
        $error[] = 'Tài khoản hoặc mật khẩu không hợp lệ!';
    }

};

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
            background-color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px;
        }
        .footer {
            background-color: #ffcccb;
            color: #000;
            text-align: center;
            width: 100%;
        }
        .logo {
            margin-right: 20px;
            padding-left: 25px;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 80px;
            margin-bottom: 260px;

        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 383px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        a {
            font-size: 10px;
            height: 50px;
            padding: 0px 0px;
            margin-bottom: 10px;
        }

        input[type="submit"],
        button {
            width: 404px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #222222;
            color: #fff;
            font-size: 16px;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #333333;
        }

        button {
            background-color: #4267b2;
            width: 200px;
        }

        button:last-child {
            margin-top: 10px;
        }

        @media only screen and (max-width: 650px) {
            .header {
                justify-content: center;
            }

        }
        /* Loại bỏ gạch chân cho các liên kết */
        a,a:hover {
            text-decoration: none;
            color: black;
        }
        .span{
            background: crimson;
            color:#fff;
            border-radius: 5px;
            padding:0 15px;
        }
        #togglePassword:hover {
            color: #4285f4;
        }
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
    </style>
</head>

<body>
    <header id="new-header" class="stickystack">
        <div class="new-header-top">
            <div class="new-container">
                <div class="new-header-top-wrap"> 
                    <div class="new-header-top-logo">
                        <a href="index.php">
                            <img class="dt-width-auto" width="170" height="35" src="https://file.hstatic.net/200000103143/file/pandora_acf7bd54e6534a07be748b51c51c637c.svg" alt="Pandora Việt Nam">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="login-form">
        <h2>Đăng nhập</h2>
        <form action="" method="post">
        <?php
            if(isset($error)){
                foreach($error as $error){
                    echo '<span style="color:red">'.$error.'</span>';
                };
            };
        ?>
            <div>
                <input type="text" name="identifier" required placeholder="Email hoặc tên tài khoản">
            </div>
            <div>
                <input type="password" name="password" required placeholder="Mật khẩu">
                <span id="togglePassword" onclick="togglePasswordVisibility()">&#128065;</span>
            </div>
            <div>
                <a href="forgotpass.php">Quên mật khẩu?</a><a style="color: gray; padding: 5px">hoặc</a><a href="register.php"
                    title="Register">Đăng ký</a>
            </div>
            <div>
                <input type="submit" value="ĐĂNG NHẬP" name="dangnhap">
            </div>
            <div>
                <button style="background-color:firebrick;">Đăng nhập Google</button>
                <button>Đăng nhập Facebook</button>
            </div>
        </form>
    </div>
    <div class="footer">
        <div class="container">
            &copy; Công ty Pandora Việt Nam
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.querySelector("input[name='password']");
            var toggleIcon = document.getElementById("togglePassword");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.innerHTML = "&#128064;"; // Mã Unicode cho biểu tượng mắt đóng
            } else {
                passwordInput.type = "password";
                toggleIcon.innerHTML = "&#128065;"; // Mã Unicode cho biểu tượng mắt mở
            }
        }
    </script>
</body>
</html>
