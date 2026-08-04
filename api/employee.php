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

    if($req['action'] == 'add') {

        $validated = validateEmployee($req);
        $errors = $validated['errors'];
        $data = $validated['data'];

        if(count($errors) == 0){
            $check_stmt = mysqli_prepare($conn, "SELECT id FROM employees WHERE email = ?");
            mysqli_stmt_bind_param($check_stmt, 's', $data['email']);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            if(mysqli_stmt_num_rows($check_stmt) > 0){
                $errors['email'] = 'Email already exists !';
            }
            mysqli_stmt_close($check_stmt);
        }

        if(count($errors) != 0){
            http_response_code(422);
            echo json_encode([
                'status' => 422,
                'errors' => $errors
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO employees (employee_name, email, mobile, department, designation, salary, date_of_joining) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssssds', $data['name'], $data['email'], $data['mobile'], $data['department'], $data['designation'], $data['salary'], $data['date_of_joining']);

        if(mysqli_stmt_execute($stmt)){
            http_response_code(201);
            echo json_encode([
                'status' => 201,
                'message' => 'Employee added successfully !'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 500,
                'message' => 'Something went wrong, please try again !'
            ]);
        }
        mysqli_stmt_close($stmt);

    }elseif($req['action'] == 'update'){

        $employee_id = $req['employee_id'];
        $validated = validateEmployee($req);
        $errors = $validated['errors'];
        $data = $validated['data'];

        if(empty($employee_id)){
            $errors['employee_name'] = 'Invalid employee !';
        }

        if(count($errors) == 0){
            $check_stmt = mysqli_prepare($conn, "SELECT id FROM employees WHERE email = ? AND id != ?");
            mysqli_stmt_bind_param($check_stmt, 'si', $data['email'], $employee_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            if(mysqli_stmt_num_rows($check_stmt) > 0){
                $errors['email'] = 'Email already exists !';
            }
            mysqli_stmt_close($check_stmt);
        }

        if(count($errors) != 0){
            http_response_code(422);
            echo json_encode([
                'status' => 422,
                'errors' => $errors
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "UPDATE employees SET employee_name = ?, email = ?, mobile = ?, department = ?, designation = ?, salary = ?, date_of_joining = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'sssssdsi', $data['name'], $data['email'], $data['mobile'], $data['department'], $data['designation'], $data['salary'], $data['date_of_joining'], $employee_id);

        if(mysqli_stmt_execute($stmt)){
            echo json_encode([
                'status' => 200,
                'message' => 'Employee updated successfully !'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 500,
                'message' => 'Something went wrong, please try again !'
            ]);
        }
        mysqli_stmt_close($stmt);

    }elseif($req['action'] == 'delete'){

        $employee_id = $req['employee_id'];

        if(empty($employee_id)){
            http_response_code(422);
            echo json_encode([
                'status' => 422,
                'message' => 'Invalid employee !'
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM employees WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $employee_id);

        if(mysqli_stmt_execute($stmt)){
            echo json_encode([
                'status' => 200,
                'message' => 'Employee deleted successfully !'
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

        $search = isset($req['search']) ? trim($req['search']) : '';
        $page = isset($req['page']) ? (int)$req['page'] : 1;
        $per_page = 5;

        if($page < 1){
            $page = 1;
        }

        $search_query = '';
        $search_types = '';
        $search_params = [];

        if($search != ''){
            $search_query = "WHERE employee_name LIKE ? OR email LIKE ?";
            $like_search = '%' . $search . '%';
            $search_types = 'ss';
            $search_params = [$like_search, $like_search];
        }

        $count_stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM employees " . $search_query);
        if($search_types != ''){
            mysqli_stmt_bind_param($count_stmt, $search_types, ...$search_params);
        }
        mysqli_stmt_execute($count_stmt);
        $count_result = mysqli_stmt_get_result($count_stmt);
        $total_employees = mysqli_fetch_assoc($count_result)['total'];
        mysqli_stmt_close($count_stmt);

        $total_pages = max(1, ceil($total_employees / $per_page));
        if($page > $total_pages){
            $page = $total_pages;
        }
        $offset = ($page - 1) * $per_page;

        $data_stmt = mysqli_prepare($conn, "SELECT id, employee_name, email, mobile, department, designation, salary, date_of_joining FROM employees " . $search_query . " ORDER BY id DESC LIMIT " . $offset . ", " . $per_page);
        if($search_types != ''){
            mysqli_stmt_bind_param($data_stmt, $search_types, ...$search_params);
        }
        mysqli_stmt_execute($data_stmt);
        $data_result = mysqli_stmt_get_result($data_stmt);

        $employees = [];
        while($row = mysqli_fetch_assoc($data_result)){
            $employees[] = $row;
        }
        mysqli_stmt_close($data_stmt);

        echo json_encode([
            'status' => 200,
            'employees' => $employees,
            'page' => $page,
            'per_page' => $per_page,
            'total' => $total_employees,
            'total_pages' => $total_pages,
        ]);

    }elseif($req['action'] == 'get'){

        $employee_id = $req['employee_id'];

        if(empty($employee_id)){
            http_response_code(422);
            echo json_encode([
                'status' => 422,
                'message' => 'Invalid employee !'
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "SELECT id, employee_name, email, mobile, department, designation, salary, date_of_joining FROM employees WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $employee_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $employee = mysqli_fetch_assoc($result);

        if($employee){
            echo json_encode([
                'status' => 200,
                'employee' => $employee
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'message' => 'Employee not found !'
            ]);
        }
        mysqli_stmt_close($stmt);

    }elseif($req['action'] == 'datatables'){

        $columns = ['id', 'employee_name', 'email', 'mobile', 'department', 'designation', 'salary', 'date_of_joining'];

        $draw = isset($req['draw']) ? (int)$req['draw'] : 1;
        $start = isset($req['start']) ? (int)$req['start'] : 0;
        $length = isset($req['length']) ? (int)$req['length'] : 5;
        $search_value = isset($req['search']['value']) ? trim($req['search']['value']) : '';

        if($length < 1){
            $length = 5;
        }
        if($start < 0){
            $start = 0;
        }

        $where = [];
        $types = '';
        $params = [];

        if($search_value != ''){
            $where[] = "(employee_name LIKE ? OR email LIKE ?)";
            $like = '%' . $search_value . '%';
            $types .= 'ss';
            $params[] = $like;
            $params[] = $like;
        }

        for($i = 0; $i < count($columns); $i++){
            if(isset($req['columns'][$i]['search']['value']) && trim($req['columns'][$i]['search']['value']) != ''){
                $col_search = $req['columns'][$i]['search']['value'];
                $where[] = $columns[$i] . " LIKE ?";
                $types .= 's';
                $params[] = '%' . $col_search . '%';
            }
        }

        $where_clause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

        $total_stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM employees");
        mysqli_stmt_execute($total_stmt);
        $total_result = mysqli_stmt_get_result($total_stmt);
        $records_total = mysqli_fetch_assoc($total_result)['total'];
        mysqli_stmt_close($total_stmt);

        $filtered_stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM employees " . $where_clause);
        if(count($params) > 0){
            mysqli_stmt_bind_param($filtered_stmt, $types, ...$params);
        }
        mysqli_stmt_execute($filtered_stmt);
        $filtered_result = mysqli_stmt_get_result($filtered_stmt);
        $records_filtered = mysqli_fetch_assoc($filtered_result)['total'];
        mysqli_stmt_close($filtered_stmt);

        $order_by = 'id DESC';
        if(isset($req['order'][0]['column'])){
            $order_index = (int)$req['order'][0]['column'];
            if(isset($columns[$order_index])){
                $dir = strtoupper($req['order'][0]['dir']);
                if($dir != 'ASC' && $dir != 'DESC'){
                    $dir = 'ASC';
                }
                $order_by = $columns[$order_index] . ' ' . $dir;
            }
        }

        $data_stmt = mysqli_prepare($conn, "SELECT id, employee_name, email, mobile, department, designation, salary, date_of_joining FROM employees " . $where_clause . " ORDER BY " . $order_by . " LIMIT " . $start . ", " . $length);
        if(count($params) > 0){
            mysqli_stmt_bind_param($data_stmt, $types, ...$params);
        }
        mysqli_stmt_execute($data_stmt);
        $data_result = mysqli_stmt_get_result($data_stmt);

        $rows = [];
        while($row = mysqli_fetch_assoc($data_result)){
            $rows[] = $row;
        }
        mysqli_stmt_close($data_stmt);

        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_filtered,
            'data' => $rows,
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

function validateEmployee($req){
    $errors = [];

    $name = trim($req['employee_name']);
    $email = trim($req['email']);
    $mobile = trim($req['mobile']);
    $department = trim($req['department']);
    $designation = trim($req['designation']);
    $salary = trim($req['salary']);
    $date_of_joining = trim($req['date_of_joining']);

    if(empty($name)) {
        $errors['employee_name'] = 'Employee name is required !';
    }

    if(empty($email)) {
        $errors['email'] = 'Email is required !';
    } else if(!filter_var($email , FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid Email !';
    }

    if(empty($mobile)) {
        $errors['mobile'] = 'Mobile number is required !';
    } else if(!preg_match('/^[0-9]{10,15}$/', $mobile)) {
        $errors['mobile'] = 'Invalid mobile number !';
    }

    if(empty($department)) {
        $errors['department'] = 'Department is required !';
    }

    if(empty($designation)) {
        $errors['designation'] = 'Designation is required !';
    }

    if(empty($salary)) {
        $errors['salary'] = 'Salary is required !';
    } else if(!is_numeric($salary) || $salary <= 0) {
        $errors['salary'] = 'Invalid salary !';
    }

    if(empty($date_of_joining)) {
        $errors['date_of_joining'] = 'Date of joining is required !';
    } else if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_of_joining)) {
        $errors['date_of_joining'] = 'Invalid date format !';
    }

    return [
        'errors' => $errors,
        'data' => [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'department' => $department,
            'designation' => $designation,
            'salary' => $salary,
            'date_of_joining' => $date_of_joining,
        ]
    ];
}
