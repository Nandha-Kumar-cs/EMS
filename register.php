<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>EMS Register</title>
</head>

<body class="bg-slate-100">

    <div class="min-h-screen flex items-center justify-center py-10">

        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">

            <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">
                EMS Registration
            </h2>

            <form class="space-y-4" id='register_form'>

                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Username
                    </label>

                    <input
                        type="text"
                        placeholder="Enter your full name"
                        name="user_name"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                    <p id="username_error" class="error-msg text-red-600 text-sm mt-1"></p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="employee@company.com"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                    <p id="email_error" class="error-msg text-red-600 text-sm mt-1"></p>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Password
                    </label>

                    <input
                        type="password"
                        placeholder="Create a password"
                        name="password"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                    <p id="password_error" class="error-msg text-red-600 text-sm mt-1"></p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Confirm password"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                </div>

                <!-- Register Button -->
                <div id="register_message" class="error-msg text-sm text-center"></div>
                <button
                    class="w-full bg-blue-900 hover:bg-blue-800 text-white py-2.5 rounded-lg font-semibold transition">
                    Register
                </button>

            </form>

            <p class="text-center text-sm text-slate-600 mt-6">
                Already have an account?
                <a href="login.php" class="text-blue-800 font-medium hover:underline">
                    Login
                </a>
            </p>

        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src='./assets/js/register.js'></script>
</body>

</html>
