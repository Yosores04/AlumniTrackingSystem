<style>
    /* Dashboard styles */
    .stat-card {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .stat-value {
        color: var(--brand-primary);
        font-size: 2rem;
        font-weight: 700;
    }
    
    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .stat-icon {
        color: var(--brand-primary-light);
        font-size: 1.75rem;
    }
    
    .chart-container {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .chart-title {
        color: var(--text-primary);
        font-weight: 600;
    }
    
    .upcoming-event {
        border-left: 3px solid var(--brand-primary);
        background-color: var(--content-bg);
        transition: transform 0.2s;
    }
    
    .upcoming-event:hover {
        transform: translateX(3px);
    }
    
    .activity-item {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 0.75rem 0;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        background-color: var(--brand-primary-light);
        color: var(--brand-primary);
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .activity-time {
        color: var(--text-secondary);
        font-size: 0.75rem;
    }
    
    .activity-content {
        color: var(--text-primary);
    }
</style>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Title and Welcome Message -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Alumni -->
            <div class="stat-card p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="stat-value">{{ $totalAlumni }}</p>
                        <p class="stat-label">Total Alumni</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            
            <!-- Verified Alumni -->
            <div class="stat-card p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="stat-value">{{ $verifiedAlumni }}</p>
                        <p class="stat-label">Verified Alumni</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Events -->
            <div class="stat-card p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="stat-value">{{ $upcomingEvents }}</p>
                        <p class="stat-label">Upcoming Events</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            
            <!-- Job Opportunities -->
            <div class="stat-card p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="stat-value">{{ $jobOpportunities }}</p>
                        <p class="stat-label">Job Opportunities</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts and Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Alumni Growth Chart -->
            <div class="chart-container p-6 lg:col-span-2">
                <h2 class="chart-title text-lg mb-4">Alumni Growth</h2>
                <div class="h-64">
                    <canvas id="alumniGrowthChart"></canvas>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="chart-container p-6">
                <h2 class="chart-title text-lg mb-4">Recent Activity</h2>
                <div class="space-y-1">
                    @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="flex items-start">
                                <div class="activity-icon mr-3">
                                    <i class="{{ $activity->icon }}"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="activity-content">{{ $activity->description }}</p>
                                    <p class="activity-time">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No recent activities</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Demographics and Events -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <!-- Demographics Chart -->
            <div class="chart-container p-6">
                <h2 class="chart-title text-lg mb-4">Alumni Demographics</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="h-56">
                        <canvas id="graduationYearChart"></canvas>
                    </div>
                    <div class="h-56">
                        <canvas id="industryChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Events -->
            <div class="chart-container p-6">
                <h2 class="chart-title text-lg mb-4">Upcoming Events</h2>
                <div class="space-y-4">
                    @forelse($nextEvents as $event)
                        <div class="upcoming-event pl-4 py-3">
                            <h3 class="font-semibold">{{ $event->name }}</h3>
                            <p class="text-sm text-gray-600 flex items-center mt-1">
                                <i class="far fa-calendar-alt mr-2"></i>
                                {{ $event->start_date->format('M d, Y') }}
                                @if($event->end_date)
                                 - {{ $event->end_date->format('M d, Y') }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 flex items-center mt-1">
                                <i class="far fa-clock mr-2"></i>
                                {{ $event->start_time->format('g:i A') }}
                                @if($event->end_time)
                                 - {{ $event->end_time->format('g:i A') }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 flex items-center mt-1">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $event->location }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500">No upcoming events</p>
                    @endforelse
                    
                    @if(count($nextEvents) > 0)
                        <div class="mt-4">
                            <a href="{{ route('tenant.events.index') }}" class="text-brand-primary hover:text-primary-hover flex items-center">
                                View all events
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Alumni Growth Chart
        const growthCtx = document.getElementById('alumniGrowthChart').getContext('2d');
        const growthChart = new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($growthChart['labels']) !!},
                datasets: [{
                    label: 'New Alumni',
                    data: {!! json_encode($growthChart['data']) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(79, 70, 229, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Graduation Year Chart
        const gradYearCtx = document.getElementById('graduationYearChart').getContext('2d');
        const gradYearChart = new Chart(gradYearCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($gradYearChart['labels']) !!},
                datasets: [{
                    data: {!! json_encode($gradYearChart['data']) !!},
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.8)',
                        'rgba(147, 51, 234, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(248, 113, 113, 0.8)',
                        'rgba(251, 191, 36, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12
                        }
                    },
                    title: {
                        display: true,
                        text: 'Graduation Years',
                        position: 'top'
                    }
                }
            }
        });
        
        // Industry Chart
        const industryCtx = document.getElementById('industryChart').getContext('2d');
        const industryChart = new Chart(industryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($industryChart['labels']) !!},
                datasets: [{
                    data: {!! json_encode($industryChart['data']) !!},
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(249, 115, 22, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12
                        }
                    },
                    title: {
                        display: true,
                        text: 'Industries',
                        position: 'top'
                    }
                }
            }
        });
    });
</script> 