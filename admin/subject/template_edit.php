<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    if(isset($_GET['id'])){

        $subject_template_id = $_GET['id'];
        
        // echo $subject_template_id;

        $school_year = new SchoolYear($con, null);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

        $get_subject_template = $con->prepare("SELECT 

            t1.*, t2.department_id

            FROM subject_template as t1
            LEFT JOIN program as t2 ON t2.program_id = t1.program_id

            WHERE t1.subject_template_id=:subject_template_id

            LIMIT 1");
        
        $get_subject_template->bindParam(":subject_template_id",
            $subject_template_id);

        $get_subject_template->execute();

        
        $department_type = "";

        if(isset($_SESSION['department_type'])){
            $department_type =  $_SESSION['department_type'];
        }

        if($get_subject_template->rowCount() > 0){

            $row = $get_subject_template->fetch(PDO::FETCH_ASSOC);

            // echo "inside";

            $subject_title = $row['subject_title'];
            $subject_code = $row['subject_code'];
            $unit = $row['unit'];
            $description = $row['description'];
            $pre_requisite_title = $row['pre_requisite_title'];
            $subject_type = $row['subject_type'];

            $template_program_id = $row['program_id'];

            $template_program_department_id = $row['department_id'];

            $program = new Program($con, $template_program_id);

            $db_program_name = $program->GetProgramName();

            // echo $db_program_name;

            // $programDropdown = "";
            $programDropdown = $program->CreateProgramDropdown($template_program_id,
                $template_program_department_id);
            
            // TODO. 1. FIX the Universal Edit, it did not work.
            // 2. Remove Functionality.

            // Fix also on the tertiary template crud side as the same SHS behavior..

            // $program_id = $row['program_id'];
            
            if(isset($_POST['edit_subject_template'])){
                
                $subject_title = $_POST['subject_title'];
                $subject_code = $_POST['subject_code'];
                $description = $_POST['description'];
                $pre_requisite_title = $_POST['pre_requisite_title'];
                $unit = $_POST['unit'];
                $subject_type = $_POST['subject_type'];
                $edit_program_id = $_POST['program_id'] ?? 0;

                // echo $edit_program_id;

                if($subject_type !== "Core" && $edit_program_id == NULL){

                    // Alert::error("If Subject Type is not 'Core', You should select a Program..",
                    //     "template_edit.php?id=$subject_template_id");

                    // exit();
                    echo "If Subject Type is not 'Core', You should select a Program..";
                    echo "
                        <a href='template_edit.php?id=$subject_template_id'>
                            <button class='btn btn-primary'>Go Back</button>
                        </a>
                    ";
                    return;
                }

                // Update the record in the database
                $query = $con->prepare("UPDATE subject_template 

                    SET subject_title = :subject_title,
                    subject_code = :subject_code,
                    description = :description,
                    pre_requisite_title = :pre_requisite_title,
                    unit = :unit,
                    subject_type = :subject_type,
                    program_id = :program_id

                    WHERE subject_template_id = :subject_template_id");

                $query->bindValue(":subject_title", $subject_title);
                $query->bindValue(":subject_code", $subject_code);
                $query->bindValue(":description", $description);
                $query->bindValue(":pre_requisite_title", $pre_requisite_title);
                $query->bindValue(":unit", $unit);
                $query->bindValue(":subject_type", $subject_type);
                $query->bindValue(":subject_template_id", $subject_template_id);
                $query->bindValue(":program_id", $edit_program_id);

                if($query->execute()){
                    Alert::success("Template Successfully Edited", "template_list.php");
                    exit();
                }
            }


            ?>

            <div class='col-md-12 row'>
                <div class='card'>
                    <div class='card-header'>
                        <a href="template_list.php">
                            <button class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </a>
                        <h4 class='text-center mb-3'>Edit Subject Program</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST'>
                            <div class='form-group mb-2'>
                                <label for=''>Subject Code</label>
                                <input class='form-control' value='<?php echo $subject_code ?>' type='text' placeholder='Subject Code' name='subject_code'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Title</label>
                                <input class='form-control' value='<?php echo $subject_title ?>' type='text' placeholder='Subject Title' name='subject_title'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Description</label>
                                <textarea class='form-control' placeholder='Subject Description' name='description'><?php echo $description ?></textarea>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Pre-Requisite</label>
                                <input class='form-control' value='<?php echo $pre_requisite_title ?>' type='text' placeholder='Pre-Requisite' name='pre_requisite_title'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Choose Subject Type</label>
                                <select class='form-control' name='subject_type' id="subject_type">
                                    <option value='Core'<?php echo ($subject_type == 'Core' ? " selected" : "") ?>>Core</option>
                                    <option value='Applied'<?php echo ($subject_type == 'Applied' ? " selected" : "") ?>>Applied</option>
                                    <option value='Specialized'<?php echo ($subject_type == 'Specialized' ? " selected" : "") ?>>Specialized</option>
                                </select>
                            </div>

                            <input type="hidden" 
                                value="<?php echo $department_type;?>" 
                                id="department_type" name="department_type">


                            <?php echo $programDropdown; ?>
                            <div class='form-group mb-2'>
                                <label for=''>Units</label>
                                <input value='<?php echo $unit ?>' class='form-control' type='text' placeholder='Unit' name='unit'>
                            </div>
                            <button type='submit' class='btn btn-primary' name='edit_subject_template'>Save</button>
                        </form>
                    </div>
                </div>

                <script>
                    $('#subject_type').on('change', function() {

                        var subject_type = $(this).val();
                        var department_type = $("#department_type").val();
                        var program_id = $("#program_id").val();
                        var programName = "";
                        
                        // console.log(subject_type)
                        // console.log(program_id)

                        // $programDropdown = $program->
                        // CreateProgramDropdownDepartmentBased($department_type);

                        if(subject_type === "Core"){

                            $.ajax({
                                url: '../../ajax/section/get_program.php',
                                type: 'POST',
                                data: {
                                    subject_type,
                                    department_type
                                },

                                dataType: 'json',
                                success: function(response) {
                                    console.log(response);

                                    var options = '<option value="">Core is not included.</option>';
                                    
                                    $('#program_id').html(options);

                                    // console.log('length!!')

                                }
                            });
                        }

                        // console.log(department_type)

                        if(subject_type !== "Core"){

                            var programName = "<?php echo $db_program_name; ?>";

                            $.ajax({
                                url: '../../ajax/section/get_program.php',
                                type: 'POST',
                                data: {
                                    subject_type,
                                    department_type
                                },

                                dataType: 'json',

                                success: function(response) {

                                    if(response.length > 0){
                                        // console.log(response);

                                        var options = '<option disabled value="">Choose Program</option>';
                                        
                                        $.each(response, function(index, value) {

                                            var selected = "";

                                            if(programName == value.program_name && subject_type !== "Core"){
                                                selected = "selected";
                                            }

                                            // options += '<option value="' + value.program_id + '"> ' + value.program_name +'</option>';
                                            options += `<option ${selected} value="${value.program_id}">${value.program_name}</option>`;

                                        });

                                        $('#program_id').html(options);

                                    }else{
                                        // $('#program_id').html("");
                                        console.log('length was zero.')
                                    }
                                }
                            });
                        }
                    });
                </script>

            </div>


            <?php
            // echo "
            //     <div class='col-md-12 row'>

            //         <div class='card'>
            //             <div class='card-header'>
            //                 <h4 class='text-center mb-3'>Edit Subject Program</h4>
            //             </div>

            //             <div class='card-body'>
            //                 <form method='POST'>

            //                     <div class='form-group mb-2'>
            //                         <label for=''>Subject Code</label>
            //                         <input class='form-control' value='$subject_code' type='text' placeholder='Subject Code' name='subject_code'>
            //                     </div>

            //                     <div class='form-group mb-2'>
            //                         <label for=''>Title</label>
            //                         <input class='form-control' value='$subject_title' type='text' placeholder='Subject Title' name='subject_title'>
            //                     </div>

            //                     <div class='form-group mb-2'>
            //                         <label for=''>Description</label>

            //                         <textarea class='form-control'
            //                         placeholder='Subject Description'
            //                         name='description'>$description</textarea>
            //                     </div>

            //                     <div class='form-group mb-2'>
            //                         <label for=''>Pre-Requisite</label>

            //                         <input class='form-control' value='$pre_requisite_title' type='text'
            //                             placeholder='Pre-Requisite' name='pre_requisite_title'>
            //                     </div>
            
            //                     <div class='form-group mb-2'>
            //                         <label for=''>Choose Subject Type</label>

            //                         <select class='form-control' name='subject_type'>
            //                             <option value='Core'" . ($subject_type == 'Core' ? " selected" : "") . ">Core</option>
            //                             <option value='Applied'" . ($subject_type == 'Applied' ? " selected" : "") . ">Applied</option>
            //                             <option value='Specialized'" . ($subject_type == 'Specialized' ? " selected" : "") . ">Specialized</option>
            //                         </select>
            //                     </div>

            //                     <div class='form-group mb-2'>
            //                         <label for=''>Units</label>

            //                         <input value='$unit' class='form-control' type='text' placeholder='Unit' name='unit'>
            //                     </div>

            //                     <button type='submit' class='btn btn-primary'
            //                         name='edit_subject_template'>Save</button>
            //                 </form>
            //             </div>
            //         </div>
            //     </div>
            // ";
        }else{
            echo "nothing ";
        }
    }

?>


