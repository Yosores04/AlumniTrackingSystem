<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Tenant Database Structure</h1>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-3">Database Columns</h2>
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Column</th>
                        <th class="px-4 py-2 border">Type</th>
                        <th class="px-4 py-2 border">Nullable</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($columns as $column)
                    <tr>
                        <td class="px-4 py-2 border">{{ $column->Field }}</td>
                        <td class="px-4 py-2 border">{{ $column->Type }}</td>
                        <td class="px-4 py-2 border">{{ $column->Null }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md mt-6">
            <h2 class="text-xl font-semibold mb-3">Tenant Records</h2>
            @if(count($tenants) > 0)
                @foreach($tenants as $tenant)
                <div class="border p-4 mb-4 rounded-lg">
                    <h3 class="font-bold text-lg">Tenant ID: {{ $tenant->id }}</h3>
                    <div class="mt-2">
                        <p><strong>Created:</strong> {{ $tenant->created_at }}</p>
                        <p><strong>Data:</strong> <code class="bg-gray-100 p-1 text-sm">{{ $tenant->data }}</code></p>
                        <p><strong>Status:</strong> {{ $status[$tenant->id] ?? 'Not set' }}</p>
                        <p><strong>Subscription:</strong> <code class="bg-gray-100 p-1 text-sm">{{ $tenant->subscription }}</code></p>
                    </div>
                </div>
                @endforeach
            @else
                <p>No tenants found in the database.</p>
            @endif
        </div>
    </div>
</body>
</html>
