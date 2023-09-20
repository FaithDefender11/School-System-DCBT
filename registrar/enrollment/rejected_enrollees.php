<?php 

    include_once('../../includes/registrar_header.php');

    
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
        </style>
        <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    </head>


    <div class="content">

        <main>

            <div class="floating">

                <div class="filters">
                    <table>
                        <tr>
                            <th rowspan="2" style="border-right: 2px solid black">
                                Search by
                            </th>
                            <th><button>Enrollment ID</button></th>
                            <th><button>Name</button></th>
                            <th><button>Section</button></th>
                            <th><button>A.Y</button></th>
                        </tr>
                    </table>
                </div>

                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">Enrollee List</h3>
                    </div>
                
                </header>

                <main>
                    <table style="width: 100%" id="rejected_enrollees_table" class="a">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>A.Y</th>  
                                <th>Status</th>  
                                <th>Action</th>  
                            </tr>
                        </thead>
                    </table>
                </main>

                
        </main>
    </div>

<script>
    $(document).ready(function() {
        var table = $('#rejected_enrollees_table').DataTable({
            //
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': 'rejectedListData.php',
                // 'success': function(data) {
                //   // Handle success response here
                //   console.log('Success:', data);
                // },
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },

            'pageLength': 15,

            'columns': [
                { data: 'name' , orderable: false },
                { data: 'term', orderable: false},
                { data: 'status', orderable: false},
                { data: 'view_button', orderable: false}
            ],
            'ordering': true
        });
     
    });

    function processRejectedEnrollees(pending_enrollees_id){
        
        Swal.fire({
                icon: 'question',
                title: `Are you sure you want to process the rejected Enrollee ID: ${pending_enrollees_id}?`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        url: "../../ajax/enrollment/process_rejected_enrollee.php",
                        type: 'POST',
                        data: {
                            pending_enrollees_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_process"){
                                window.location.href = `../admission/process_enrollment.php?enrollee_details=true&id=${pending_enrollees_id}`;
                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });

                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
</script>



<?php include_once('../../includes/footer.php') ?>
