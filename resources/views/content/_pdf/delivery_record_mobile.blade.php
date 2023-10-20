<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Record PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .header-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <div class="header-logo">
        <img src="{{ asset('path_to_your_logo.png') }}" alt="Logo" width="80">
    </div>

    <h2>DELIVERY RECORD</h2>
    <p>DR-DTX010101</p>
    <p>16 Agustus 2023</p>

    <h3>Information</h3>
    <p>Courier Name: Handani</p>
    <p>Courier ID: 001</p>
    <p>Delivery Date: 16 Agustus 2023</p>
    <p>Deposited Date: 16 Agustus 2023</p>

    <h3>Delivery Order</h3>
    <p>Total Waybill: 100</p>
    <p>Total COD Amount: 2,130,000</p>

    <h3>Waybill Details</h3>
    <table class="table">
        <tr>
            <th>WAYBILL NO</th>
            <th>STATUS DELIVERY</th>
            <th>COD</th>
        </tr>
        
        <tr>
            <td>DTX0101012231</td>
            <td>Delivered</td>
            <td>300,000</td>
        </tr>
    </table>

</body>

</html>
