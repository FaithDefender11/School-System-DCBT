 <?php 

    include_once('../../includes/cashier_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');

    ?>  
      <head>

        <style>
            .show_search{
                position: relative;
                /* margin-top: -38px;
                margin-left: 215px; */
            }
            div.dataTables_length {
                display: none;
            }

            #waiting_payment_table_filter{
              margin-top: 12px;
              width: 100%;
              display: flex;
              flex-direction: row;
              justify-content: start;
            }

            #waiting_payment_table_filter input{
              width: 250px;
            }
        </style>

        <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

      </head>

    <?php




?>