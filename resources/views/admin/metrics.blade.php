<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Admin Dashboard - Platform Metrics
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Current Metrics</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-50 p-6 rounded-lg shadow-sm text-center">
                <p class="text-5xl font-bold text-blue-700">{{ $metrics['total_jobs'] }}</p>
                <p class="text-lg text-blue-600 mt-2">Total Job Listings</p>
            </div>
            <div class="bg-green-50 p-6 rounded-lg shadow-sm text-center">
                <p class="text-5xl font-bold text-green-700">{{ $metrics['total_users'] }}</p>
                <p class="text-lg text-green-600 mt-2">Total Users</p>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg shadow-sm text-center">
                <p class="text-5xl font-bold text-purple-700">{{ $metrics['total_applications'] }}</p>
                <p class="text-lg text-purple-600 mt-2">Total Applications</p>
            </div>
            <div class="bg-yellow-50 p-6 rounded-lg shadow-sm text-center">
                <p class="text-5xl font-bold text-yellow-700">{{ $metrics['pending_applications'] }}</p>
                <p class="text-lg text-yellow-600 mt-2">Pending Applications</p>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-700 mb-4">Metric Updates</h3>
            <button id="refresh-metrics" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                Refresh Metrics
            </button>
            <div id="metrics-message" class="mt-4 text-sm font-medium"></div>
        </div>
    </div>

    <script>
        document.getElementById('refresh-metrics').addEventListener('click', function() {
            const messageDiv = document.getElementById('metrics-message');
            messageDiv.textContent = 'Refreshing metrics...';
            messageDiv.className = 'mt-4 text-sm font-medium text-gray-600';

            fetch('/api/admin/metrics', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                document.querySelector('.bg-blue-50 p:first-child').textContent = data.total_jobs;
                document.querySelector('.bg-green-50 p:first-child').textContent = data.total_users;
                document.querySelector('.bg-purple-50 p:first-child').textContent = data.total_applications;
                document.querySelector('.bg-yellow-50 p:first-child').textContent = data.pending_applications;

                messageDiv.className = 'mt-4 text-sm font-medium text-green-600';
                messageDiv.textContent = 'Metrics refreshed successfully!';
                setTimeout(() => messageDiv.textContent = '', 3000);
            })
            .catch(error => {
                console.error('Error refreshing metrics:', error);
                messageDiv.className = 'mt-4 text-sm font-medium text-red-600';
                messageDiv.textContent = error.message || 'Failed to refresh metrics. Please ensure you are logged in as an Admin.';
                setTimeout(() => messageDiv.textContent = '', 5000);
            });
        });
    </script>
</x-app-layout>
