
<?php 

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');

    $section = new Section($con);

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

?>

<div class="content">


    <main>
        <div class="floating" id="shs-sy">
            <header>

                <div class="title">
                    <h3 style="font-weight: bold;">To remove Section</h3>
                </div>

                <div class="action">
                    <button onclick="removeAllSection('<?php echo $current_school_year_term?>')" class="danger">
                       <i class="fas fa-times-circle"></i> All</button>
                </div>
            </header>
            <main>
                <table id="remove_table_section"
                    class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Section ID</th>
                            <th>Name</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = $con->prepare("SELECT * FROM course

                                WHERE is_remove = 1
                                AND school_year_term =:school_year_term
                            ");

                            $query->bindParam(":school_year_term", $current_school_year_term);
                            $query->execute();

                            if($query->rowCount() > 0){

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $course_id = $row['course_id'];
                                    $program_section = $row['program_section'];

                                    $removeSection = "removeSection($course_id)";
                                    echo "
                                        <tr>
                                            
                                            <td>$course_id</td>
                                            <td>$program_section</td>
                                            <td>
                                                <button onclick='$removeSection' class='btn btn-danger'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>
                                        </tr>
                                    
                                    ";
                                }
                            }

                        ?>
                    </tbody>
                </table>
            </main>
        </div>
    </main>

</div>


<script>

    function removeAllSection(school_year_term){
        Swal.fire({
                icon: 'question',
                title: `I agreed to removed All Section in the list`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/section/removeAll.php",
                        type: 'POST',
                        data: {
                            school_year_term
                        },
                        success: function(response) {
                            response = response.trim();

                            // console.log(response);
                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Removed`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                $('#remove_table_section').load(
                                    location.href + ' #remove_table_section'
                                );
                            });
                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                }
                else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }

    function removeSection(course_id){

        var course_id = parseInt(course_id);
        
        Swal.fire({

                icon: 'question',
                title: `I agreed to removed Section ID: ${course_id}`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "../../ajax/section/remove_section.php",
                        type: 'POST',
                        data: {
                            course_id
                        },
                        success: function(response) {
                            response = response.trim();

                            // console.log(response);
                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Removed`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                $('#remove_table_section').load(
                                    location.href + ' #remove_table_section'
                                );
                            });
                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                }
                else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
</script>