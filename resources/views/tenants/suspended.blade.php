<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account {{ ucfirst($status) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
        <div class="text-center">
            @if($status === 'suspended')
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                <h2 class="text-2xl font-bold text-red-600 mb-2">Account Suspended</h2>
                <p class="text-gray-600 mb-4">This tenant account has been suspended. Please contact the administrator for assistance.</p>
            @elseif($status === 'inactive')
                <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-yellow-600 mb-2">Account Inactive</h2>
                <p class="text-gray-600 mb-4">This tenant account is currently inactive. Please contact the administrator to activate your account.</p>
            @else
                <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">Account Unavailable</h2>
                <p class="text-gray-600 mb-4">This tenant account is currently unavailable. Please try again later or contact support.</p>
            @endif
            
            @if($reason)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg text-left">
                    <h3 class="font-semibold text-gray-700">Reason:</h3>
                    <p class="text-gray-600">{{ $reason }}</p>
                </div>
            @endif
        </div>
        
        <div class="mt-6 border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-2">Account Information</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="text-gray-500">Tenant ID:</div>
                    <div class="font-medium">{{ $tenant->id }}</div>
                    
                    <div class="text-gray-500">Domain:</div>
                    <div class="font-medium">{{ request()->getHost() }}</div>
                    
                    <div class="text-gray-500">Status:</div>
                    <div class="font-medium">{{ ucfirst($status) }}</div>
                    
                    <div class="text-gray-500">Since:</div>
                    <div class="font-medium">{{ \Carbon\Carbon::parse($suspended_at)->format('M d, Y H:i') }}</div>
                    
                    <div class="text-gray-500">Plan:</div>
                    <div class="font-medium">{{ ucfirst($plan) }}</div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 mb-2">Your account is in read-only mode. You can still access your data but cannot make changes.</p>
            <a href="mailto:support@example.com" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition duration-200">
                Contact Support
            </a>
        </div>
    </div>
</body>
</html>
