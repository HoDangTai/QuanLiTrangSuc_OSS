<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thành công</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .success-container h1 {
            color: #28a745;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .success-container p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }

        .btn-custom {
            background-color: #ff6b6b;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #e63946;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="success-container">
        <h1>Thanh toán thành công!</h1>
        <p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi.</p>
        <p>Mã đơn hàng của bạn: <strong><?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?></strong></p>
        <a href="index.php" class="btn btn-custom">Tiếp tục mua sắm</a>
    </div>
</body>

</html>
