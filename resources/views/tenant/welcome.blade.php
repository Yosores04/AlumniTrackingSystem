<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ tenant('id') }} - Tenant Site</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-blue-600 text-white shadow-md">
        <div class="container mx-auto py-4 px-6">
            <h1 class="text-2xl font-bold">{{ strtoupper(tenant('id')) }} Domain</h1>
            <p class="text-sm opacity-80">{{ request()->getHost() }}</p>
        </div>
    </header>

    @if(isset($warningMessage))
        <div class="container mx-auto px-6 mt-4">
            @if($readOnly ?? false)
                <x-tenant-status-banner message="{{ $warningMessage }}" type="danger" />
            @else
                <x-tenant-status-banner message="{{ $warningMessage }}" type="info" />
            @endif
        </div>
    @endif

    <main class="flex-grow container mx-auto py-12 px-6">
        <div class="bg-white rounded-lg shadow-md p-8 max-w-3xl mx-auto">
            @if($readOnly ?? false)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <p class="font-bold">Read-Only Mode</p>
                    <p>This tenant is in read-only mode. You can view your data but cannot make changes.</p>
                </div>
            @else
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    <p class="font-bold">Domain Active!</p>
                    <p>This tenant domain is working properly.</p>
                </div>
            @endif
            
            <h2 class="text-xl font-semibold mb-4">Tenant Information</h2>
            <ul class="space-y-2 mb-6">
                <li><strong>Tenant ID:</strong> {{ tenant('id') }}</li>
                <li><strong>Domain:</strong> {{ request()->getHost() }}</li>
                <li><strong>Created:</strong> {{ now()->format('Y-m-d') }}</li>
                <li><strong>Status:</strong> {{ tenant()->status ?? 'active' }}</li>
                <li><strong>Plan:</strong> {{ tenant()->subscription['plan'] ?? 'free' }}</li>
            </ul>
            
            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                <h3 class="text-lg font-medium mb-2">Next Steps</h3>
                <p class="mb-4">This is a basic tenant site for testing. You can:</p>
                <ul class="list-disc pl-6 space-y-1 text-gray-600">
                    <li>Verify the domain is accessible</li>
                    <li>Check that tenant isolation is working</li>
                    <li>Visit <a href="/debug" class="text-blue-600 hover:underline">the debug page</a> for more information</li>
                </ul>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white text-center py-4">
        <p>Alumni Tracking System - Tenant Domain</p>
    </footer>
</body>
</html>
