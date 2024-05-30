<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
</head>
<body>
<h1>Register</h1>
<form method="POST" action="/register">
    <label>
        <input type="text" name="name" placeholder="Name" required>
    </label>
    <label>
        <input type="email" name="email" placeholder="Email" required>
    </label>
    <label>
        <input type="password" name="password" placeholder="Password" required>
    </label>
    <label>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
    </label>
    <button type="submit">Register</button>
</form>

@isset($error)
    <div>{{ $error }}</div>
@endisset
</body>
</html>
