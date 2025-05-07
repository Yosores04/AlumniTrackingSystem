@extends('layouts.instructor')

@section('title', 'Instructor Dashboard')

@section('content')
<div class="content-card">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold section-heading">Dashboard Overview</h2>
        <div class="flex space-x-3">
            <a href="{{ route('instructor.alumni.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus mr-2"></i> Add Alumni
            </a>
            <a href="{{ url('/support') }}" class="btn btn-secondary">
                <i class="fas fa-headset mr-2"></i> Support
            </a>
        </div>
    </div>

    <!-- Support Banner -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-lg"></i>
            </div>
            <div class="ml-3 flex-grow">
                <p class="text-sm text-blue-700">
                    Access the support system to manage alumni inquiries or request assistance. 
                    <a href="{{ url('/support') }}" class="text-blue-700 font-bold hover:underline">Go to Support Center →</a>
                    <br><small>(Debug: Current port is {{ request()->getPort() }} | <a href="{{ url('/debug-routes') }}" class="underline">View All Routes</a>)</small>
                </p>
            </div>
        </div>
    </div>

    <!-- Analytics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Alumni</p>
                    <h3 class="text-2xl font-bold">{{ number_format($totalAlumni ?? 0) }}</h3>
                </div>
                <div class="bg-blue-100 h-12 w-12 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <span class="{{ ($newAlumni > 0) ? 'text-green-600' : 'text-gray-600' }}">
                    <i class="fas {{ ($newAlumni > 0) ? 'fa-arrow-up' : 'fa-minus' }} mr-1"></i>
                    {{ $newAlumni }} new this month
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Employed Alumni</p>
                    <h3 class="text-2xl font-bold">{{ number_format($employedAlumni ?? 0) }}</h3>
                </div>
                <div class="bg-green-100 h-12 w-12 rounded-lg flex items-center justify-center">
                    <i class="fas fa-briefcase text-green-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                @if($totalAlumni > 0)
                    <span class="text-green-600">
                        {{ round(($employedAlumni / $totalAlumni) * 100) }}% employment rate
                    </span>
                @else
                    <span class="text-gray-600">No data available</span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Unemployment Rate</p>
                    <h3 class="text-2xl font-bold">
                        @php
                            $unemployedAlumni = \App\Models\Alumni::where('employment_status', 'unemployed')->count();
                            $unemploymentRate = ($totalAlumni > 0) ? ($unemployedAlumni / $totalAlumni) * 100 : 0;
                        @endphp
                        {{ round($unemploymentRate) }}%
                    </h3>
                </div>
                <div class="bg-red-100 h-12 w-12 rounded-lg flex items-center justify-center">
                    <i class="fas fa-search text-red-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <span class="{{ ($unemploymentRate < 20) ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas {{ ($unemploymentRate < 20) ? 'fa-thumbs-up' : 'fa-thumbs-down' }} mr-1"></i>
                    {{ ($unemploymentRate < 20) ? 'Good' : 'Needs improvement' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Verification Status</p>
                    <h3 class="text-2xl font-bold">
                        @php
                            $verifiedAlumni = \App\Models\Alumni::where('is_verified', true)->count();
                            $verificationRate = ($totalAlumni > 0) ? ($verifiedAlumni / $totalAlumni) * 100 : 0;
                        @endphp
                        {{ round($verificationRate) }}%
                    </h3>
                </div>
                <div class="bg-purple-100 h-12 w-12 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <span>{{ $verifiedAlumni }} verified out of {{ $totalAlumni }}</span>
            </div>
        </div>
    </div>

    <!-- Employment Distribution Chart -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
            <h3 class="text-lg font-semibold mb-4">Employment Distribution</h3>
            <div class="space-y-4">
                @php
                    $employmentStatuses = [
                        'employed' => ['count' => \App\Models\Alumni::where('employment_status', 'employed')->count(), 'color' => 'bg-green-500', 'icon' => 'fa-briefcase', 'label' => 'Employed'],
                        'unemployed' => ['count' => \App\Models\Alumni::where('employment_status', 'unemployed')->count(), 'color' => 'bg-red-500', 'icon' => 'fa-search', 'label' => 'Unemployed'],
                        'self_employed' => ['count' => \App\Models\Alumni::where('employment_status', 'self_employed')->count(), 'color' => 'bg-blue-500', 'icon' => 'fa-user-tie', 'label' => 'Self-Employed'],
                        'student' => ['count' => \App\Models\Alumni::where('employment_status', 'student')->count(), 'color' => 'bg-purple-500', 'icon' => 'fa-graduation-cap', 'label' => 'Student'],
                        'other' => ['count' => \App\Models\Alumni::where('employment_status', 'other')->count(), 'color' => 'bg-gray-500', 'icon' => 'fa-question-circle', 'label' => 'Other'],
                    ];
                @endphp

                @foreach($employmentStatuses as $status => $data)
                    @php
                        $percentage = ($totalAlumni > 0) ? ($data['count'] / $totalAlumni) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex items-center">
                                <i class="fas {{ $data['icon'] }} mr-2 text-gray-700"></i>
                                <span>{{ $data['label'] }}</span>
                            </div>
                            <div class="text-sm font-medium">{{ $data['count'] }} ({{ round($percentage) }}%)</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="{{ $data['color'] }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Batch Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
            <h3 class="text-lg font-semibold mb-4">Batch Year Distribution</h3>
            <div>
                @php
                    $batchYears = \App\Models\Alumni::select('batch_year')
                        ->whereNotNull('batch_year')
                        ->distinct()
                        ->orderBy('batch_year', 'desc')
                        ->take(5)
                        ->pluck('batch_year');
                        
                    $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500'];
                @endphp

                @if(count($batchYears) > 0)
                    <div class="space-y-4">
                        @foreach($batchYears as $index => $year)
                            @php
                                $count = \App\Models\Alumni::where('batch_year', $year)->count();
                                $percentage = ($totalAlumni > 0) ? ($count / $totalAlumni) * 100 : 0;
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span>Batch {{ $year }}</span>
                                    <div class="text-sm font-medium">{{ $count }} ({{ round($percentage) }}%)</div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-6">
                        <i class="fas fa-chart-bar text-gray-300 text-4xl mb-3"></i>
                        <p>No batch year data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions and Recent Alumni -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
            <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
            <ul class="space-y-3">
                <li>
                    <a href="{{ route('instructor.alumni.create') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-user-plus text-blue-600"></i>
                        </div>
                        <span>Register New Alumni</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.alumni.index') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-search text-purple-600"></i>
                        </div>
                        <span>Search Alumni</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.alumni.report') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-file-pdf text-green-600"></i>
                        </div>
                        <span>Generate PDF Report</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.alumni.reports') }}" class="flex items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                        <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-chart-pie text-yellow-600"></i>
                        </div>
                        <span>Generate Reports</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-100 md:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Recently Added Alumni</h3>
                <a href="{{ route('instructor.alumni.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            @if(isset($recentAlumni) && count($recentAlumni) > 0)
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Batch</th>
                                <th>Added On</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAlumni as $alumni)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            @if($alumni->profile_photo_path)
                                                <div class="avatar mr-3">
                                                    <img src="{{ Storage::url($alumni->profile_photo_path) }}" alt="{{ $alumni->name }}">
                                                </div>
                                            @else
                                                <div class="avatar bg-blue-100 text-blue-600 mr-3">
                                                    <span class="font-medium">{{ substr($alumni->first_name, 0, 1) . substr($alumni->last_name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $alumni->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $alumni->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $alumni->batch_year ?? 'N/A' }}</td>
                                    <td>{{ $alumni->created_at->format('M d, Y') }}</td>
                                    <td class="text-right">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('instructor.alumni.show', $alumni->id) }}" class="btn btn-secondary py-1 px-2" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('instructor.alumni.edit', $alumni->id) }}" class="btn btn-secondary py-1 px-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                    <p class="text-lg font-medium">No alumni records found</p>
                    <p class="mt-1 text-sm">Get started by adding your first alumni record</p>
                    <a href="{{ route('instructor.alumni.create') }}" class="btn btn-primary mt-4">
                        <i class="fas fa-plus mr-2"></i> Add Alumni
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 