<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/Template.php');
    include_once('../../includes/classes/Program.php');

    ?>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        </head>
    <?php

    $subject = new Subject($con, null);
    $program = new Program($con, null);

    $department_type = "";

    if(isset($_SESSION['department_type'])){
        $department_type =  $_SESSION['department_type'];
    }

    $programDropdown = $program->CreateProgramDropdownDepartmentBased(
        $department_type);

    if(isset($_GET['type']) && $_GET['type'] == "shs"){

        $type = $_GET['type'];

        if($_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST['create_subject_template'])
            && isset($_POST['subject_title'])
            && isset($_POST['subject_type'])
            && isset($_POST['unit'])
            && isset($_POST['description'])
            && isset($_POST['subject_code'])
            // && isset($_POST['pre_requisite_title'])
            
        ){

            $program_type = $type === "shs" ? 0 : ($type === "tertiary" ? 1 : "");

            $pre_requisite_title = $_POST['pre_requisite_title'] ?? "";

            // var_dump($pre_requisite_title);

            // return;

            $subject_title = $_POST['subject_title'];
            
            $subject_type = $_POST['subject_type'];
            $unit = $_POST['unit'];
            $description = $_POST['description'];
            $subject_code = $_POST['subject_code'];
            $program_id = $_POST['program_id'];

            $create = $con->prepare("INSERT INTO subject_template
                (subject_title, unit, subject_type,  pre_requisite_title,
                description, subject_code, program_type, program_id)

                VALUES(:subject_title, :unit, :subject_type, :pre_requisite_title,
                :description, :subject_code, :program_type, :program_id)");
                
            $create->bindParam(':subject_title', $subject_title);
            $create->bindParam(':pre_requisite_title', $pre_requisite_title);
            $create->bindParam(':subject_type', $subject_type);
            $create->bindParam(':unit', $unit);
            $create->bindParam(':description', $description);
            $create->bindParam(':subject_code', $subject_code);
            $create->bindParam(':program_type', $program_type);
            $create->bindParam(':program_id', $program_id);

            if($create->execute()){

                $template_id = $con->lastInsertId();

                $template = new Template($con, $template_id);

                $template_subject = $template->GetTemplateSubjectName();

                Alert::success("Template Subject: $template_subject has been created in the system.", "template_list.php");
                exit();
            }

        }
        
        ?>
            <div class='col-md-10 row offset-md-1'>
    
                <div class='card'>
                    <div class='card-header'>
                        <a href="template_list.php">
                            <button class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </a>
                        <h4 class='text-center mb-3'>Create Template Subject (<?php echo $department_type;?>)</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST'>

                            <div class='form-group mb-2'>
                                <label for=''>* Subject Code</label>
                                <input required class='form-control' type='text' placeholder='Subject Code' name='subject_code'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>* Title</label>
                                <input  required class='form-control' type='text' placeholder='Subject Title' name='subject_title'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>* Description</label>
                                <textarea  required class='form-control' placeholder='Subject Description' name='description'></textarea>
                            </div>
                    
                            <div class='form-group mb-2'>
                                <label for=''>Pre-requisite</label>
                                <input  class='form-control' type='text' placeholder='Pre-Requisite' name='pre_requisite_title'>
                            </div>
        
                            <div class='form-group mb-2'>
                                
                                <label for=''>* Choose Subject Type</label>
                                <select  required class='form-control' id="subject_type" name="subject_type">

                                    <option value='' disabled selected>Select Type</option>
                                    <option value='Core'>Core</option>
                                    <option value='Applied'>Applied</option>
                                    <option value='Specialized'>Specialized</option>
                                </select>
                            </div>

                            <input type="hidden" value="<?php echo $department_type;?>" id="department_type" name="department_type">
                            <!-- <?php $programDropdown; ?> -->

                            <div class='form-group mb-2'>
                                <label class='mb-2'>Program</label>

                                <select  class="form-select" name="program_id" id="program_id">
                                    <option value="" selected>Core is not included</option>
                                </select>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Units</label>
                                <input  required class='form-control' value='3' type='text' 
                                    placeholder='Unit' name='unit'>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' 
                                    class='btn btn-success' name='create_subject_template'>
                                    Save
                                </button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        <?php
        
    }

    if(isset($_GET['type']) && $_GET['type'] == "tertiary"){

        $type = $_GET['type'];

        // $form = $subject->createFormModified($type, $programDropdown);

        if(isset($_POST['create_subject_template'])
            && isset($_POST['subject_title'])
            && isset($_POST['subject_type'])
            && isset($_POST['unit'])
            && isset($_POST['description'])
            && isset($_POST['subject_code'])
            // && isset($_POST['program_id'])
            && isset($_POST['pre_requisite_title'])
        ){

            $program_type = $type === "shs" ? 0 : ($type === "tertiary" ? 1 : "");

            $pre_requisite_title = $_POST['pre_requisite_title'];

            // var_dump($pre_requisite_title);
            // return;
            $subject_title = $_POST['subject_title'];
            $subject_type = $_POST['subject_type'];
            $unit = $_POST['unit'];
            $description = $_POST['description'];
            $subject_code = $_POST['subject_code'];
            $program_id = $_POST['program_id'];

            $create = $con->prepare("INSERT INTO subject_template
                (subject_title, unit, subject_type, pre_requisite_title,
                description, subject_code, program_type, program_id)

                VALUES(:subject_title, :unit, :subject_type, :pre_requisite_title,
                :description, :subject_code, :program_type, :program_id)");
                
            $create->bindParam(':subject_title', $subject_title);
            $create->bindParam(':subject_type', $subject_type);
            $create->bindParam(':pre_requisite_title', $pre_requisite_title);
            $create->bindParam(':unit', $unit);
            $create->bindParam(':description', $description);
            $create->bindParam(':subject_code', $subject_code);
            $create->bindParam(':program_type', $program_type);
            $create->bindParam(':program_id', $program_id);

            if($create->execute()){

                $template_id = $con->lastInsertId();

                $template = new Template($con, $template_id);

                $template_subject = $template->GetTemplateSubjectName();

                Alert::success("Template Subject: $template_subject has been created in the system.", "template_list.php");
                exit();
            }
        }

        ?>
            <div class='col-md-10 row offset-md-1'>

                <div class='card'>
                    <div class='card-header'>
                        <a href="template_list.php">
                            <button class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </a>
                        <h4 class='text-center mb-3'>Create Template Subject (<?php echo $department_type;?>)</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST'>

                            <div class='form-group mb-2'>
                                <label for='subject_code'>* Subject Code</label>
                                <input required class='form-control' id="subject_code" type='text' placeholder='Subject Code' name='subject_code'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for='subject_title'>* Title</label>
                                <input required class='form-control' id="subject_title" type='text' placeholder='Subject Title' name='subject_title'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>* Description</label>
                                <textarea required class='form-control' placeholder='Subject Description' name='description'></textarea>
                            </div>
                    
                            <div class='form-group mb-2'>
                                <label for=''>Pre-requisite</label>
                                <input class='form-control' type='text' placeholder='Pre-Requisite' name='pre_requisite_title'>
                            </div>
        
                            <div class='form-group mb-2'>
                                
                                <label for=''>* Choose Subject Type</label>
                                <select required class='form-control' id="subject_type" name="subject_type">

                                    <option value='' disabled selected>Select Type</option>
                                    <option value='Core'>Core</option>
                                    <option value='Applied'>Applied</option>
                                    <option value='Specialized'>Specialized</option>
                                </select>
                            </div>

                            <input type="hidden" value="<?php echo $department_type;?>"
                                id="department_type" name="department_type">

                            <div class='form-group mb-2'>
                                <label class='mb-2'>* Program</label>

                                <select class="form-select" name="program_id" id="program_id">
                                    <option value="" selected>Core is not included</option>
                                </select>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>* Units</label>
                                <input required class='form-control' value='3' type='text' 
                                    placeholder='Unit' name='unit'>
                            </div>

                            <button type='submit' class='btn btn-primary'
                                name='create_subject_template'>Save</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    }
    
    if(isset($_GET['type']) && $_GET['type'] != "tertiary" && $_GET['type'] != "shs"){
        // System should provide selection of subject template between SHS and Tertiary.
        echo "choose between";
    }

?>

<script>

    $('#subject_type').on('change', function() {

        var subject_type = $(this).val();
        var department_type = $("#department_type").val();
        
        // console.log(department_type)

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

        if(subject_type !== "Core"){

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

                    if(response.length > 0){
                        console.log(response);

                        var options = '<option value="">Choose Program</option>';
                        
                        $.each(response, function(index, value) {

                            options += '<option value="' + value.program_id + '"> ' + value.program_name +'</option>';

                        });

                        $('#program_id').html(options);
                    }
                    // response = response.trim();
                }
            });
        }
    });
</script>

