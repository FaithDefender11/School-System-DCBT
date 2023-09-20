<?php 
    
    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

</head>

<body>
    <div class="row col-md-12">
            
        <div class="col-md-10 offset-md-1">
            <div class="table-responsive" style="margin-top:2%;"> 
                <table id="empTable" class="a"  style="font-size:15px" cellspacing="0"> 
                    <thead>
                        <tr>
                            <th>Program Name</th>
                            <th>Department Id</th>
                            <th>Dean</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</body>
</html>
<script>
    $(document).ready(function(){
        var table = $('#empTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url':'indexv2List.php'
            },
            'columns': [
                { data: 'program_name' },
                { data: 'department_id' },
                { data: 'dean' },
                { data: 'actions' }
            ]
        });
    });
</script>