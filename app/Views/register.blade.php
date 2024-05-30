<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
<h1>Register</h1>

@if (!empty($errors))
    <div>
        @foreach ($errors as $field => $messages)
            <ul>
                @foreach ($messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endforeach
    </div>
@endif

@if (isset($error))
    <div>{{ $error }}</div>
@endif

<form action="/register" method="POST">
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>
    </div>
    <button type="submit">Register</button>
</form>
</body>
</html>
