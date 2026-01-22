<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Hostel Management System</title>
</head>

<body>
    <h1>Welcome, {{ $student->full_name }}!</h1>
    <p>Your student profile has been created successfully.</p>
    <p>You can now log in to the student portal using your email address.</p>
    <p>Student ID: {{ $student->student_id }}</p>
    <p>Department: {{ $student->department }}</p>
    <br>
    <p>Best regards,</p>
    <p>Hostel Management Team</p>
</body>

</html>