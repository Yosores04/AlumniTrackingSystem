<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Alumni Dashboard') }}
        </h2>
    </x-slot>

    <!-- Custom CSS for dashboard enhancements -->
    <style>
        .dashboard-container {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card {
            border-radius: 12px;
            transition: all 0.3s;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        
        .stat-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-body {
            padding: 20px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.8;
        }
        
        .stat-icon {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .card-primary {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            color: white;
        }
        
        .card-secondary {
            background: linear-gradient(135deg, #0EA5E9, #0284C7);
            color: white;
        }
        
        .card-accent {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
        }
        
        .card-neutral {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: white;
        }
        
        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 1.5rem;
            position: relative;
            padding-left: 1rem;
        }
        
        .section-title:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #4F46E5, #7C3AED);
            border-radius: 4px;
        }
        
        .activity-card, .link-card, .event-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: white;
            transition: all 0.3s;
        }
        
        .activity-card:hover, .link-card:hover, .event-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        
        .quick-link {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            transition: all 0.3s;
        }
        
        .quick-link:hover {
            background-color: #F9FAFB;
            transform: translateX(5px);
        }
        
        .link-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
            transition: all 0.3s;
        }
        
        .quick-link:hover .link-icon {
            transform: scale(1.1);
        }
        
        .event-date {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            overflow: hidden;
            width: 60px;
            margin-right: 1rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .date-month {
            background-color: #4F46E5;
            color: white;
            width: 100%;
            padding: 4px 0;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .date-day {
            background-color: white;
            color: #1F2937;
            width: 100%;
            padding: 6px 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .chart-container {
            padding: 1.5rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            height: 100%;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            color: #9CA3AF;
        }
        
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>

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
                
                <!-- Subscription Info -->
                <div class="mb-6 p-4 rounded-lg border border-gray-200 bg-white">
                    <div class="flex items-center">
                        <div class="mr-2 text-lg font-semibold">
                            @if (tenant()->plan)
                            Subscription: <span class="font-bold text-accent">{{ tenant()->plan->name }}</span>
                            @else
                            Subscription: <span class="font-bold text-accent">{{ isset($subscriptionPlan['plan_name']) ? $subscriptionPlan['plan_name'] : (isset($subscriptionPlan['plan']) ? ucfirst($subscriptionPlan['plan']) : 'Free Plan') }}</span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">
                            @if (tenant()->plan)
                            · {{ ucfirst(tenant()->billing_cycle) }} billing · Renews on {{ tenant()->plan_expires_at ? (is_string(tenant()->plan_expires_at) ? date('M d, Y', strtotime(tenant()->plan_expires_at)) : tenant()->plan_expires_at->format('M d, Y')) : \Carbon\Carbon::now()->addMonth()->format('M d, Y') }}
                            @else
                            · Monthly billing · Renews on {{ \Carbon\Carbon::parse($subscriptionPlan['billing_period_end'] ?? \Carbon\Carbon::now()->addMonth())->format('M d, Y') }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- Alumni Count -->
                    <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="rounded-full bg-primary-100 p-3 mr-4">
                                <i class="fas fa-user-graduate text-primary text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Alumni</p>
                                <p class="text-xl font-semibold">{{ $totalAlumni ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card card-primary">
                        <div class="stat-header">
                            <h3 class="font-semibold text-xl">Alumni Network</h3>
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-body">
                            <div>
                                <div class="stat-number">{{ $totalAlumni }}</div>
                                <div class="stat-label">Registered Alumni</div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-white bg-opacity-20 text-white text-xs font-medium uppercase tracking-wider">Total</span>
                        </div>
                    </div>
                    
                    <div class="stat-card card-secondary">
                        <div class="stat-header">
                            <h3 class="font-semibold text-xl">Job Opportunities</h3>
                            <div class="stat-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                        <div class="stat-body">
                            <div>
                                <div class="stat-number">{{ $totalJobs }}</div>
                                <div class="stat-label">Available Positions</div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-white bg-opacity-20 text-white text-xs font-medium uppercase tracking-wider">Careers</span>
                        </div>
                    </div>
                    
                    <div class="stat-card card-accent">
                        <div class="stat-header">
                            <h3 class="font-semibold text-xl">Upcoming Events</h3>
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="stat-body">
                            <div>
                                <div class="stat-number">{{ $upcomingEvents }}</div>
                                <div class="stat-label">Scheduled Activities</div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-white bg-opacity-20 text-white text-xs font-medium uppercase tracking-wider">Events</span>
                        </div>
                    </div>
                    
                    <div class="stat-card card-neutral">
                        <div class="stat-header">
                            <h3 class="font-semibold text-xl">News Articles</h3>
                            <div class="stat-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="stat-body">
                            <div>
                                <div class="stat-number">{{ $totalNews }}</div>
                                <div class="stat-label">Published Updates</div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-white bg-opacity-20 text-white text-xs font-medium uppercase tracking-wider">News</span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    <!-- Recent Activity with Enhanced Design -->
                    <div>
                        <h2 class="section-title">Recent Activities</h2>
                        <div class="activity-card p-6">
                            @if(count($recentActivities) > 0)
                                <div class="space-y-6">
                                    @foreach($recentActivities as $activity)
                                        <div class="flex items-start">
                                            <div class="activity-icon mr-4
                                                @if($activity->type == 'job')
                                                    bg-blue-100 text-blue-600
                                                @elseif($activity->type == 'event')
                                                    bg-green-100 text-green-600
                                                @elseif($activity->type == 'news')
                                                    bg-yellow-100 text-yellow-600
                                                @else
                                                    bg-purple-100 text-purple-600
                                                @endif
                                            ">
                                                @if($activity->type == 'job')
                                                    <i class="fas fa-briefcase"></i>
                                                @elseif($activity->type == 'event')
                                                    <i class="fas fa-calendar-alt"></i>
                                                @elseif($activity->type == 'news')
                                                    <i class="fas fa-newspaper"></i>
                                                @else
                                                    <i class="fas fa-bell"></i>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-gray-500 text-sm mb-1">{{ $activity->created_at->diffForHumans() }}</div>
                                                <div class="font-medium text-gray-800">{{ $activity->description }}</div>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="border-b border-gray-100 my-4"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-stream empty-icon"></i>
                                    <p class="font-medium">No recent activity</p>
                                    <p class="text-sm mt-2">Check back later for updates</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Upcoming Events Section -->
                    <div>
                        <h2 class="section-title">Upcoming Events</h2>
                        <div class="event-card p-6">
                            @if(count($nextEvents) > 0)
                                <div class="space-y-6">
                                    @foreach($nextEvents as $event)
                                        <div class="flex items-start">
                                            <div class="event-date">
                                                <div class="date-month">{{ $event->start_date->format('M') }}</div>
                                                <div class="date-day">{{ $event->start_date->format('d') }}</div>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">{{ $event->title }}</h4>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <i class="fas fa-clock mr-2 text-gray-400"></i>
                                                    {{ $event->start_date->format('g:i A') }}
                                                </p>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                                    {{ $event->location }}
                                                </p>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="border-b border-gray-100 my-4"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-calendar-alt empty-icon"></i>
                                    <p class="font-medium">No upcoming events</p>
                                    <p class="text-sm mt-2">Check back later for scheduled events</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Charts and Stats with Enhanced Design -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                    <div>
                        <h2 class="section-title">Alumni Status</h2>
                        <div class="chart-container" style="position: relative; height: 270px; max-height: 270px; overflow: hidden;">
                            <canvas id="alumniStatusChart"></canvas>
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="section-title">Alumni by Year</h2>
                        <div class="chart-container" style="position: relative; height: 270px; max-height: 270px; overflow: hidden;">
                            <canvas id="alumniYearChart"></canvas>
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
                            
                            <a href="{{ route('alumni.import') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center">
                                <div class="rounded-full bg-blue-100 p-3 mr-3">
                                    <i class="fas fa-file-import text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Import Data</p>
                                    <p class="text-sm text-gray-500">Bulk import alumni records</p>
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
</x-app-layout> 