<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Request a Domain</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Request a Domain') }}
                        </h2>
                        <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">Back to home</a>
                    </div>

                    <p class="mb-6 text-gray-600">
                        Fill out this form to request your own customized domain. Once approved, you'll receive an email with your login credentials.
                    </p>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('domain-requests.store') }}">
                        @csrf

                        <!-- Admin Name -->
                        <div class="mb-4">
                            <label for="admin_name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                            <input type="text" name="admin_name" id="admin_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('admin_name') border-red-500 @enderror" value="{{ old('admin_name') }}" required>
                            @error('admin_name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Admin Email -->
                        <div class="mb-4">
                            <label for="admin_email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                            <input type="email" name="admin_email" id="admin_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('admin_email') border-red-500 @enderror" value="{{ old('admin_email') }}" required>
                            <p class="text-gray-600 text-xs mt-1">
                                This email will be used for communication and login credentials.
                            </p>
                            @error('admin_email')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Domain Prefix -->
                        <div class="mb-4">
                            <label for="domain_prefix" class="block text-gray-700 text-sm font-bold mb-2">Desired Domain Prefix</label>
                            <div class="flex items-center">
                                <input type="text" name="domain_prefix" id="domain_prefix" 
                                    class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('domain_prefix') border-red-500 @enderror" 
                                    value="{{ old('domain_prefix') }}" 
                                    required 
                                    placeholder="myschool">
                                <span class="bg-gray-200 py-2 px-3 rounded-r">.localhost</span>
                            </div>
                            <p class="text-gray-600 text-xs mt-1">
                                Only letters, numbers, and hyphens are allowed. This will be the prefix for your custom domain.
                            </p>
                            @error('domain_prefix')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Submit Request
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-lg text-gray-700 mb-2">What happens next?</h3>
                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                            <li>Your request will be reviewed by our administrators.</li>
                            <li>Once approved, we'll create your custom domain.</li>
                            <li>You'll receive an email with login credentials.</li>
                            <li>Your temporary password will expire in 24 hours, so make sure to log in and change it promptly.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
