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
    <title>EMS Uploads</title>
</head>

<body class="bg-slate-100">

    <div class="min-h-screen p-6">

        <div class="max-w-4xl mx-auto">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">File Upload</h2>
                    <p class="text-slate-600 text-sm">Profile images and supporting documents</p>
                </div>
                <div class="flex gap-3">
                    <a href="./dashboard.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-700 to-blue-900 hover:from-blue-800 hover:to-blue-950 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition text-sm">
                        Dashboard
                    </a>
                    <a href="../logout.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition text-sm">
                        Logout
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                <h3 class="text-xl font-bold text-slate-800 mb-4">Upload File</h3>

                <form id="upload_form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Employee</label>
                        <select name="employee_id" id="employee_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                            <option value="">Select Employee</option>
                        </select>
                        <p id="employee_id_error" class="error-msg text-red-600 text-sm mt-1"></p>
                    </div>
                    <div>
                        <input
                            type="file"
                            id="file"
                            name="file"
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                        <p class="text-xs text-slate-500 mt-1">Allowed formats: JPG, PNG, PDF (maximum 2 MB)</p>
                        <p id="file_error" class="error-msg text-red-600 text-sm mt-1"></p>
                    </div>

                    <div id="upload_message" class="error-msg text-sm text-center"></div>

                    <button class="w-full bg-blue-900 hover:bg-blue-800 text-white py-2.5 rounded-lg font-semibold transition">
                        Upload File
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-xl font-bold text-slate-800 mb-4">Uploaded Files</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700">
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">File Name</th>
                                <th class="px-4 py-3">Employee</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Size</th>
                                <th class="px-4 py-3">Uploaded At</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="upload_table_body">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/uploads.js"></script>
</body>

</html>
