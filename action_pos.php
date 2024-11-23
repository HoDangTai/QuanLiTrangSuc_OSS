<?php
	session_start();
	include 'config.php';
	mysqli_set_charset($conn, 'utf8mb4');
	$id_pos = "";
	$tenvitri = "";

    $update=false;

	if (isset($_POST['add'])) {
        $id_pos = $_POST['id_pos'];
		$tenvitri = $_POST['tenvitri'];

		// Kiểm tra xem ID_VITRI đã tồn tại chưa
		$query_check = "SELECT * FROM POSITION WHERE ID_POS = ?";
		$stmt_check = $conn->prepare($query_check);
		$stmt_check->bind_param("s", $id_pos);
		$stmt_check->execute();
		$result_check = $stmt_check->get_result();

		if ($result_check->num_rows > 0) {
			// Nếu có vị trí với ID_POS này, thông báo lỗi
			$_SESSION['response'] = "Position ID already exists. Please use a unique ID!!";
			$_SESSION['res_type'] = "danger";
			header('Location: position.php');
			exit; // Dừng việc thực hiện tiếp
		} else {
			// Nếu ID_VITRI chưa tồn tại, thực hiện thao tác chèn
			$query = "INSERT INTO POSITION (ID_POS, NAME_POS) VALUES (?, ?)";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ss", $id_pos, $tenvitri);
			$stmt->execute();

			// Cập nhật thông báo thành công
			$_SESSION['response'] = "Added position successfully!";
			$_SESSION['res_type'] = "success";
			header('Location: position.php');
		}

	}

	if (isset($_GET['edit'])) {
		$id_pos = $_GET['edit'];

		$query = "SELECT * FROM POSITION WHERE ID_POS=?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $id_pos);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		$id_pos = $row['ID_POS'];
		$tenvitri = $row['NAME_POS'];

        $update=true;

	}
	if (isset($_POST['update'])) {
        $id_pos = $_POST['id_pos'];
        $tenvitri = $_POST['tenvitri'];
      
        // Kiểm tra xem dữ liệu mới có khác với dữ liệu cũ không
        $query_check = "SELECT * FROM POSITION WHERE ID_POS=?";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("s", $id_pos);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();
      
        $id_pos_old = $row_check['id_pos'];
        $tenvitri_old = $row_check['tenvitri'];
      
        if ($id_pos != $id_pos_old || $tenvitri != $tenvitri_old ) {
          echo "Dữ liệu được chỉnh sửa mới:<br>";
          echo "ID : " . $id_pos . "<br>";
          echo "Tên vị trí: " . $tenvitri . "<br>";
        }
      
        // Tiếp tục thực hiện câu truy vấn UPDATE
        $query = "UPDATE POSITION SET NAME_POS=? WHERE ID_POS=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $tenvitri, $id_pos);
        $stmt->execute();
      
        $_SESSION['response'] = "Updated Successfully!";
        $_SESSION['res_type'] = "primary";
        header('location: position.php');
      }

	if (isset($_GET['delete'])) {
		$id_pos = $_GET['delete'];

		$query = "DELETE FROM POSITION WHERE ID_POS=?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $id_pos);
		$stmt->execute();

		header('location: position.php');
		$_SESSION['response'] = "Successfully Deleted!";
		$_SESSION['res_type'] = "danger";
	}
	if (isset($_GET['details'])) {
		$id_pos = $_GET['details'];
		$query = "SELECT * FROM POSITION WHERE ID_POS=?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $id_pos);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		$vid = $row['ID_POS'];
		$vtenvitri = $row['NAME_POS'];
	}
?>