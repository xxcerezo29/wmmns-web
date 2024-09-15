<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Truck List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100%;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:nth-child(odd) {
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/header-logo.png') }}" alt="Header Logo">
        <h1>Complaints List</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Ref. ID</th>
                <th>Report Type</th>
                <th>Description</th>
                <th>Schedule</th>
                <th>Barangay</th>
                <th>Status</th>
                <!-- Add more columns as necessary -->
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $complaint->reference_number }}</td>
                    <td>{{ $complaint->report_type }}</td>
                    <td>{{ $complaint->description }}</td>
                    <td>{{ 
                    $complaint->schedule_id? 
                        $complaint->schedule->day.'-'.$complaint->schedule->route->name.'-'.$complaint->schedule->time.'-(Plate Number: '.$complaint->schedule->truck->plate_number.')' : '-'
                     }}
                    </td>
                    <td>{{ $complaint->barangay }}</td>
                    <td>{{ $complaint->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>