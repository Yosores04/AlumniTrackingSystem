@extends('layouts.app')

@section('title', 'Alumni Management')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (tenant()->plan)
            <div class="mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                @if (tenant()->plan->slug !== 'premium')
                                    <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-accent text-white rounded-md hover:bg-accent-dark">
                                        Upgrade Plan
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="dashboard-container">
                <h1 class="text-3xl font-bold mb-8 tracking-tight text-gray-900">Welcome to Your Alumni Portal</h1>
                
                <!-- Modern Quick Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Subscription Plan Card -->
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Subscription Plan</p>
                                <h3 class="text-2xl font-bold">{{ $subscriptionPlan['plan_name'] ?? (isset($subscriptionPlan['plan']) ? ucfirst($subscriptionPlan['plan']) : 'Free Plan') }}</h3>
                            </div>
                            <div class="bg-yellow-100 h-12 w-12 rounded-lg flex items-center justify-center">
                                <i class="fas fa-crown text-yellow-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <span>Renews on {{ \Carbon\Carbon::parse($subscriptionPlan['billing_period_end'] ?? \Carbon\Carbon::now()->addMonth())->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <!-- Total Alumni Card -->
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Alumni</p>
                                <h3 class="text-2xl font-bold">{{ number_format($totalAlumni ?? 0) }}</h3>
                            </div>
                            <div class="bg-blue-100 h-12 w-12 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-graduate text-blue-600 text-lg"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Total Instructors Card -->
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Instructors</p>
                                <h3 class="text-2xl font-bold">{{ number_format($totalInstructors ?? 0) }}</h3>
                            </div>
                            <div class="bg-green-100 h-12 w-12 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-green-600 text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Distribution Charts Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Employment Distribution Chart -->
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Alumni Employment Distribution</h3>
                        <div class="space-y-4">
                            @php
                                $employmentLabels = [
                                    'employed' => ['label' => 'Employed', 'color' => 'bg-green-500', 'icon' => 'fa-briefcase'],
                                    'unemployed' => ['label' => 'Unemployed', 'color' => 'bg-red-500', 'icon' => 'fa-search'],
                                    'self_employed' => ['label' => 'Self-Employed', 'color' => 'bg-blue-500', 'icon' => 'fa-user-tie'],
                                    'student' => ['label' => 'Student', 'color' => 'bg-purple-500', 'icon' => 'fa-graduation-cap'],
                                    'other' => ['label' => 'Other', 'color' => 'bg-gray-500', 'icon' => 'fa-question-circle'],
                                ];
                                $totalAlumni = $totalAlumni ?? 0;
                            @endphp
                            @foreach($employmentLabels as $key => $info)
                                @php
                                    $count = $employmentDistribution[$key] ?? 0;
                                    $percentage = ($totalAlumni > 0) ? ($count / $totalAlumni) * 100 : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <div class="flex items-center">
                                            <i class="fas {{ $info['icon'] }} mr-2 text-gray-700"></i>
                                            <span>{{ $info['label'] }}</span>
                                        </div>
                                        <div class="text-sm font-medium">{{ $count }} ({{ round($percentage) }}%)</div>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="{{ $info['color'] }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Batch Year Distribution Chart -->
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Batch Year Distribution</h3>
                        <div>
                            @php
                                $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500'];
                                $i = 0;
                            @endphp
                            @if(count($batchYearDistribution) > 0)
                                <div class="space-y-4">
                                    @foreach($batchYearDistribution as $year => $count)
                                        @php
                                            $percentage = ($totalAlumni > 0) ? ($count / $totalAlumni) * 100 : 0;
                                            $color = $colors[$i++ % count($colors)];
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

                <!-- Alumni Management Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Alumni Management</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('alumni.index') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center">
                                <div class="rounded-full bg-primary-100 p-3 mr-3">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div>
                                    <p class="font-medium">View Alumni</p>
                                    <p class="text-sm text-gray-500">Browse and manage alumni records</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('alumni.create') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center">
                                <div class="rounded-full bg-green-100 p-3 mr-3">
                                    <i class="fas fa-user-plus text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Add Alumni</p>
                                    <p class="text-sm text-gray-500">Create new alumni record</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('alumni.report') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center">
                                <div class="rounded-full bg-blue-100 p-3 mr-3">
                                    <i class="fas fa-file-pdf text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Generate Report</p>
                                    <p class="text-sm text-gray-500">Create PDF report of alumni data</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('alumni.reports') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center">
                                <div class="rounded-full bg-purple-100 p-3 mr-3">
                                    <i class="fas fa-chart-bar text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Reports</p>
                                    <p class="text-sm text-gray-500">View alumni statistics and reports</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart.js Configuration with Enhanced Styling
            Chart.defaults.font.family = "'Inter', 'Helvetica', 'Arial', sans-serif";
            Chart.defaults.font.size = 13;
            Chart.defaults.color = '#6B7280';
            
            // Alumni Status Chart
            const statusCtx = document.getElementById('alumniStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Employed', 'Further Studies', 'Entrepreneurs', 'Unemployed', 'Unknown'],
                    datasets: [{
                        data: [
                            {{ $employedAlumni }}, 
                            {{ $furtherStudiesAlumni }}, 
                            {{ $entrepreneurAlumni }}, 
                            {{ $unemployedAlumni }}, 
                            {{ $unknownStatusAlumni }}
                        ],
                        backgroundColor: [
                            '#4F46E5', // Indigo
                            '#0EA5E9', // Sky blue
                            '#10B981', // Green
                            '#F59E0B', // Amber
                            '#9CA3AF', // Gray
                        ],
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    layout: {
                        padding: 10
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                boxWidth: 8,
                                boxHeight: 8,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            cornerRadius: 8,
                            boxPadding: 6
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 1000
                    }
                }
            });
            
            // Alumni by Year Chart
            const yearCtx = document.getElementById('alumniYearChart').getContext('2d');
            new Chart(yearCtx, {
                type: 'bar',
                data: {
                    labels: @json($graduationYears),
                    datasets: [{
                        label: 'Alumni Count',
                        data: @json($alumniCountByYear),
                        backgroundColor: '#4F46E5',
                        borderRadius: 6,
                        borderWidth: 0,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 10
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            cornerRadius: 8,
                            boxPadding: 6
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                padding: 8,
                                maxTicksLimit: 5,
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: true,
                                drawBorder: false,
                                color: 'rgba(243, 244, 246, 1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                padding: 8,
                                maxRotation: 0,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuad'
                    }
                }
            });
        });
    </script> 