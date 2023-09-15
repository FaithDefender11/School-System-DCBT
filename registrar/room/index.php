
<?php 

    include_once('../../includes/registrar_header.php');
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
                        <button type="button"
                            onclick="window.location.href='opened_section.php?id=<?php echo $current_school_year_id; ?>'"
                        class="information default large">
                            Room Section
                        </button>
                </div>
            </header>
            <main>

                <table id="room_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Room ID</th>
                            <th>Name</th>
                            <th>Capacity</th>
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
                                            <td>$room_capacity</td>
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
   
</script>