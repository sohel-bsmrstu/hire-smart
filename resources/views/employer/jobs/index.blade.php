<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            My Posted Jobs
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
        <div class="mb-6 flex justify-end">
            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                Post New Job
            </a>
        </div>

        @if ($jobs->isEmpty())
            <p class="text-gray-600 text-center">You haven't posted any jobs yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($jobs as $job)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $job->title }}</h3>
                            <p class="text-gray-600 mb-2 truncate">{{ $job->description }}</p>
                            <p class="text-gray-700 text-sm mb-1"><strong class="font-semibold">Location:</strong> {{ $job->location }}</p>
                            @if ($job->salary_range)
                                <p class="text-gray-700 text-sm mb-1"><strong class="font-semibold">Salary:</strong> {{ $job->salary_range }}</p>
                            @endif
                            @if ($job->required_skills)
                                <p class="text-gray-700 text-sm mb-4"><strong class="font-semibold">Skills:</strong> {{ implode(', ', $job->required_skills) }}</p>
                            @endif
                            <p class="text-gray-700 text-sm mb-4"><strong class="font-semibold">Applications:</strong> {{ $job->applications_count }}</p>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('jobs.show', $job->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg text-center transition-all duration-200">
                                View Details
                            </a>
                            <a href="{{ route('employer.jobs.edit', $job->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg text-center transition-all duration-200">
                                Edit
                            </a>
                            <a href="{{ route('employer.jobs.applications', $job->id) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg text-center transition-all duration-200">
                                View Apps
                            </a>
                            <form action="{{ route('employer.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg text-center transition-all duration-200">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
