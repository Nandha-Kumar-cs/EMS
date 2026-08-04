<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>EMS Login</title>
</head>

<body class="bg-slate-100">

    <div class="min-h-screen flex items-center justify-center">

        <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-8">

            <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">
                EMS Login
            </h2>

            <form class="space-y-5" id="login_form">

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
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
                        name="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800">
                    <p id="password_error" class="error-msg text-red-600 text-sm mt-1"></p>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex justify-between items-center text-sm">

                    <label class="flex items-center gap-2 text-slate-600">
                        <input type="checkbox">
                        Remember Me
                    </label>

                    <a href="#" class="text-blue-800 hover:underline">
                        Forgot Password?
                    </a>

                </div>

                <!-- Button -->
                <div id="login_message" class="error-msg text-sm text-center"></div>
                <button
                    class="w-full bg-blue-900 hover:bg-blue-800 text-white py-2.5 rounded-lg font-semibold transition">
                    Login
                </button>

            </form>

            <p class="text-center text-sm text-slate-600 mt-6">
                Don't have an account?
                <a href="register.php" class="text-blue-800 font-medium hover:underline">
                    Register
                </a>
            </p>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="./assets/js/login.js"></script>
</body>

</html>
