<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            {{ $job->title }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
        <div class="mb-6 border-b pb-4">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $job->title }}</h3>
            <p class="text-gray-700 mb-4">{{ $job->description }}</p>
            <p class="text-gray-600 text-sm mb-1"><strong class="font-semibold">Location:</strong> {{ $job->location }}</p>
            @if ($job->salary_range)
                <p class="text-gray-600 text-sm mb-1"><strong class="font-semibold">Salary Range:</strong> {{ $job->salary_range }}</p>
            @endif
            @if ($job->required_skills)
                <p class="text-gray-600 text-sm mb-1"><strong class="font-semibold">Required Skills:</strong> {{ $job->required_skills }}</p>
            @endif
            <p class="text-gray-600 text-sm mt-2">Posted by: {{ $job->employer->name }} on {{ $job->created_at->format('M d, Y') }}</p>
        </div>

        @auth
            @if (Auth::user()->isCandidate())
                <h4 class="text-xl font-bold text-gray-700 mb-4">Apply for this Job</h4>
                <form id="apply-form" class="space-y-4" method="POST" action="/api/applications">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    <div>
                        <label for="cover_letter" class="block text-gray-700 text-sm font-bold mb-2">Cover Letter (Optional)</label>
                        <textarea id="cover_letter" name="cover_letter" rows="6" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        @error('cover_letter')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                        Submit Application
                    </button>
                    <div id="application-message" class="mt-4 text-sm font-medium"></div>
                </form>
            @elseif (Auth::user()->isEmployer() && Auth::user()->id === $job->user_id)
                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('employer.jobs.edit', $job->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                        Edit Job
                    </a>
                    <form action="{{ route('employer.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                            Delete Job
                        </button>
                    </form>
                    <a href="#" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                        View Applications ({{ $job->applications->count() }})
                    </a>
                </div>
            @endif
        @else
            <p class="text-gray-600 mt-6">Please <a href="{{ route('login') }}" class="text-blue-500 hover:underline">log in</a> as a candidate to apply for this job.</p>
        @endauth
    </div>
</x-app-layout>
