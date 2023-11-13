<?php 

    $selectedDepartmentID = null;

    $changing_department_id = isset($_GET['selected_department_id']) 
        ? $_GET['selected_department_id']
        : $recordsPerPageOptions[0];

    $recordsPerPageRadios = '';
    
    foreach ($offeredDepartment as $option) {

        $checked = ($option['department_id'] == $stored_department_id) ? 'checked' : '';

        $text = $option['department_name'] == "Tertiary" ? "Tertiary" : ($option['department_name'] == "Senior High School" ? "Senior High" : "");
        
        $recordsPerPageRadios .= "<div class='form-element'>";
        $recordsPerPageRadios .= "<label for='selected_department_id_".$option['department_id']."'>$text</label>";
        $recordsPerPageRadios .= "<div>";

        $recordsPerPageRadios .= '<input required type="radio" 
            id="selected_department_id_' . $option['department_id'] . '"
            name="selected_department_id"
            value="' . $option['department_id'] . '" ' . $checked . '>';

        $recordsPerPageRadios .= "</div>";
        $recordsPerPageRadios .= "</div>";
    }
?>
    <hr>
    <div class="enrollment_new">

        <header>
            <div class="title">
                <h3>Student type</h3>
            </div>
        </header>

        <div class="row">
            <span>
                <?php 
                    echo $recordsPerPageRadios;
                ?>
            </span>
        </div>

        <!-- COURSE STRAND SELECTION -->
        <hr>
        
        <header>
            <div class="title">
            <h3>Program & Section</h3>
            </div>
        </header>

        <div class="row">
            <span>
                <div class="form-element courseStrand">
                    <label>Choose Program</label>
                    <div>
                      
                        <select  style="width: 450px;" class='form-control'
                            name="program_id" id="program_id" required>
                            <?php 

                                if($stored_program_name != ""){
                                    echo "
                                      <option value='$stored_program_id' selected >$stored_program_name</option>
                                    ";
                                }
                            
                            ?>

                        </select>
                    </div>
                </div>

                <div class="form-element courseStrand">
                    <label>Choose Section 

                        <!-- <a style="text-decoration: none; color: inherit" href="../section/createe_section.php?id=<?= $program_id;?>>
                            <i class="fas fa-plus-circle"></i>
                        </a> -->
                        
                        <a id="populateSectionCreate"></a>



                    </label>
                    
                    <div>
                        <select  style="width: 350px;" 
                            class='form-control' name="course_id" id="course_id">
                        
                            <?php 
                                // $program_id_val = Helper::DisplayText("program_id", "");
                                if($stored_course_name != ""){
                                    echo "
                                      <option value='$stored_course_id' selected >$stored_course_name</option>
                                    ";
                                }
                            
                            ?>
                        </select>
                    </div>
                </div>
            </span>
        </div>
    </div>
