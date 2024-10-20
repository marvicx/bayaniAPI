<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <style>
        /* Add styles specific to the PDF */
        body {
            font-family: Arial, sans-serif; 
            margin: 0; /* Remove default margin */
            padding: 20px; /* Add some padding */
            font-size: 9px;
        }
        header {
            position: relative;
            text-align: center; /* Center the text */
            padding: 20px 0;
        }
        .logo-left, .logo-right {
            position: absolute;
            top: 10px; /* Adjust as necessary */
            width: 100px; /* Set desired width for images */
            height: auto; /* Maintain aspect ratio */
        }
        .logo-left {
            left: 20px; /* Adjust as necessary */
        }
        .logo-right {
            right: 20px; /* Adjust as necessary */
        }
        .title {
            font-size: 20px;
            font-weight: bold;
        }
        .content {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed; /* Use fixed table layout */
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            overflow: hidden; /* Hide overflow */
            word-wrap: break-word; /* Allow word wrapping */
            white-space: normal; /* Allow text to wrap */
        }
        th {
            background-color: #f2f2f2;
        }
        /* Set fixed widths for columns */
        th:nth-child(1), td:nth-child(1) { width: 10%; } /* Company Name  */
        th:nth-child(2), td:nth-child(2) { width: 10%; } /* Company Type  */
        th:nth-child(3), td:nth-child(3) { width: 15%; } /* Industry */
        th:nth-child(4), td:nth-child(4) { width: 20%; } /* Description */
        th:nth-child(5), td:nth-child(5) { width: 15%; } /* Mission */
        th:nth-child(6), td:nth-child(6) { width: 15%; } /* Vision */
        th:nth-child(7), td:nth-child(7) { width: 15%; } /* Address */
        th:nth-child(8), td:nth-child(8) { width: 15%; } /* Company Website */ 
    </style>
</head>
<body>
 
    <!-- <div class="content">
        <p>Some dynamic data:</p>
        <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
    </div> -->

    
    <header>
        <img src="storage/logo/owwa-logo.jpg" alt="owwa" class="logo-left">
        <h1>Department of Overseas Workers Welfare Administration</h1>
        <img src="storage/logo/owwa-cares-logo.jpg" alt="owwa" class="logo-right">
    </header>

    <table>
        <thead>
            <tr>
                <th>Company Name</th>
                <th>Company Type</th>
                <th>Industry</th>
                <th>Description	</th>
                <th>Mission</th>
                <th>Vision</th>
                <th>Address</th>
                <th>Company Website</th> 
            </tr>
        </thead>
        <tbody>
        @foreach($data as $person)
            <tr> 
                <td>{{ $person['companyName'] }}</td>
                <td>{{ $person['companyType'] }}</td>
                <td>{{ $person['industry'] }}</td>
                <td>{{ $person['description'] }}</td>
                <td>{{ $person['mission'] }}</td>
                <td>{{ $person['vision'] }}</td>
                <td>{{ $person['address'] }}</td>
                <td>{{ $person['same_as'] }}</td> 
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
