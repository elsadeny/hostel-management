<!DOCTYPE html>
<html>

<head>
    <title>Room Allocation Notification</title>
</head>

<body>
    <h1>Hello, {{ $allocation->student->full_name }}!</h1>
    <p>You have been allocated a room in the hostel.</p>

    <h3>Allocation Details:</h3>
    <ul>
        <li><strong>Hostel:</strong> {{ $allocation->hostel->name }}</li>
        <li><strong>Room Number:</strong> {{ $allocation->room->room_number }}</li>
        <li><strong>Floor:</strong> {{ $allocation->room->floor }}</li>
        <li><strong>Allocation Date:</strong> {{ $allocation->allocation_date->format('d M Y') }}</li>
    </ul>

    <p>Please log in to your student portal to view more details and download your receipt.</p>
    <br>
    <p>Best regards,</p>
    <p>Hostel Management Team</p>
</body>

</html>