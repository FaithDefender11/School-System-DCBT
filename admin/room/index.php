
<?php 

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Room.php');

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


?>

<div class="content">
    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h3>Available Room for <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h3>
                </div>

                <div class="action">
                    <a href="create.php">
                        <button type="button" class="clean large success">+ Add New</button>
                    </a>
                </div>
            </header>
            <main>

                <table id="room_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Room ID</th>
                            <th>Name</th>
                            <th>Assign Section</th>
                            <th>Capacity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $query = $con->prepare("SELECT 
                            
                                t1.*, t2.program_section
                                FROM room as t1
                            
                                LEFT JOIN course as t2 ON t2.course_id = t1.course_id");

                            $query->execute();

                            if($query->rowCount() > 0){
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                                    
                                    $room_id = $row['room_id'];
                                    $room_name = $row['room_name'];
                                    $room_capacity = $row['room_capacity'];
                                    $program_section = $row['program_section'] ?? "-";

                                    $removeRoomBtn = "removeRoomBtn($room_id)";


                                    $room = new Room($con, $room_id);

                                    $room_assigned = $room->GetRoomSectionFilled($room_id, $current_school_year_period);

                                    $room_assigned = $room_assigned ?? "-";
                                    
                                    echo "
                                        <tr>
                                            <td>$room_id</td>
                                            <td>$room_name</td>
                                            <td>$room_assigned</td>
                                            <td>$room_capacity</td>
                                            <td>
                                                <a href='edit.php?id=$room_id'>
                                                    <button class='btn btn-primary'>
                                                        <i class='fas fa-pen'></i>
                                                    </button>
                                                </a>
                                                <button onclick='$removeRoomBtn' class='btn btn-danger'>
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
    function removeRoomBtn(room_id){
        Swal.fire({
                icon: 'question',
                title: `I agreed to removed Room ID: ${room_id}`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/room/remove_room.php",
                        type: 'POST',
                        data: {
                            room_id
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

                                $('#room_table').load(
                                    location.href + ' #room_table'
                                );
                            });
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