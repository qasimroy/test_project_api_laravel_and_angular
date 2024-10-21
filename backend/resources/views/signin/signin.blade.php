<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Sign In</h2>

        <form id="loginForm" class="space-y-4" method="POST" action="{{ route('api.login') }}">
            @csrf <!-- CSRF token -->

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Login
                </button>
            </div>

            <p id="error" class="text-red-500 text-sm mt-2 text-center"></p>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent the default form submission
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData, 
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('[name=_token]').value // Include CSRF token
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Save token to local storage
                    localStorage.setItem('token', data.token);
                    alert('Logged in successfully!');
                    window.location.href = '/dashboard'; // Redirect to a protected page
                } else {
                    document.getElementById('error').innerText = data.message || 'Invalid credentials';
                }
            } catch (error) {
                document.getElementById('error').innerText = 'Something went wrong. Please try again.';
            }
        });
    </script>

</body>
</html>
