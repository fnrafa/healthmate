<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            text-align: center;
        }
        .error-details {
            margin-top: 20px;
            text-align: left;
        }
        pre {
            background: #eee;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Something went wrong</h1>
    <p>We encountered an error while processing your request. Please try again later.</p>
    @if (isset($message))
        <div class="error-details">
            <h3>Error Details:</h3>
            <pre>{{ $message }}</pre>
        </div>
    @endif
</div>
</body>
</html>
