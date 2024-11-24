<?php

@include 'config.php';

if (isset($_POST['reset_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Kiểm tra email và tên tài khoản
    $select = "SELECT * FROM users WHERE EMAIL = '$email' AND NAME = '$name'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        if ($new_password === $confirm_password) {
            // Cập nhật mật khẩu mới
            $update = "UPDATE users SET PASS = '$new_password' WHERE EMAIL = '$email'";
            mysqli_query($conn, $update);
            $_SESSION['response'] = "Mật khẩu đã được thay đổi thành công!";
            $_SESSION['res_type'] = "success";
        } else {
            $error[] = 'Mật khẩu mới không khớp!';
        }
    } else {
        $error[] = 'Email hoặc tên tài khoản không chính xác!';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <style>
        body, html {
            height: 100%;
            font-family: 'Montserrat', sans-serif !important;
            background-color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        .header {
            width: 100%;
            background-color: #ffffff;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-top: 15px;
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
            margin-top: auto
        }
        .logo {
            margin-right: 20px;
            padding-left: 25px;
        }

        .forgot-password-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 80px;
            margin-bottom: 175px;
            width: 100%;
        }

        input[type="text"], input[type="email"], input[type="password"]{
            width: 383px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        select {
            width: 405px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        a {
            font-size: 12px;
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
        #togglePassword:hover {
            color: #4285f4;
        }
        #togglecPassword:hover {
            color: #4285f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <a href="index.php">
                <img class="dt-width-auto" width="170" height="35"
                    src="https://file.hstatic.net/200000103143/file/pandora_acf7bd54e6534a07be748b51c51c637c.svg"
                    alt="Pandora Việt Nam" />
            </a>
        </div>
    </div>
    <div class="forgot-password-form">
        <h2>Quên mật khẩu</h2>
        <form action="" method="post">
        <?php
            if (isset($error)) {
                foreach ($error as $error) {
                    echo '<span style="color:red;">' . $error . '</span>';
                }
            }
            if (isset($_SESSION['response'])) {
                echo '<span style="color:green;">' . $_SESSION['response'] . '</span>';
                unset($_SESSION['response']); // Xóa thông báo sau khi hiển thị
            }
        ?>
            <div>
                <input type="text" name="name" required placeholder="Nhập tên tài khoản">
            </div>
            <div>
                <input type="email" name="email" required placeholder="Nhập email của bạn">
            </div>
            <div>
                <input type="password" name="new_password" required placeholder="Nhập mật khẩu mới">
                <span id="togglePassword" onclick="togglePasswordVisibility()">&#128065;</span>
            </div>
            <div>
                <input type="password" name="confirm_password" required placeholder="Xác nhận mật khẩu mới">
                <span id="togglecPassword" onclick="togglePasswordVisibility2()">&#128065;</span>
            </div>
            <div>
                <input type="submit" name="reset_password" value="Đổi mật khẩu">
            </div>
            <div align="center">
                <p style="font-size: 12px">Bạn nhớ mật khẩu? <a style="color: #DC143C;" href="login.php">Đăng nhập ngay</a></p>
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
            var passwordInput = document.querySelector("input[name='new_password']");
            var toggleIcon = document.getElementById("togglePassword"); // Sửa thành "togglePassword"

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.innerHTML = "&#128064;"; // Mã Unicode cho biểu tượng mắt đóng
            } else {
                passwordInput.type = "password";
                toggleIcon.innerHTML = "&#128065;"; // Mã Unicode cho biểu tượng mắt mở
            }
        }
        function togglePasswordVisibility2() {
            var passwordInput = document.querySelector("input[name='confirm_password']");
            var toggleIcon = document.getElementById("togglecPassword");

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
