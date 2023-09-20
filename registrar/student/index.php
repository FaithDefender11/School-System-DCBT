

<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');

    $student_unique_id_val = NULL;
    $search_word = NULL;

    // if(isset($_POST['search_student'])
    //     && isset($_POST['student_unique_id_val'])
    //     && isset($_POST['search_word'])
    // ){

    //     $search_word = $_POST['search_word'];


    //     $student_unique_id_val = $_POST['student_unique_id_val'];

    //     echo "Search results: $search_word";
        
    // }

    $selected_student_filter = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" 
        && isset($_POST["student_filter"])) {

        $selected_student_filters = $_POST["student_filter"];

        foreach ($selected_student_filters as $selected_filter) {
            // echo $selected_filter . "<br>";

          $selected_student_filter = $selected_filter;

        }
    }

    // echo $selected_student_filter;
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

    <script src="search_student.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    
    <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  </head>


<div class="content">

    <main>

        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h4 style="font-weight:bold;">Student details</h4>
                </div>
            
                <div class="action">
                    <form style="display: flex;" method="POST" id="student_filter_form">
                        <div style="margin-right: 15px;" class="form-group">
                            <label for="active">Active</label>
                            <input type="checkbox" id="active" name="student_filter[]" value="1" 
                                class="form-control" 
                                onchange="handleCheckboxChange('active')" <?php if (isset($_POST["student_filter"]) && in_array("1", $_POST["student_filter"])) echo "checked"; ?>>

                        </div>
                        <div class="form-group">
                            <label for="inactive">Inactive</label>
                            <input type="checkbox" id="inactive" name="student_filter[]" value="0" 
                                class="form-control" onchange="handleCheckboxChange('inactive')" <?php if (isset($_POST["student_filter"]) && in_array("0", $_POST["student_filter"])) echo "checked"; ?>>

                        </div>
                    </form>
                </div>

            </header>

            <main>
                 <table style="width: 100%" id="student_table" class="a">
                    <thead>
                        <tr>
                            <th>Student Id</th>  
                            <th>Name</th>
                            <th>Level</th>  
                            <th>Section</th>  
                            <th>Status</th>  
                            <th>Type</th>  
                            <th>Requirements</th>  
                            <th></th>  
                        </tr>
                    </thead>
                </table>

            </main>

        </div>
    </main>
    
</div>

<script>

    function submitForm() {
        document.getElementById("student_filter_form").submit();
    }

    function handleCheckboxChange(checkboxId) {
        if (checkboxId === "active") {
            if (document.getElementById("active").checked) {
                document.getElementById("inactive").checked = false;
            }
        } else if (checkboxId === "inactive") {
            if (document.getElementById("inactive").checked) {
                document.getElementById("active").checked = false;
            }
        }
        document.getElementById("student_filter_form").submit();
    }

    $(document).ready(function() {

        var selected_student_filter = `
            <?php echo $selected_student_filter; ?>
        `;

        selected_student_filter = selected_student_filter.trim();

        var table = $('#student_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `studentListData.php?status=${selected_student_filter}`,
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
            'pageLength': 10,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available data to be evaluated.",
            },
            'columns': [
                { data: 'student_id', orderable: true },
                { data: 'name' , orderable: false },
                { data: 'course_levelx', orderable: false},
                { data: 'program_section', orderable: false},
                { data: 'status', orderable: false},
                { data: 'type', orderable: false},
                { data: 'requirement', orderable: false},
                { data: 'view_button', orderable: false}
            ],
            'ordering': true

        });

        var ad = table.context;

        // console.log(ad.json)
        var sec = ad[0];

        // console.log(sec['']);
        // sec -> json -> data = all data in the server placed in that array.
        // console.log(sec);
    });
</script>

