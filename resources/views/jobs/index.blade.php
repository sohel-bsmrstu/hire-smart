<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Available Job Listings
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-700 mb-4">Filter Jobs</h3>
            <form id="job-filter-form" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="keyword" id="keyword" placeholder="Keyword (title, description)" class="p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input type="text" name="location" id="location" placeholder="Location" class="p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input type="text" name="skills" id="skills" placeholder="Skill" class="p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" class="col-span-1 md:col-span-3 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all duration-200">
                    Apply Filters
                </button>
            </form>
        </div>

        <div id="job-listings" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($jobs as $job)
                <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $job->title }}</h3>
                        <p class="text-gray-600 mb-2 truncate">{{ $job->description }}</p>
                        <p class="text-gray-700 text-sm mb-1"><strong class="font-semibold">Location:</strong> {{ $job->location }}</p>
                        @if ($job->salary_range)
                            <p class="text-gray-700 text-sm mb-1"><strong class="font-semibold">Salary:</strong> {{ $job->salary_range }}</p>
                        @endif
                        @if ($job->required_skills)
                            <p class="text-gray-700 text-sm mb-4"><strong class="font-semibold">Skills:</strong> {{ $job->required_skills }}</p>
                        @endif
                    </div>
                    <a href="{{ route('jobs.show', $job->id) }}" class="mt-4 inline-block bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg text-center transition-all duration-200">
                        View Details
                    </a>
                </div>
            @empty
                <p class="text-gray-600 text-center col-span-full">No jobs found matching your criteria.</p>
            @endforelse
        </div>
    </div>

    <script>
        document.getElementById('job-filter-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const keyword = document.getElementById('keyword').value;
            const location = document.getElementById('location').value;
            const skills = document.getElementById('skills').value;

            const queryParams = new URLSearchParams();
            if (keyword) queryParams.append('keyword', keyword);
            if (location) queryParams.append('location', location);
            if (skills) queryParams.append('skills', skills);

            fetch(`/api/jobs?${queryParams.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const jobListingsDiv = document.getElementById('job-listings');
                jobListingsDiv.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(job => {
                        const jobCard = `
                            <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">${job.title}</h3>
                                    <p class="text-gray-600 mb-2 truncate">${job.description}</p>
                                    <p class="text-gray-700 text-sm mb-1"><strong class="font-semibold">Location:</strong> ${job.location}</p>
                                    ${job.salary_range ? `<p class="text-gray-700 text-sm mb-1"><strong class="font-semibold">Salary:</strong> ${job.salary_range}</p>` : ''}
                                    ${job.required_skills && job.required_skills.length > 0 ? `<p class="text-gray-700 text-sm mb-4"><strong class="font-semibold">Skills:</strong> ${job.required_skills}</p>` : ''}
                                </div>
                                <a href="/jobs/${job.id}" class="mt-4 inline-block bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg text-center transition-all duration-200">
                                    View Details
                                </a>
                            </div>
                        `;
                        jobListingsDiv.innerHTML += jobCard;
                    });
                } else {
                    jobListingsDiv.innerHTML = '<p class="text-gray-600 text-center col-span-full">No jobs found matching your criteria.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching jobs:', error);
                document.getElementById('job-listings').innerHTML = '<p class="text-red-500 text-center col-span-full">Failed to load jobs. Please try again.</p>';
            });
        });
    </script>
</x-app-layout>
