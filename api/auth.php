<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require '../config/db.php' ;
    $req = $_POST;

    if(!isset($req['action'])){
        http_response_code(422);
        echo json_encode([
            'status' => 422 , 
            'error'  => 'Invalid action !!'
        ]);
        exit;
    }

    if($req['action'] == 'register') {

        $errors = [] ; 
        $name = trim($req['user_name']);
        $email = trim($req['email']);
        $password = trim($req['password']);
        $confirm_password = trim($req['confirm_password']);

        if(empty($name)) {
            $errors['username'] = "Username is required !";
        }

        if(empty($email)) {
            $errors['email'] = 'Email is required !';
        } else if(!filter_var($email , FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid Email !' ;
        }

        if($password != $confirm_password){
            $errors['password'] = "Please ensure both the password match !" ; 
        }else if (strlen($password) < 8 ) {
            $errors['password'] = "Password length must be greater than 8 ";
        }

        if(count($errors) != 0 ) {
            http_response_code(422);
            echo json_encode([
                'status' => 422 , 
                'errors' => $errors
            ]);
            exit;
        }

        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, 's', $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if(mysqli_stmt_num_rows($check_stmt) > 0) {
            http_response_code(409);
            echo json_encode([
                'status' => 409 , 
                'errors' => ['email' => 'Email already exists !']
            ]);
            exit;
        }
        mysqli_stmt_close($check_stmt);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hashed_password);

        if(mysqli_stmt_execute($stmt)) {
            http_response_code(201);
            echo json_encode([
                'status' => 201 , 
                'message' => 'Registration successful !'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 500 , 
                'message' => 'Something went wrong, please try again !'
            ]);
        }
        mysqli_stmt_close($stmt);

    }elseif($req['action'] == 'login'){

        $errors = [] ; 
        $email = trim($req['email']);
        $password = $req['password'];

        if(empty($email)) {
            $errors['email'] = 'Email is required !';
        } else if(!filter_var($email , FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid Email !' ;
        }

        if(empty($password)) {
            $errors['password'] = 'Password is required !';
        }

        if(count($errors) != 0 ) {
            http_response_code(422);
            echo json_encode([
                'status' => 422 , 
                'errors' => $errors
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "SELECT id, username, email, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if($user && password_verify($password, $user['password'])) {
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
            session_regenerate_id(true);

            $_SESSION['userAccountId'] = $user['id'];
            $_SESSION['userName'] = $user['username'];
            $_SESSION['userEmail'] = $user['email'];

            echo json_encode([
                'status' => 200 , 
                'message' => 'Login successful !'
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'status' => 401 , 
                'message' => 'Invalid email or password !'
            ]);
        }
        mysqli_stmt_close($stmt);

    }else {
        http_response_code(422);
        echo json_encode([
            'status' => 422 , 
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
