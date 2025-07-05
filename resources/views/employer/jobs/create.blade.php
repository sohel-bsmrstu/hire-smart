<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Post New Job Listing
        </h2>
    </x-slot>

    <div class="flex justify-center items-center">
        <div class="w-full max-w-2xl bg-white p-8 rounded-lg shadow-xl">
            <form method="POST" action="{{ route('employer.jobs.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Job Title</label>
                    <input type="text" name="title" id="title" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('title') }}" required>
                    @error('title')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Job Description</label>
                    <textarea name="description" id="description" rows="8" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                    <input type="text" name="location" id="location" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('location') }}" required>
                    @error('location')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="salary_range" class="block text-gray-700 text-sm font-bold mb-2">Salary Range (Optional)</label>
                    <input type="text" name="salary_range" id="salary_range" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('salary_range') }}">
                    @error('salary_range')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="required_skills" class="block text-gray-700 text-sm font-bold mb-2">Required Skills (Comma-separated)</label>
                    <input type="text" name="required_skills" id="required_skills_input" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('required_skills') }}">
                    <p class="text-gray-500 text-xs italic mt-2">Example: PHP, Laravel, PostgreSQL, JavaScript</p>
                    @error('required_skills')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition-all duration-200">
                        Post Job
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Convert comma-separated string to hidden array input before form submission
        document.querySelector('form').addEventListener('submit', function(event) {
            const skillsInput = document.getElementById('required_skills_input');
            if (skillsInput.value) {
                const skillsArray = skillsInput.value.split(',').map(skill => skill.trim());
                // Create a hidden input for each skill
                skillsArray.forEach(skill => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'required_skills[]';
                    hiddenInput.value = skill;
                    event.target.appendChild(hiddenInput);
                });
            }
        });
    </script>
</x-app-layout>
