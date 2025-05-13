@php
    use Illuminate\Support\Facades\Storage;
@endphp

<nav x-data="{ open: false }" class="primary-nav bg-secondary-80 border-b border-secondary-30 shadow-md fixed top-0 w-full z-0">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                   <div class="shrink-0 flex items-center">
                    <a href="/" class="flex items-center">
                        @php
                            $settings = \App\Models\TenantSettings::getSettings();
                            $logoUrl = null;
                            
                            if ($settings->logo_url) {
                                $logoUrl = $settings->logo_url;
                            } elseif ($settings->logo_path) {
                                $logoUrl = Storage::url($settings->logo_path);
                            }
                        @endphp

                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" class="h-8 w-auto mr-2" alt="{{ $settings->site_name ?? 'Alumni Logo' }}">
                        @else
                            <img src="/img/1.svg" class="h-8 w-auto mr-2" alt="Alumni Logo">
                        @endif
                        <span class="font-bold text-lg text-white tracking-tight">Tenant Admin</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-link flex items-center">
                        <i class="fas fa-tachometer-alt mr-1.5"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link href="/" class="nav-link flex items-center">
                        <i class="fas fa-home mr-1.5"></i>
                        {{ __('Home') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('tenant.settings.edit')" :active="request()->routeIs('tenant.settings.edit')" class="nav-link flex items-center">
                        <i class="fas fa-cog mr-1.5"></i>
                        {{ __('Site Settings') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('tenant.instructors.index')" :active="request()->routeIs('tenant.instructors.*')" class="nav-link flex items-center">
                        <i class="fas fa-chalkboard-teacher mr-1.5"></i>
                        {{ __('Instructors') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('alumni.index')" :active="request()->routeIs('alumni.*')" class="nav-link flex items-center">
                        <i class="fas fa-user-graduate mr-1.5"></i>
                        {{ __('Alumni') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="url('/support')" :active="request()->is('support*')" class="nav-link flex items-center">
                        <i class="fas fa-headset mr-1.5"></i>
                        {{ __('Support') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('system.versions')" :active="request()->routeIs('system.*')" class="nav-link flex items-center">
                        <i class="fas fa-code-branch mr-1.5"></i>
                        {{ __('System') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="nav-link inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('tenant.profile.edit')" class="flex items-center">
                            <i class="fas fa-user-circle mr-2 text-primary"></i>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('tenant.settings.edit')" class="flex items-center">
                            <i class="fas fa-cog mr-2 text-primary"></i>
                            {{ __('Site Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="flex items-center">
                                <i class="fas fa-sign-out-alt mr-2 text-primary"></i>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-primary-30 focus:outline-none focus:bg-primary-30 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center">
                <i class="fas fa-tachometer-alt mr-2 text-primary"></i>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link href="/" class="flex items-center">
                <i class="fas fa-home mr-2 text-primary"></i>
                {{ __('Home') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('tenant.settings.edit')" :active="request()->routeIs('tenant.settings.edit')" class="flex items-center">
                <i class="fas fa-cog mr-2 text-primary"></i>
                {{ __('Site Settings') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('tenant.instructors.index')" :active="request()->routeIs('tenant.instructors.*')" class="flex items-center">
                <i class="fas fa-chalkboard-teacher mr-2 text-primary"></i>
                {{ __('Instructors') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('alumni.index')" :active="request()->routeIs('alumni.*')" class="flex items-center">
                <i class="fas fa-user-graduate mr-2 text-primary"></i>
                {{ __('Alumni') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="url('/support')" :active="request()->is('support*')" class="flex items-center">
                <i class="fas fa-headset mr-2 text-primary"></i>
                {{ __('Support') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->isAdmin())
            <x-responsive-nav-link :href="route('system.versions')" :active="request()->routeIs('system.*')" class="flex items-center">
                <i class="fas fa-code-branch mr-2 text-primary"></i>
                {{ __('System') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-secondary-30">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-secondary-20">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('tenant.profile.edit')" class="flex items-center">
                    <i class="fas fa-user-circle mr-2 text-primary"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="flex items-center">
                        <i class="fas fa-sign-out-alt mr-2 text-primary"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Add padding to main content to account for fixed navbar -->
<div class="pt-16"></div>
