<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Routes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .system-info {
            background-color: #e9f7fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Debug Routes</h1>
        
        <div class="system-info">
            <h2>System Information</h2>
            <p><strong>Base URL:</strong> {{ url('/') }}</p>
            <p><strong>Current URL:</strong> {{ request()->url() }}</p>
            <p><strong>Host:</strong> {{ request()->getHost() }}</p>
            <p><strong>Port:</strong> {{ request()->getPort() }}</p>
        </div>
        
        <h2>Routes List</h2>
        <p>This page shows all registered routes in the application.</p>
        
        <table>
            <thead>
                <tr>
                    <th>Method</th>
                    <th>URI</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($routes as $route)
                <tr>
                    <td>{{ implode('|', $route->methods()) }}</td>
                    <td>{{ $route->uri() }}</td>
                    <td>{{ $route->getName() }}</td>
                    <td>{{ $route->getActionName() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 