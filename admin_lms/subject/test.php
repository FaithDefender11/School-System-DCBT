<?php 

    include_once('../../includes/admin_elms_header.php');
    ?>

<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            max-width: 800px;
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Fix table layout */
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            /* white-space: nowrap; */
            /* overflow: hidden; */
            /* text-overflow: ellipsis;  */

        }

        /* Enable horizontal scrollbar if content overflows */
        tbody {
            display: block;
            max-height: 300px; /* Set a maximum height for the tbody */
            overflow: auto;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Header 1</th>
                <th>Header 2</th>
                <th>Header 3</th>
                <!-- Add more headers as needed -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>This is a long non-wrapping text that will cause overflow</td>
                <td>This is a long non-wrapping text that will cause overflow</td>
                <td>This is a long non-wrapping text that will cause overflow</td>
            </tr>
            
        </tbody>
    </table>
</body>
</html>
