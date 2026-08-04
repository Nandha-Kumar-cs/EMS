<?php
require '../api/checkSession.php';
checkSession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <style>
        .dataTables_scrollFootInner table tfoot th {
            background: #f1f5f9;
        }
        .dataTables_scrollFootInner input {
            background: #ffffff;
        }
    </style>
    <title>EMS Employees</title>
</head>

<body class="bg-slate-100">

    <div class="min-h-screen p-6">

        <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-lg p-8">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">
                    Employee Management
                </h2>
                <div class="flex gap-3">
                    <a href="./dashboard.php" class="text-sm text-blue-800 hover:underline">Dashboard</a>
                    <button onclick="openAddModal()" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg font-semibold transition text-sm">
                        Add Employee
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="employee_table" class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Mobile</th>
                            <th class="px-4 py-3">Department</th>
                            <th class="px-4 py-3">Designation</th>
                            <th class="px-4 py-3">Salary</th>
                            <th class="px-4 py-3">Date of Joining</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-slate-100 text-slate-700">
                            <th class="px-2 py-2"><input type="text" placeholder="ID" class="w-full px-2 py-1 border border-slate-300 rounded-lg text-sm"></th>
                            <th class="px-2 py-2"><input type="text" placeholder="Name" class="w-full px-2 py-1 border border-slate-300 rounded-lg text-sm"></th>
                            <th class="px-2 py-2"><input type="text" placeholder="Email" class="w-full px-2 py-1 border border-slate-300 rounded-lg text-sm"></th>
                            <th class="px-2 py-2"><input type="text" placeholder="Mobile" class="w-full px-2 py-1 border border-slate-300 rounded-lg text-sm"></th>
                            <th class="px-2 py-2"><input type="text" placeholder="Department" class="w-full px-2 py-1 border border-slate-300 rounded-lg text-sm"></th>
                            <th class="px-2 py-2"><input type="text" placeholder="Designation" class="w-full px-2 py-1 border border-slate-300 rounded-lg text-sm"></th>
                            <th class="px-2 py-2"><input type="text" placeholder="Salary" class="w-full px-2 py-1 border border-slate-300 rounded-lg text-sm"></th>
                            <th class="px-2 py-2"><input type="text" placeholder="Joining" class="w-full px-2 py-1 border border-slate-300 rounded-lg text-sm"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Employee Modal -->
    <div id="employee_modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8">

            <div class="flex justify-between items-center mb-6">
                <h3 id="employee_modal_title" class="text-xl font-bold text-slate-800">Add Employee</h3>
                <button onclick="closeEmployeeModal()" class="text-slate-500 hover:text-slate-800 text-xl">&times;</button>
            </div>

            <form id="employee_form" class="space-y-4">
                <input type="hidden" id="employee_id" name="employee_id">

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Employee Name</label>
                    <input type="text" name="employee_name" id="employee_name" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                    <p id="employee_name_error" class="error-msg text-red-600 text-sm mt-1"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                        <p id="email_error" class="error-msg text-red-600 text-sm mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Mobile Number</label>
                        <input type="text" name="mobile" id="mobile" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                        <p id="mobile_error" class="error-msg text-red-600 text-sm mt-1"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Department</label>
                        <input type="text" name="department" id="department" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                        <p id="department_error" class="error-msg text-red-600 text-sm mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Designation</label>
                        <input type="text" name="designation" id="designation" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                        <p id="designation_error" class="error-msg text-red-600 text-sm mt-1"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Salary</label>
                        <input type="number" step="0.01" name="salary" id="salary" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                        <p id="salary_error" class="error-msg text-red-600 text-sm mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Date of Joining</label>
                        <input type="date" name="date_of_joining" id="date_of_joining" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                        <p id="date_of_joining_error" class="error-msg text-red-600 text-sm mt-1"></p>
                    </div>
                </div>

                <div id="employee_message" class="error-msg text-sm text-center"></div>

                <button class="w-full bg-blue-900 hover:bg-blue-800 text-white py-2.5 rounded-lg font-semibold transition">
                    Save Employee
                </button>
            </form>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/employees.js"></script>
</body>

</html>
