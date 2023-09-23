<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/StudentRequirement.php');
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
            <header>
                <div class="title">
                <h3>Student Requirements Checklist</h3>
                </div>
            </header>

            <div class="filters">
                <!-- <table>
                    <tr>
                        <th rowspan="2" style="border-right: 2px solid black">
                        Search by
                        </th>
                        <th><button>ID number</button></th>
                        <th><button>Name</button></th>
                        <th><button>Status</button></th>
                        <th><button>Section</button></th>
                    </tr>
                </table> -->
            </div>

            <table id="requirement_table" class="a" style="margin: 0">
                <thead>
                    <tr class="text-center"> 
                        <th >Student Id</th>  
                        <th >Name</th>  
                        <th >Section</th>  
                        <th >Status</th>  
                        <th >Form 137</th>  
                        <th >Good Moral</th>  
                        <th >PSA</th>  
                        <th >Action</th>  
                    </tr>	
                </thead> 	
            </table>
        </div>
    </main>
</div>

<script>

    $(document).ready(function() {

        var table = $('#requirement_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `requirementListData.php`,
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },

            'pageLength': 15,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available data for enrolled students."
            },
            'columns': [
                { data: 'student_id', orderable: false },
                { data: 'name' , orderable: false },
                { data: 'program_section', orderable: false},
                { data: 'status', orderable: false},
                { data: 'form_137', orderable: false},
                { data: 'good_moral', orderable: false},
                { data: 'psa', orderable: false},
                { data: 'view_button', orderable: false}
            ],
            'ordering': true
        });
     
    });


</script>
<?php include_once('../../includes/footer.php') ?>



