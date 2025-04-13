<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Tenant</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('Create New Tenant') }}
                    </h2>
                    
                    <!-- Authentication section -->
                    @guest
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="mb-2">Want to use your Google account information?</p>
                        <a href="{{ route('auth.google') }}" class="flex items-center justify-center bg-white hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded border border-gray-300 shadow-sm">
                            <img src="{{ asset('images/google.svg') }}" alt="Google" class="w-5 h-5 mr-2" onerror="this.src='https://www.google.com/favicon.ico'">
                            Sign in with Google
                        </a>
                    </div>
                    @endguest
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                        
                        @if(session('tenant_info'))
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
                                <h3 class="font-bold text-lg mb-2">Tenant Information (Save these details)</h3>
                                <p><strong>Tenant ID:</strong> {{ session('tenant_info')['id'] }}</p>
                                <p><strong>Domain:</strong> {{ session('tenant_info')['domain'] }}</p>
                                <p><strong>Admin Email:</strong> {{ session('tenant_info')['email'] }}</p>
                                <p><strong>Admin Password:</strong> {{ session('tenant_info')['password'] }}</p>
                                <p class="mt-2 text-sm">This password will not be shown again. Please make sure to save it.</p>
                            </div>
                        @endif
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tenants.store') }}">
                        @csrf

                        <!-- Admin Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Admin Name</label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Admin Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Admin Email</label>
                            <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Domain Prefix -->
                        <div class="mb-4">
                            <label for="domain_prefix" class="block text-gray-700 text-sm font-bold mb-2">Subdomain Prefix</label>
                            <div class="flex items-center">
                                <input type="text" name="domain_prefix" id="domain_prefix" 
                                    class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('domain_prefix') border-red-500 @enderror" 
                                    value="{{ old('domain_prefix') }}" 
                                    required 
                                    placeholder="test1">
                                <span class="bg-gray-200 py-2 px-3 rounded-r">.localhost</span>
                            </div>
                            <p class="text-gray-600 text-xs mt-1">
                                Only enter the subdomain prefix (e.g., "test1" will create "test1.localhost").<br>
                                Only letters, numbers, and hyphens are allowed.
                            </p>
                            @error('domain_prefix')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create Tenant
                            </button>
                        </div>
                    </form>

                    <!-- Example section -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-lg text-gray-700 mb-2">Example:</h3>
                        <p>If you enter <span class="font-mono bg-gray-200 px-1 rounded">school1</span> in the field above, your tenant will be accessible at:</p>
                        <p class="font-mono text-blue-600 mt-1">http://school1.localhost:8000</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
