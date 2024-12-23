<?php
	session_start();
	include 'config.php';
	mysqli_set_charset($conn, 'utf8mb4');
    $id_emp="";
	$id = "";
    $id_dep = "";
    $id_pos = "";
	$firstname_emp = "";
	$lastname_emp = "";
	$gt = "";
    $email_emp = "";
	$add_emp = "";
	$phone_emp = "";
    $quyen = "";

    $update=false;

	if (isset($_POST['add'])) {

        if (isset($_POST['add'])) {
            $id_emp = $_POST['id_emp'];
            $id = $_POST['id'];
            $id_dep = $_POST['id_dep'];
            $id_pos = $_POST['id_pos'];
            $firstname_emp = $_POST['firstname_emp'];
            $lastname_emp = $_POST['lastname_emp'];
            $gt = $_POST['gt'];
            $email_emp = $_POST['email_emp'];
            $add_emp = $_POST['add_emp'];
            $phone_emp = $_POST['phone_emp'];
            $quyen = $_POST['quyen'];
        
            // Kiểm tra xem ID_EMP đã tồn tại hay chưa
            $check_query = "SELECT ID_EMP FROM USERS_EMPLOYEER WHERE ID_EMP = ?";
            $stmt_check = $conn->prepare($check_query);
            $stmt_check->bind_param("s", $id_emp);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
        
            if ($result_check->num_rows > 0) {
                // Nếu ID_EMP đã tồn tại, thông báo lỗi
                $_SESSION['response'] = "Error: Employeer ID already exists. Please use a unique ID!";
                $_SESSION['res_type'] = "danger";
                header('location: info_NV.php');
                exit();
            }
        
            // Nếu ID_EMP chưa tồn tại, thêm mới vào bảng
            $query = "INSERT INTO USERS_EMPLOYEER (ID_EMP, ID_USER, ID_DEP, ID_POS, 
                FIRSTNAME_EMP, LASTNAME_EMP, GioiTinh, EMAIL_EMP, ADDRESS_EMP, PHONE_EMP, QUYEN) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssssss", $id_emp, $id, $id_dep, $id_pos, $firstname_emp, $lastname_emp, $gt, $email_emp, $add_emp, $phone_emp, $quyen);
            $stmt->execute();
        
            // Chuyển hướng và hiển thị thông báo thành công
            $_SESSION['response'] = "Added employeer successfully!";
            $_SESSION['res_type'] = "success";
            header('location: info_NV.php');
            exit();
        }
        
	}

	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];

		$query = "DELETE FROM USERS_EMPLOYEER WHERE ID_EMP=?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $id);
		$stmt->execute();

		header('location: info_NV.php');
		$_SESSION['response'] = "Successfully Deleted!";
		$_SESSION['res_type'] = "danger";
	}

	if (isset($_GET['edit'])) {
		$id = $_GET['edit'];

		$query = "SELECT * FROM USERS_EMPLOYEER WHERE ID_EMP=?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

        $id_emp=$row['ID_EMP'];
        $id=$row['ID_USER'];
        $id_dep=$row['ID_DEP'];
        $id_pos=$row['ID_POS'];
        $firstname_emp =$row['FIRSTNAME_EMP'];
        $lastname_emp =$row['LASTNAME_EMP'];
        $gt =$row['GioiTinh'];
        $email_emp =$row['EMAIL_EMP'];
        $add_emp =$row['ADDRESS_EMP'];
        $phone_emp =$row['PHONE_EMP'];
        $quyen =$row['QUYEN'];

        $update=true;
	}if (isset($_POST['update'])) {
        
        $id_emp=$_POST['id_emp'];
        $id=$_POST['id'];
        $id_dep=$_POST['id_dep'];
        $id_pos=$_POST['id_pos'];
        $firstname_emp =$_POST['firstname_emp'];
        $lastname_emp =$_POST['lastname_emp'];
        $gt =$_POST['gt'];
        $email_emp =$_POST['email_emp'];
        $add_emp =$_POST['add_emp'];
        $phone_emp =$_POST['phone_emp'];
        $quyen =$_POST['quyen'];
      
        // Kiểm tra xem dữ liệu mới có khác với dữ liệu cũ không
        $query_check = "SELECT * FROM USERS_EMPLOYEER WHERE ID_EMP=?";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("s", $id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();
      
        // $id_emp_old=$row_check['id_emp'];
        // $id_dep_old=$row_check['id_dep'];
        // $id_pos_old=$row_check['id_pos'];
        // $firstname_emp_old =$row_check['firstname_emp'];
        // $lastname_emp_old =$row_check['lastname_emp'];
        // $gt_old=$row_check['gt'];
        // $email_emp_old =$row_check['email_emp'];
        // $add_emp_old =$row_check['add_emp'];
        // $phone_emp_old =$row_check['phone_emp'];
        // $quyen_old =$row_check['quyen'];
      
        // if ($id_emp != $id_emp_old || $id_dep != $id_dep_old || $id_pos != $id_pos_old ||
        //  $firstname_emp != $firstname_emp_old || $lastname_emp != $lastname_emp_old || $gt != $gt_old || $email_emp != $email_emp_old
        //  || $add_emp != $add_emp_old || $phone_emp != $phone_emp_old || $quyen != $quyen_old ) {
        //   echo "Dữ liệu được chỉnh sửa mới:<br>";
        //   echo "ID NV: " . $id_emp . "<br>";
        //   echo "Phòng ban: " . $id_dep . "<br>";
        //   echo "Vị trí: " . $id_pos . "<br>";
        //   echo "Tên: " . $firstname_emp . "<br>";
        //   echo "Họ: " . $lastname_emp . "<br>";
        //   echo "Giới tính: " . $gt . "<br>";
        //   echo "Email: " . $email_emp . "<br>";
        //   echo "Địa chỉ: " . $add_emp . "<br>";
        //   echo "SĐT: " . $phone_emp . "<br>";
        //   echo "Quyền: " . $quyen . "<br>";
        // }
      
        // Tiếp tục thực hiện câu truy vấn UPDATE
        $query = "UPDATE USERS_EMPLOYEER SET ID_USER=?, ID_DEP =?, ID_POS =? 
        ,FIRSTNAME_EMP=?, LASTNAME_EMP=?, GioiTinh=?, EMAIL_EMP =?,
        ADDRESS_EMP=?, PHONE_EMP=?, QUYEN  =? WHERE ID_EMP=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssisssss",$id, $id_dep, $id_pos, $firstname_emp ,
        $lastname_emp,$gt,$email_emp, $add_emp,$phone_emp, $quyen,  $id_emp);
        $stmt->execute();
      
        $_SESSION['response'] = "Updated Successfully!";
        $_SESSION['res_type'] = "primary";
        header('location: info_NV.php');
      }

	if (isset($_GET['details'])) {
		$id = $_GET['details'];
        $query = "
        SELECT e.*, 
            p.NAME_DEP AS TEN_PHONGBAN, 
            v.NAME_POS AS TEN_VITRI
        FROM USERS_EMPLOYEER e
        LEFT JOIN DEPARTMENT p ON e.ID_DEP = p.ID_DEP
        LEFT JOIN POSITION v ON e.ID_POS = v.ID_POS
        WHERE e.ID_EMP = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

		
        $vid_emp = $row['ID_EMP'];
        $vid = $row['ID_USER'];
        $vid_dep = $row['ID_DEP'];
        $vid_pos = $row['ID_POS'];

        $vfirstname_emp = $row['FIRSTNAME_EMP'];
        $vlastname_emp = $row['LASTNAME_EMP'];
        $vgt = $row['GioiTinh'];
        $vemail_emp = $row['EMAIL_EMP'];
        $vadd_emp = $row['ADDRESS_EMP'];
        $vphone_emp = $row['PHONE_EMP'];
        $vquyen = $row['QUYEN'];

        // Lấy tên phòng ban và tên vị trí
        $ten_phongban = $row['TEN_PHONGBAN'];  // Tên phòng ban
        $ten_vitri = $row['TEN_VITRI'];        // Tên vị trí

	}
?>
