<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require '../config/db.php' ;

    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['userAccountId'])){
        http_response_code(401);
        echo json_encode([
            'status' => 401,
            'message' => 'Unauthorized access !'
        ]);
        exit;
    }

    $req = $_POST;

    if(!isset($req['action'])){
        http_response_code(422);
        echo json_encode([
            'status' => 422, 
            'error'  => 'Invalid action !!'
        ]);
        exit;
    }

    if($req['action'] == 'upload') {

        $user_id = $_SESSION['userAccountId'];

        if(!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK){
            http_response_code(422);
            echo json_encode([
                'status' => 422,
                'errors' => ['file' => 'Please choose a file to upload !']
            ]);
            exit;
        }

        $file = $_FILES['file'];
        $employee_id = isset($req['employee_id']) ? $req['employee_id'] : '';
        $max_size = 2 * 1024 * 1024;
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
        $allowed_mime = ['image/jpeg', 'image/png', 'application/pdf'];

        $errors = [];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(empty($employee_id)){
            $errors['employee_id'] = 'Please select an employee !';
        } else {
            $check_stmt = mysqli_prepare($conn, "SELECT id FROM employees WHERE id = ?");
            mysqli_stmt_bind_param($check_stmt, 'i', $employee_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            if(mysqli_stmt_num_rows($check_stmt) == 0){
                $errors['employee_id'] = 'Invalid employee !';
            }
            mysqli_stmt_close($check_stmt);
        }

        if(!in_array($ext, $allowed_ext)){
            $errors['file'] = 'Only JPG, PNG and PDF files are allowed !';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if(!in_array($mime, $allowed_mime)){
            $errors['file'] = 'Only JPG, PNG and PDF files are allowed !';
        }

        if($file['size'] > $max_size){
            $errors['file'] = 'File size must be less than 2 MB !';
        }

        if(count($errors) != 0){
            http_response_code(422);
            echo json_encode([
                'status' => 422,
                'errors' => $errors
            ]);
            exit;
        }

        $original_name = $file['name'];
        $stored_name = time() . '_' . uniqid() . '.' . $ext;
        $file_type = $mime;
        $file_size = $file['size'];

        if(!move_uploaded_file($file['tmp_name'], '../uploads/' . $stored_name)){
            http_response_code(500);
            echo json_encode([
                'status' => 500,
                'message' => 'Failed to upload file, please try again !'
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO uploads (user_id, employee_id, original_name, stored_name, file_type, file_size) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iisssi', $user_id, $employee_id, $original_name, $stored_name, $file_type, $file_size);

        if(mysqli_stmt_execute($stmt)){
            http_response_code(201);
            echo json_encode([
                'status' => 201,
                'message' => 'File uploaded successfully !'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 500,
                'message' => 'Something went wrong, please try again !'
            ]);
        }
        mysqli_stmt_close($stmt);

    }elseif($req['action'] == 'list'){

        $stmt = mysqli_prepare($conn, "SELECT uploads.id, uploads.original_name, uploads.stored_name, uploads.file_type, uploads.file_size, uploads.created_at, employees.employee_name FROM uploads INNER JOIN employees ON uploads.employee_id = employees.id ORDER BY uploads.id DESC");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $files = [];
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        mysqli_stmt_close($stmt);

        echo json_encode([
            'status' => 200,
            'files' => $files
        ]);

    }elseif($req['action'] == 'delete'){

        $upload_id = $req['upload_id'];

        if(empty($upload_id)){
            http_response_code(422);
            echo json_encode([
                'status' => 422,
                'message' => 'Invalid file !'
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "SELECT stored_name FROM uploads WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $upload_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $upload = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if(!$upload){
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'message' => 'File not found !'
            ]);
            exit;
        }

        if(file_exists('../uploads/' . $upload['stored_name'])){
            unlink('../uploads/' . $upload['stored_name']);
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM uploads WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $upload_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo json_encode([
            'status' => 200,
            'message' => 'File deleted successfully !'
        ]);

    }else {
        http_response_code(422);
        echo json_encode([
            'status' => 422, 
            'error'  => 'Invalid action !!'
        ]);
    }
}else {
    http_response_code(405);
    echo json_encode([
        'status' => 405, 
        'error'  => 'Invalid Request !!' 
    ]);
}
