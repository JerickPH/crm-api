<!DOCTYPE html>
<html>
<head>
    <title>New Ticket Created</title>
</head>
<body>
    <h1>New Ticket Created</h1>
    <p>A new ticket has been created:</p>
    <ul>
        <li>Subject: {{ $ticket->subject }}</li>
        <li>Description: {{ $ticket->description }}</li>
        <li>Priority: {{ $ticket->priority }}</li>
        <li>Assigned Staff: {{ $ticket->assign_staff }}</li>
        <li>Company ID: {{ $ticket->company_id }}</li>
        <li>To Email: {{ $ticket->to_email }}</li>
        <li>CC: {{ $ticket->cc }}</li>
    </ul>
</body>
</html>
