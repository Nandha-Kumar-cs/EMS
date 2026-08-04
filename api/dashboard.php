<?php
require '../config/db.php';

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

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM employees");
$total_employees = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(DISTINCT department) as total FROM employees");
$total_departments = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(DISTINCT designation) as total FROM employees");
$total_designations = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COALESCE(SUM(salary), 0) as total FROM employees");
$total_payroll = mysqli_fetch_assoc($result)['total'];

$stmt = mysqli_prepare($conn, "SELECT id, employee_name, department, designation, date_of_joining FROM employees ORDER BY id DESC LIMIT 5");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$recent_employees = [];
while($row = mysqli_fetch_assoc($result)){
    $recent_employees[] = $row;
}
mysqli_stmt_close($stmt);

echo json_encode([
    'status' => 200,
    'total_employees' => $total_employees,
    'total_departments' => $total_departments,
    'total_designations' => $total_designations,
    'total_payroll' => $total_payroll,
    'recent_employees' => $recent_employees,
]);
