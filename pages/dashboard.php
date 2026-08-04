<?php
require '../api/checkSession.php';
checkSession();
$user_name = htmlspecialchars($_SESSION['userName']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>EMS Dashboard</title>
</head>

<body class="bg-slate-100">

    <div class="min-h-screen p-6">

        <div class="max-w-6xl mx-auto">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Dashboard</h2>
                    <p class="text-slate-600 text-sm">Welcome, <?php echo $user_name; ?></p>
                </div>
                <div class="flex gap-3">
                    <a href="./employees.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-700 to-blue-900 hover:from-blue-800 hover:to-blue-950 text-white px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Manage Employees
                    </a>
                    <a href="./uploads.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-slate-600 to-slate-800 hover:from-slate-700 hover:to-slate-900 text-white px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Uploads
                    </a>
                    <a href="../logout.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total Employees</p>
                        <p id="total_employees" class="text-3xl font-bold text-slate-800 mt-1">0</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total Departments</p>
                        <p id="total_departments" class="text-3xl font-bold text-slate-800 mt-1">0</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total Designations</p>
                        <p id="total_designations" class="text-3xl font-bold text-slate-800 mt-1">0</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total Payroll</p>
                        <p id="total_payroll" class="text-2xl font-bold text-slate-800 mt-1">0</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-slate-800">Recently Added Employees</h3>
                    <a href="./employees.php" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition text-sm">
                        Add Employee
                    </a>
                </div>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700">
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Department</th>
                            <th class="px-4 py-3">Designation</th>
                            <th class="px-4 py-3">Date of Joining</th>
                        </tr>
                    </thead>
                    <tbody id="recent_table_body">
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>

</html>
