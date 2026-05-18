<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .logout-form { display: inline; }
        button { padding: 10px 15px; background: #ff6b6b; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #ff5252; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ini Dashboard Admin</h1>
        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
    <p>User: {{ auth()->user()->name }}</p>
    <p>Role: {{ auth()->user()->role->nama_role }}</p>
</body>
</html>
