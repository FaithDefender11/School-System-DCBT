<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');
    
    $school_year = new SchoolYear($con);
    $section = new Section($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];
 
    // $processEnrolled = true;
    // $current_enrollment_course_id = 1281;

    // $originalValueIsFalse = false;

    
    // $enrollment = new Enrollment($con);

    // $check = $enrollment->GetAllRegistrarIdIntheEnrollment(
    //     $current_school_year_id, $registrarUserId);

    // $base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

    // $dirname = dirname($_SERVER['PHP_SELF']);
    // $after_registrar = substr($dirname, strpos($dirname, 'registrar/') + strlen('registrar/'));

    // var_dump($after_registrar);
    
    // foreach ($check as $key => $value) {
    //     # code...
    //     $currently_registrar_id = $value['currently_registrar_id'];

    //         # If other registrar is not in this folder.
    //         if($after_registrar !== "student" 
    //             || $after_registrar !== "dashboard" 
    //             || $after_registrar !== "section" 
    //             || $after_registrar !== "enrollment" 
    //             || $after_registrar !== "room" ){
                   

    //             echo "currently_registrar_id: $currently_registrar_id is not in this page";
    //             echo "<br>";
    //         }
    // }
    // var_dump($check);

    # We are in the every 30 seconds function trigger.

    # Get all registrar id in the enrollment form
    # Check each registrar id is present to the whole php page of registrar route.
    # If registrar id is not present in the whole page & it has an registrar id in the enrollment form
    # then, we consider this as Inactive.
    # and his registrar id tin the enrollment form should removed

    // $course_fulled_ids = $section->GetSectionWhoReachedTheMaximumCapacityOnEnrollment(
    //     $current_school_year_id);
    
    // // $course_fulled_ids = [1281, 1289];
    // // print_r($course_fulled_ids);

    // // Create an array of placeholders for the course_ids
    // $placeholders = array_map(function ($id) {
    //     return ":course_id_$id";
    // }, $course_fulled_ids);

    // // print_r($placeholders);

    // $query = $con->prepare("SELECT course_id FROM course

    //     WHERE program_id = :program_id
    //     AND course_id NOT IN (" . implode(',', $placeholders) . ")

    //     AND active = 'yes'
    //     AND is_full = 'no'
    //     AND school_year_term = :school_year_term
    // ");

    // $query->bindValue(":program_id", 25);
    // $query->bindValue(":school_year_term", $current_school_year_term);

    // // Bind each course_id from the array
    // foreach ($course_fulled_ids as $course_id) {
    //     $paramName = ":course_id_$course_id";
    //     $query->bindValue($paramName, $course_id);
    // }

    // $query->execute();

    // if ($query->rowCount() > 0) {

    //     $res = $query->fetchAll(PDO::FETCH_ASSOC);
    //     // print_r($res);

    // }



    $reachedMaxEnrollmentArr = [];

    $query = $con->prepare("SELECT t1.* FROM enrollment as t1 


        -- WHERE t1.registrar_id=:registrar_id
        WHERE t1.enrollment_status=:enrollment_status
        AND t1.school_year_id = :school_year_id

    ");

    // $query->bindValue(":registrar_id", $registrarUserId);
    $query->bindValue(":enrollment_status", "tentative");
    $query->bindValue(":school_year_id", $current_school_year_id);
    $query->execute();

    if($query->rowCount() > 0){

        while($row = $query->fetch(PDO::FETCH_ASSOC)){

            $course_id = $row['course_id'];
            $enrollment_id = $row['enrollment_id'];
            $school_year_id = $row['school_year_id'];
            $school_year_id = $row['school_year_id'];


            $section = new Section($con, $course_id);
            $sectionCapacity = $section->GetSectionCapacity();

            $count = $section->GetEnrollmentCourseIdEnrolledCount($course_id, $school_year_id);

            if($sectionCapacity == $count){

                array_push($reachedMaxEnrollmentArr, $row);

            }
        }
    }

    // print_r($reachedMaxEnrollmentArr);

    # You placed the student to te ABE1-A Capacity: 3
    # ABE1-A has already 3 enrolled student

    # Show that student which you have been processed.


 

?>

<div class="content">

    <!-- <?php echo Helper::RegistrarDepartmentSection(false,
        "shs_index", "tertiary_index");?> -->

    <main>

        <div class="floating" id="shs-sy">

            <header>

                <div class="title">
                    <!-- <h3 style="font-weight: bold;">My processed conflict section enrollment form</h3> -->
                    <h3 style="font-weight: bold;">Dashboard</h3>
                </div>

            </header>

            <main>


            </main>

        </div>
    </main>

</div>
<?php include_once('../../includes/footer.php') ?>


 
<script>

    function removeCurrentRegistrarIdBtn(registrarId){

        var registrarId = parseInt(registrarId);

        Swal.fire({
            icon: 'question',
            title: 'This will remove registrar ID in the form?',
            text: 'Important! This action cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../../ajax/super_admin/removeRegistrarCurrentlyId.php",
                    type: 'POST',
                    data: {
                        registrarId: registrarId // Make sure you pass the data in the correct format
                    },
                    dataType: 'text', // Specify the expected response data type
                    success: function (response) {
                        response = response.trim();
                        console.log(response);

                        if (response === "success_update") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Selected registrar id has been pulled out.',
                                showConfirmButton: false,
                                timer: 1500, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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
                                location.reload();
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle any errors here
                    }
                });
            } else {
                // User clicked "No," perform alternative action or do nothing
            }
        });

        
    }

    function removeCurrentCashierIdBtn(cashierId){

        var cashierId = parseInt(cashierId);

        Swal.fire({
            icon: 'question',
            title: 'This will remove cashier ID in the form?',
            text: 'Important! This action cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'

        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../../ajax/super_admin/removeCashierCurrentlyId.php",
                    type: 'POST',
                    data: {
                        cashierId: cashierId // Make sure you pass the data in the correct format
                    },
                    dataType: 'text', // Specify the expected response data type
                    success: function (response) {
                        response = response.trim();
                        console.log(response);

                        if (response === "success_update") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Selected registrar id has been pulled out.',
                                showConfirmButton: false,
                                timer: 1500, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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
                                location.reload();
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle any errors here
                    }
                });
            } else {
                // User clicked "No," perform alternative action or do nothing
            }
        });

        
    }

    // document.addEventListener('DOMContentLoaded', function() {
    //     var validNavigation = false;

    //     console.log('qweqwe')

    //     // Attach the event keypress to exclude the F5 refresh
    //     document.addEventListener('keypress', function(e) {
    //         if (e.keyCode === 116) {
    //             validNavigation = true;
    //         }

    //         if ((e.ctrlKey || e.metaKey) && e.keyCode === 82) {
    //             validNavigation = true;
    //         }
    //     });


    //     // Attach the event click for all links in the page
    //     document.querySelectorAll('a').forEach(function(link) {
    //         link.addEventListener('click', function() {
    //             validNavigation = true;
    //         });
    //     });

    //     // Attach the event submit for all forms in the page
    //     document.querySelectorAll('form').forEach(function(form) {
    //         form.addEventListener('submit', function() {
    //             validNavigation = true;
    //         });
    //     });

    //     // Attach the event click for all inputs in the page
    //     document.querySelectorAll('input[type=submit]').forEach(function(input) {
    //         input.addEventListener('click', function() {
    //             validNavigation = true;
    //         });
    //     });

    //     window.onbeforeunload = function() {
    //         if (validNavigation === false) {

    //             var status = 'abandoned';

    //             console.log('abandoned')

    //             // // Use the fetch API for AJAX request

    //             $.ajax({
    //                 url: '../../ajax/schedule/samp.php',
    //                 type: 'POST',
    //                 data: {
    //                     status,
    //                 },
    //                 dataType: 'json',

    //                 success: function(response) {

    //                     // response = response.trim();

    //                     console.log(response);

    //                     if(response.length > 0){
    //                         var options = '<option selected value="">Available Sections</option>';
                            
    //                         $.each(response, function (index, value) {
    //                             options +=
    //                             '<option value="' + value.course_id + '">' + value.program_section + '</option>';
    //                         });

    //                         $('#course_id').html(options);
    //                     }else{
    //                         $('#course_id').html('<option selected value="">No data found(s).</option>');

    //                     }
    //                 }
    //             });
    //         }
    //     };

    // });


    // function updateLastActivity() {

    //     let update_activity = "update_activity";

    //    $.ajax({
    //         url: '../../ajax/schedule/samp.php',
    //         type: 'POST',
    //         data: {
    //             update_activity
    //         },
    //         // dataType: 'json',

    //         success: function (response) {
    //             // Optional: Handle the response data as needed
    //             console.log(`executed.. ${response}`);
    //         },
    //         error: function (xhr, status, error) {
    //             console.error('Error:', status, error);
    //         }
    //     });
    // }

    // // Update last activity every 30 seconds (adjust as needed)
    // setInterval(updateLastActivity, 2500);

     

let isRefreshing = false;

// Add a beforeunload event listener to update the activity and reset registrar ID when the user closes the browser/tab
// window.addEventListener('beforeunload', function (event) {
//     // Check if the event is due to a page refresh
//     if (event.persisted) {
//         // Page is being refreshed
//         isRefreshing = true;
//     } else {
//         // Page is being closed or navigating away
//         // Make a final update before leaving only if not refreshing
//         if (!isRefreshing) {
//             updateLastActivity();
//             resetRegistrarIdOnClose();
//         }
//     }
// });

// // Event listener for page visibility changes (e.g., switching tabs)
// document.addEventListener('visibilitychange', function () {
//     if (document.visibilityState === 'visible') {
//         // Page is visible, reset the refreshing flag
//         isRefreshing = false;
//     }
// });

// // Function to update last activity timestamp
// function updateLastActivity() {
//     // ... (as before)
//     console.log('updateLastActivity')

// }

// // Function to reset registrar ID
// function resetRegistrarIdOnClose() {
//     // ... (as before)
//     console.log('resetRegistrarIdOnClose')
// }

</script>

  
