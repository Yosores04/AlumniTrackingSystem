<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Tenant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-8 tracking-tight">Welcome to Your Alumni Dashboard</h1>
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="app-card bg-primary-5 border-l-4 border-primary shadow-primary rounded-lg overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-primary">Total Alumni</h3>
                            <i class="fas fa-users text-2xl text-primary"></i>
                        </div>
                    </div>
                    <div class="p-5 bg-primary-10">
                        <div class="flex items-center justify-between">
                            <p class="text-4xl font-bold">{{ $totalAlumni }}</p>
                            <span class="px-2 py-1 rounded-md bg-primary text-white text-xs font-medium">Alumni Network</span>
                        </div>
                    </div>
                </div>
                
                <div class="app-card bg-accent-5 border-l-4 border-accent shadow-accent rounded-lg overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-accent">Job Opportunities</h3>
                            <i class="fas fa-briefcase text-2xl text-accent"></i>
                        </div>
                    </div>
                    <div class="p-5 bg-accent-10">
                        <div class="flex items-center justify-between">
                            <p class="text-4xl font-bold">{{ $totalJobs }}</p>
                            <span class="px-2 py-1 rounded-md bg-accent text-white text-xs font-medium">Career Center</span>
                        </div>
                    </div>
                </div>
                
                <div class="app-card bg-secondary-5 border-l-4 border-secondary shadow-secondary rounded-lg overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-secondary">Upcoming Events</h3>
                            <i class="fas fa-calendar-alt text-2xl text-secondary"></i>
                        </div>
                    </div>
                    <div class="p-5 bg-secondary-10">
                        <div class="flex items-center justify-between">
                            <p class="text-4xl font-bold">{{ $upcomingEvents }}</p>
                            <span class="px-2 py-1 rounded-md bg-secondary text-white text-xs font-medium">Events Calendar</span>
                        </div>
                    </div>
                </div>
                
                <div class="app-card bg-primary-5 border-l-4 border-primary shadow-primary rounded-lg overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-primary">News Articles</h3>
                            <i class="fas fa-newspaper text-2xl text-primary"></i>
                        </div>
                    </div>
                    <div class="p-5 bg-primary-10">
                        <div class="flex items-center justify-between">
                            <p class="text-4xl font-bold">{{ $totalNews }}</p>
                            <span class="px-2 py-1 rounded-md bg-primary text-white text-xs font-medium">Latest Updates</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Activity -->
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-semibold mb-5 tracking-tight">Recent Activity</h2>
                    <div class="app-card shadow-primary rounded-xl">
                        <div class="app-card-body">
                            @if(count($recentActivities) > 0)
                                <div class="space-y-5">
                                    @foreach($recentActivities as $activity)
                                        <div class="flex">
                                            <div class="bg-accent-10 text-accent w-10 h-10 flex items-center justify-center rounded-full mr-4 shrink-0">
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
                                            <div>
                                                <div class="text-muted mb-1 text-sm">{{ $activity->created_at->diffForHumans() }}</div>
                                                <div class="font-medium">{{ $activity->description }}</div>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-4 border-primary-10">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <i class="fas fa-stream text-muted text-4xl mb-4"></i>
                                    <p class="text-muted font-medium">No recent activity</p>
                                    <p class="text-muted text-sm mt-2">Check back later for updates</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h2 class="text-2xl font-semibold mb-5 tracking-tight">Quick Links</h2>
                    <div class="app-card shadow-secondary rounded-xl">
                        <div class="app-card-body">
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ route('tenant.profile.edit') }}" class="flex items-center p-3 rounded-lg hover:bg-primary-10 transition-colors">
                                        <span class="bg-primary-10 text-primary p-2 rounded-lg mr-3 flex items-center justify-center w-10 h-10 shrink-0">
                                            <i class="fas fa-user-edit"></i>
                                        </span>
                                        <span class="font-medium">Update Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.jobs.index') }}" class="flex items-center p-3 rounded-lg hover:bg-accent-10 transition-colors">
                                        <span class="bg-accent-10 text-accent p-2 rounded-lg mr-3 flex items-center justify-center w-10 h-10 shrink-0">
                                            <i class="fas fa-briefcase"></i>
                                        </span>
                                        <span class="font-medium">Browse Job Opportunities</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.events.index') }}" class="flex items-center p-3 rounded-lg hover:bg-secondary-10 transition-colors">
                                        <span class="bg-secondary-10 text-secondary p-2 rounded-lg mr-3 flex items-center justify-center w-10 h-10 shrink-0">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <span class="font-medium">Upcoming Events</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.news.index') }}" class="flex items-center p-3 rounded-lg hover:bg-primary-10 transition-colors">
                                        <span class="bg-primary-10 text-primary p-2 rounded-lg mr-3 flex items-center justify-center w-10 h-10 shrink-0">
                                            <i class="fas fa-newspaper"></i>
                                        </span>
                                        <span class="font-medium">Latest News</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.directory.index') }}" class="flex items-center p-3 rounded-lg hover:bg-accent-10 transition-colors">
                                        <span class="bg-accent-10 text-accent p-2 rounded-lg mr-3 flex items-center justify-center w-10 h-10 shrink-0">
                                            <i class="fas fa-address-book"></i>
                                        </span>
                                        <span class="font-medium">Alumni Directory</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <h2 class="text-2xl font-semibold mb-5 mt-8 tracking-tight">Alumni Status</h2>
                    <div class="app-card shadow-accent rounded-xl overflow-hidden">
                        <div class="app-card-body" style="height: 300px;">
                            <canvas id="alumniStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Charts and Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-10">
                <div>
                    <h2 class="text-2xl font-semibold mb-5 tracking-tight">Alumni by Graduation Year</h2>
                    <div class="app-card shadow-primary rounded-xl overflow-hidden">
                        <div class="app-card-body" style="height: 300px;">
                            <canvas id="alumniYearChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-2xl font-semibold mb-5 tracking-tight">Upcoming Events</h2>
                    <div class="app-card shadow-secondary rounded-xl">
                        <div class="app-card-body">
                            @if(count($nextEvents) > 0)
                                <div class="space-y-5">
                                    @foreach($nextEvents as $event)
                                        <div class="flex">
                                            <div class="mr-4 text-center">
                                                <div class="w-14 bg-secondary-10 text-secondary font-bold rounded-t-lg py-1 text-xs uppercase">
                                                    {{ $event->start_date->format('M') }}
                                                </div>
                                                <div class="w-14 bg-white border border-secondary-20 rounded-b-lg py-2 font-bold text-xl">
                                                    {{ $event->start_date->format('d') }}
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold">{{ $event->title }}</h4>
                                                <p class="text-muted text-sm mt-1">{{ $event->start_date->format('g:i A') }} - {{ $event->location }}</p>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-4 border-secondary-10">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <i class="fas fa-calendar-alt text-muted text-4xl mb-4"></i>
                                    <p class="text-muted font-medium">No upcoming events</p>
                                    <p class="text-muted text-sm mt-2">Check back later for updates</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get primary, secondary and accent colors from CSS variables
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim();
            const secondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--secondary-color').trim();
            const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--accent-color').trim();
            
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
                            primaryColor, 
                            accentColor, 
                            secondaryColor, 
                            '#EF4444', // Red
                            '#9CA3AF', // Gray
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
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
                        backgroundColor: primaryColor,
                        borderColor: 'transparent',
                        borderWidth: 0
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
        });
    </script>
</x-app-layout> 