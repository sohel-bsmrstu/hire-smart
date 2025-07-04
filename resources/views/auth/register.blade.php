<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Register
        </h2>
    </x-slot>

    <div class="flex justify-center items-center">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl">
            <form onsubmit="event.preventDefault();">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input id="name" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input id="email" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input id="password" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                    <input id="password_confirmation" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="mb-6">
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Register As:</label>
                    <select id="role" name="role" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="candidate" {{ old('role') == 'candidate' ? 'selected' : '' }}>Candidate</option>
                        <option value="employer" {{ old('role') == 'employer' ? 'selected' : '' }}>Employer</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" onclick="registerUser()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition-all duration-200">
                        Register
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{route('login')}}">
                        Already registered?
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function registerUser() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const role = document.getElementById('role').value;

        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password,
                    password_confirmation: passwordConfirmation,
                    role: role
                })
            });

            const data = await response.json();

            if (response.ok) {
                console.log('Registration successful:', data);
                alert('Registration successful! You can now log in.');
                window.location.href = "/login";
            }
            else if (response.status === 400) {
                let errors = Object.values(data).join('\n');
                alert(errors);
                // Optionally, redirect to login page or auto-login
            } else {
                console.error('Registration failed:', data);
                alert('Registration failed: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Network or other error during registration:', error);
            alert('An error occurred during registration. Please try again.');
        }
    }
    </script>
</x-app-layout>
