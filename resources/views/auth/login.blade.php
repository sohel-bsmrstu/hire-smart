<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Log In
        </h2>
    </x-slot>

    <div class="flex justify-center items-center">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl">
            <form onsubmit="event.preventDefault();">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input id="email" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" name="email" autofocus autocomplete="username">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input id="password" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" type="password" name="password" autocomplete="current-password">
                </div>

                <!-- Remember Me -->
                <div class="mb-6">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" onclick="loginUser()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition-all duration-200">
                        Log In
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        async function loginUser() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    console.log('Login successful:', data);
                    alert('Login successful!');
                    // IMPORTANT: Store the JWT token securely
                    localStorage.setItem('jwt_token', data.access_token);
                    console.log('JWT Token stored:', data.access_token);

                    // Redirect based on user role (example logic)
                    if (data.user && data.user.role === 'employer') {
                        window.location.href = '/employer/jobs'; // Redirect to employer dashboard
                    } else if (data.user && data.user.role === 'admin') {
                        window.location.href = '/admin/metrics'; // Redirect to admin dashboard
                    } else {
                        window.location.href = '/jobs'; // Default redirect for candidates
                    }

                } else {
                    console.error('Login failed:', data);
                    alert('Login failed: ' + (data.error || data.message || 'Invalid credentials'));
                }
            } catch (error) {
                console.error('Network or other error during login:', error);
                alert('An error occurred during login. Please try again.');
            }
        }
    </script>
</x-app-layout>
