<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Truck List</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 30px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            height: 80px;
        }

        .header h1 {
            font-size: 24px;
            margin: 10px 0;
            text-transform: uppercase;
        }

        .header p {
            font-size: 14px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #e6e6e6;
            text-transform: uppercase;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/header-logo.png') }}" alt="Header Logo">
        <h1>Truck List</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Truck Plate Number</th>
                <th>Barangay</th>
                <!-- Add more columns as necessary -->
            </tr>
        </thead>
        <tbody>
            @foreach($trucks as $truck)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $truck->plate_number }}</td>
                    <td>{{ $truck->barangay }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>