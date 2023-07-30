<?php 

?>

<div id="pending_enrollment" class="floating">
    <header>
        <div class="title">
            <h4>Enrollment details</h4>
        </div>
        <h3><?php echo $current_school_year_term ?> <?php echo $current_school_year_period == "First" ? "1st" : ($current_school_year_period == "Second" ? "2nd" : ""  );?> Semester</h3>
    </header>
    <main>
        <form method="POST">

            <div class="row">

                <span>
                    <label for="Year">* Type</label>
                    <div>
                        <select onchange="UpdateDepartmentType(this, <?php echo $pending_enrollees_id;?>)" class="form-control text-center" name="department_type" id="department_type">
                            <option <?php if ($pending_type === "Senior High School") echo "selected"; ?> value="SHS">Senior High School</option>
                            <option <?php if ($pending_type === "Tertiary") echo "selected"; ?> value="Tertiary">Tertiary</option>
                        </select>
                    </div>
                </span>

                <span>
                    <label for="grade">* Level</label>
                    <div>
                        <select onchange="UpdatePendingLevel(this, <?php echo $pending_enrollees_id;?>)" class="form-control" name="course_level" id="course_level">

                            <?php 
                                if($pending_type == "SHS"){
                                    ?>
                                        <option class="text-center" value="" selected>* Choose SHS Level</option>
                                        <option class="text-center" value="11" <?php echo $pending_level == 11 ? "selected" : ""; ?>>11</option>
                                        <option class="text-center" value="12" <?php echo $pending_level == 12 ? "selected" : ""; ?>>12</option>
                                    <?php
                                }else if($pending_type == "Tertiary"){
                                    ?>
                                        <option class="text-center" value="" selected>* Choose Tertiary Level</option>
                                        <option class="text-center" value="1" <?php echo $pending_level == 1 ? "selected" : ""; ?>>1</option>
                                        <option class="text-center" value="2" <?php echo $pending_level == 2 ? "selected" : ""; ?>>2</option>
                                        <option class="text-center" value="3" <?php echo $pending_level == 3 ? "selected" : ""; ?>>3</option>
                                        <option class="text-center" value="4" <?php echo $pending_level == 4 ? "selected" : ""; ?>>4</option>
                                    <?php
                                }
                            ?>

                        </select>
                    </div>
                </span>
            </div>

            <div class="row">
                <?php
                
                    if($type == "Tertiary"){
                        ?>
                            <span>
                                <label label for="track">Track</label>

                                <div>
                                    <select class="form-control text-center" style="width: 300px; pointer-events: none;" id="inputTrack" class="form-select">
                                        <?php 

                                            // $SHS_DEPARTMENT = 4;
                                        
                                            $track_sql = $con->prepare("SELECT 
                                                program_id, track, acronym 
                                                
                                                FROM program 

                                                WHERE department_id !=:department_id
                                                GROUP BY track
                                            ");

                                            $track_sql->bindValue(":department_id", $department_id);
                                            $track_sql->execute();
                                            
                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                $row_program_id = $row['program_id'];

                                                $track = $row['track'];

                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                echo "<option class='form-control ' value='$row_program_id' $selected>$track</option>";
                                            }
                                        ?>
                                    
                                    </select>
                                </div>
                            </span>

                            <span>
                                <label for="strand">Courses</label>

                                <select style="width: 300px" class="form-control text-center" onchange="ChooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                    name="strand" id="strand" class="form-select">
                                    <?php 

                                        $SHS_DEPARTMENT = 4;
                                    
                                        $track_sql = $con->prepare("SELECT 
                                            program_id, track, acronym 
                                            
                                            FROM program 
                                            WHERE department_id !=:department_id
                                            GROUP BY acronym
                                        ");

                                        $track_sql->bindValue(":department_id", $department_id);
                                        $track_sql->execute();


                                            echo "<option class='text-center' value=''>Choose Course</option>";

                                        while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                            $row_program_id = $row['program_id'];

                                            $acronym = $row['acronym'];

                                            $selected = ($row_program_id == $program_id) ? "selected" : "";

                                            echo "<option class='text-center' value='$row_program_id' $selected>$acronym</option>";
                                        }
                                    ?>

                                </select>
                            </span>
                        <?php
                    }
                    else if($type == "SHS"){
                        ?>
                            <span>
                                <label label for="track">* Track</label>
                                <div>
                                    <select style="width: 300px" class="form-control text-center" style="pointer-events: none;" id="inputTrack" class="form-select">
                                        <?php 
                                            $SHS_DEPARTMENT = 4;

                                            echo $department_id;
                                        
                                            $track_sql = $con->prepare("SELECT 
                                                program_id, track, acronym 
                                                
                                                FROM program 

                                                WHERE department_id =:department_id
                                                GROUP BY track
                                            ");

                                            $track_sql->bindValue(":department_id", $department_id);
                                            $track_sql->execute();

                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                $row_program_id = $row['program_id'];

                                                $track = $row['track'];

                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                echo "<option value='$row_program_id' $selected>$track</option>";
                                            }
                                        ?>
                                        
                                    </select>
                                </div>
                            </span>

                            <span>
                                <label for="strand">* Strand</label>
                                <select style="width: 300px" class="form-control  text-center" style="width: 170px" onchange="ChooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                    name="strand" id="strand" class="form-select">
                                    <?php 
                                    
                                        $track_sql = $con->prepare("SELECT 
                                            program_id, track, acronym 
                                            
                                            FROM program 
                                            WHERE department_id =:department_id
                                            GROUP BY acronym
                                        ");

                                        $track_sql->bindValue(":department_id", $department_id);
                                        $track_sql->execute();

                                        // if($pending_level == NULL){
                                        //     echo "<option class='text-center' value=''>Choose Strand</option>";
                                        // }else if($pending_level != NULL){
                                        //     // echo $pending_level;
                                        //     echo "<option class='text-center' value='' disabled>Choose Strand</option>";
                                        // }
                                        
                                        echo "<option class='text-center' value=''>Choose Strand</option>";

                                        while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                            $row_program_id = $row['program_id'];

                                            $acronym = $row['acronym'];

                                            $selected = ($row_program_id == $program_id) ? "selected" : "";

                                            echo "<option value='$row_program_id' $selected>$acronym</option>";
                                        }
                                    ?>

                                </select>
                            </span>
                        <?php
                    }
                ?>
            </div>
            
        </form>

    </main>

</div>


<script>

    function ChooseStrand(entity, pending_enrollees_id){

        var program_id = document.getElementById("strand").value;

        // console.log("Selected value: " + program_id);

        var strand = "Strand";

        Swal.fire({
            icon: 'question',
            title: `Are you sure?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/pending/enrollment_details_update.php',
                    type: 'POST',
                    data: {
                        program_id, pending_enrollees_id,
                        strand
                    },
                    success: function(response) {
                        response = response.trim();
                     
                        if(response == "success_update"){
                             
                            setTimeout(function() {
                                location.reload();
                                // $('#pending_available_section').load(location.href + ' #pending_available_section');
                            }, 500);
                          
                        }


                    }
                });
            }

        });
    }

    function UpdatePendingLevel(entity, pending_enrollees_id){

        var course_level = parseInt(document.getElementById("course_level").value);

        console.log("Selected value: " + course_level);

        var level = "Level";
        Swal.fire({
            icon: 'question',
            title: `Are you sure want to change student level?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/pending/enrollment_details_update.php',
                    type: 'POST',
                    data: {
                        course_level, pending_enrollees_id,
                        level
                    },
                    success: function(response) {

                        response = response.trim();
                        // console.log('AJAX Success:', response);


                        if (response === "success_update_level") {
                            console.log('Update successful');
                            // Reload tables
                            setTimeout(function() {
                                location.reload();
                                // $('#pending_available_section').load(location.href + ' #pending_available_section');
                            }, 500);
                            // $('#pending_available_section').load(location.href + ' #pending_available_section');
                            // $('#pending_enrollment').load(location.href + ' #pending_enrollment');
                        } 
                        else {
                            console.log('Update failed');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }

    function UpdateDepartmentType(entity, pending_enrollees_id){

        var department_type = document.getElementById("department_type").value;

        console.log("Selected value: " + department_type);

        var department = "Department";

        Swal.fire({
            icon: 'question',
            title: `Are you sure want to change student level?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/pending/enrollment_details_update.php',
                    type: 'POST',
                    data: {
                        department_type,
                        pending_enrollees_id,
                        department
                    },
                    success: function(response) {

                        response = response.trim();
                        // console.log('AJAX Success:', response);

                        if (response === "success_update") {
                            Swal.fire({
                                icon: 'success',
                                title: `Success`,
                            });
                            setTimeout(() => {
                                Swal.close();
                                location.reload();
                            }, 1000);
                           
                        } 
                        else {
                             Swal.fire({
                                icon: 'warning',
                                title: `Something went wrong. Please contact the admin`,
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }

</script>
