<!-- resources/views/emails/appointment_approved.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointment Has Been Approved</title>
</head>
<body>
    <h1>Your Appointment Has Been Approved!</h1>
    <p>Dear {{ $appointment->user->name }},</p>
    <p>We are pleased to inform you that your appointment has been approved.</p>
    <p>Appointment Details:</p>
    <ul>
        <li><strong>Assessment Type:</strong> {{ $appointment->assessment->assessment_type }}</li>
        <li><strong>Scheduled Date:</strong> {{ \Carbon\Carbon::parse($appointment->schedule_at)->format('F j, Y, g:i A') }}</li>
        <li><strong>Location:</strong> Your preferred location</li>
    </ul>
    <p>Thank you for your request. If you have any questions, feel free to contact us.</p>
    <p>Best regards,</p>
    <p>Your Support Team</p>
</body>
</html>
