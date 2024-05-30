<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
</head>
<body>
<h1>Dashboard</h1>
@auth
    <p>Welcome, {{ Auth::user()->name }}</p>
@endauth
<form method="POST" action="/logout">
    <button type="submit">Logout</button>
</form>
</body>
</html>
