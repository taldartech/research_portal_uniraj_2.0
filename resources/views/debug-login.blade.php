<!DOCTYPE html>
<html>
<head>
    <title>Login Debug Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .user-info { background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .error { color: red; }
        .success { color: green; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h1>Login Debug Tool</h1>

    <div class="user-info">
        <h3>All Users in Database:</h3>
        @php
            $users = \App\Models\User::all(['email', 'user_type', 'password']);
        @endphp
        <table border="1" style="border-collapse: collapse; width: 100%;">
            <tr>
                <th>Email</th>
                <th>User Type</th>
                <th>Password Status</th>
                <th>Can Login as Scholar</th>
                <th>Can Login as Staff</th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->user_type }}</td>
                    <td>
                        @if(empty($user->password))
                            <span class="error">No Password</span>
                        @elseif(strlen($user->password) < 50)
                            <span class="error">Invalid Hash</span>
                        @else
                            <span class="success">Valid Hash</span>
                        @endif
                    </td>
                    <td>
                        @if($user->user_type === 'scholar')
                            <span class="success">Yes</span>
                        @else
                            <span class="warning">No (Wrong Type)</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $allowedStaffTypes = ['staff', 'supervisor', 'hod', 'dean', 'da', 'so', 'ar', 'dr', 'hvc'];
                        @endphp
                        @if(in_array($user->user_type, $allowedStaffTypes))
                            <span class="success">Yes</span>
                        @else
                            <span class="warning">No (Not in Allowed Types)</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="user-info">
        <h3>Login Test Form:</h3>
        <form method="POST" action="{{ route('staff.login') }}" style="margin-bottom: 20px;">
            @csrf
            <h4>Staff Login Test:</h4>
            <label>Email: <input type="email" name="email" value="staff@example.com" required></label><br><br>
            <label>Password: <input type="password" name="password" value="password" required></label><br><br>
            <button type="submit">Test Staff Login</button>
        </form>

        <form method="POST" action="{{ route('scholar.login') }}">
            @csrf
            <h4>Scholar Login Test:</h4>
            <label>Email: <input type="email" name="email" value="scholar@example.com" required></label><br><br>
            <label>Password: <input type="password" name="password" value="password" required></label><br><br>
            <button type="submit">Test Scholar Login</button>
        </form>
    </div>

    <div class="user-info">
        <h3>Common Login Issues:</h3>
        <ul>
            <li><strong>Wrong Login Page:</strong> Scholars should use <a href="{{ route('scholar.login') }}">Scholar Login</a></li>
            <li><strong>Staff Login:</strong> All other users should use <a href="{{ route('staff.login') }}">Staff Login</a></li>
            <li><strong>User Type Mismatch:</strong> Check if user type matches the login controller</li>
            <li><strong>Password Issues:</strong> Ensure password is properly hashed</li>
            <li><strong>Email Case Sensitivity:</strong> Try different email cases</li>
        </ul>
    </div>

    <div class="user-info">
        <h3>Quick Fixes:</h3>
        <p><strong>Reset a user's password:</strong></p>
        <code>php artisan tinker --execute="\$user = \App\Models\User::where('email', 'user@example.com')->first(); \$user->password = bcrypt('password'); \$user->save();"</code>

        <p><strong>Check specific user:</strong></p>
        <code>php artisan tinker --execute="\$user = \App\Models\User::where('email', 'user@example.com')->first(); echo 'User: ' . \$user->email . PHP_EOL; echo 'Type: ' . \$user->user_type . PHP_EOL; echo 'Password: ' . (empty(\$user->password) ? 'Empty' : 'Set') . PHP_EOL;"</code>
    </div>
</body>
</html>

