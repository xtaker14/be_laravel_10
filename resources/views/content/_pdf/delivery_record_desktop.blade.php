<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 15px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .info,
        .order,
        .waybill {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table,
        th,
        td {
            border: 1px solid #dddddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .delivered {
            color: green;
            font-weight: bold;
        }

        /* Stylesheet khusus untuk versi desktop */
        @media (min-width: 768px) {

            .info,
            .order {
                display: inline-block;
                width: 48%;
                vertical-align: top;
            }

            .order {
                margin-left: 4%;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('template/assets/img/website/dethix-logo.svg'); }}" alt="Dethix Logo">
        <h2>DELIVERY RECORD</h2>
        <p>DR-DTX010101</p>
        <p>16 Agustus 2023</p>
    </div>

    <div class="info">
        <h2>Information</h2>
        <p>Courier Name: {{ $courier_name; }}</p>
        <p>Courier ID: 001</p>
        <p>Delivery Date: 16 Agustus 2023</p>
        <p>Deposited Date: 16 Agustus 2023</p>
    </div>

    <div class="order">
        <h2>Delivery Order</h2>
        <p>Total Waybill: 100</p>
        <p>Delivered COD: 5</p>
        <p>Total COD Amount: 2,130,000</p>
    </div>

    <div class="waybill">
        <table>
            <thead>
                <tr>
                    <th>WAYBILL NO</th>
                    <th>STATUS DELIVERY</th>
                    <th>COD</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>DTX0101012231</td>
                    <td class="delivered">Delivered</td>
                    <td>300,000</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p>Total COD Deposited: 2,130,000</p>
    <p>Collected by: johndoe</p>
    <p>Collection code: VW22</p>
</body>

</html>
