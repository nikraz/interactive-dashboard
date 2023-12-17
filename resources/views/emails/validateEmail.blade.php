<!DOCTYPE html>
<html>
<head>
    <title>Email Validation</title>
</head>
<body>
<h1>Email Validation</h1>
<p>Hello {{ $user->name }},</p>
<p>Please click the link below to validate your email address:</p>
<a href="{{ url('/email/validate?token=' . $token) }}">Validate Email</a>
</body>
</html>
