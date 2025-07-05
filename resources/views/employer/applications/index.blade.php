<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Applications for "{{ $job->title }}"
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
        @if ($applications->isEmpty())
            <p class="text-gray-600 text-center">No applications received for this job yet.</p>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach ($applications as $application)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Applicant: {{ $application->candidate->name }}</h3>
                        <p class="text-gray-700 text-sm mb-2"><strong class="font-semibold">Email:</strong> {{ $application->candidate->email }}</p>
                        <p class="text-gray-700 mb-4">{{ $application->cover_letter ?: 'No cover letter provided.' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700">Status: <span id="status-{{ $application->id }}" class="
                                @if($application->status === 'pending') text-yellow-600
                                @elseif($application->status === 'reviewed') text-blue-600
                                @elseif($application->status === 'accepted') text-green-600
                                @elseif($application->status === 'rejected') text-red-600
                                @endif
                            ">{{ ucfirst($application->status) }}</span></span>

                            <select
                                data-application-id="{{ $application->id }}"
                                class="status-dropdown bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            >
                                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div id="message-{{ $application->id }}" class="mt-2 text-sm font-medium"></div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
            dropdown.addEventListener('change', function() {
                const applicationId = this.dataset.applicationId;
                const newStatus = this.value;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const messageDiv = document.getElementById(`message-${applicationId}`);
                const statusSpan = document.getElementById(`status-${applicationId}`);

                fetch(`/api/employer/applications/${applicationId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    messageDiv.className = 'mt-2 text-sm font-medium text-green-600';
                    messageDiv.textContent = data.message;
                    statusSpan.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    statusSpan.className = 'text-sm font-semibold';
                    if (newStatus === 'pending') statusSpan.classList.add('text-yellow-600');
                    else if (newStatus === 'reviewed') statusSpan.classList.add('text-blue-600');
                    else if (newStatus === 'accepted') statusSpan.classList.add('text-green-600');
                    else if (newStatus === 'rejected') statusSpan.classList.add('text-red-600');

                    // Clear message after a few seconds
                    setTimeout(() => messageDiv.textContent = '', 3000);
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    messageDiv.className = 'mt-2 text-sm font-medium text-red-600';
                    messageDiv.textContent = error.message || 'Failed to update status.';
                    setTimeout(() => messageDiv.textContent = '', 5000);
                });
            });
        });
    </script>
</x-app-layout>
