
<?php 


    $pending = new Pending($con, $pending_enrollees_id);

    $doesEnrolleeHasSchoolHistoryMade = $pending->CheckEnrolleeHasSchoolHistory($pending_enrollees_id);

    // var_dump($pending_enrollees_id);

    $school_history = $pending->GetEnrolleeSchoolHistory($pending_enrollees_id);

    // $student_school_history_id = "";
    // $school_name = "";
    // $address = "";
    // $year_started = "";
    // $year_ended = "";

    if($school_history !== NULL){

        // $student_school_history_id = $school_history['student_school_history_id'];
        // $school_name = $school_history['school_name'];
        // $address = $school_history['address'];
        // $year_started = $school_history['year_started'];
        // $year_ended = $school_history['year_ended'];
    }

    $parent = new PendingParent($con, $pending_enrollees_id);

    $school_name = $parent->GetSchoolName();
    $address = $parent->GetSchoolAddress();
    $year_started = $parent->GetSchoolYearStarted();
    $year_ended = $parent->GetSchoolYearEnded();

    // var_dump($address);



    if($_SERVER["REQUEST_METHOD"] === "POST"
        && isset($_POST['student_school_history_btn_' . $pending_enrollees_id])
        && isset($_POST['school_name'])
        && isset($_POST['address'])
        && isset($_POST['year_started'])
        && isset($_POST['year_ended'])
    ){

        $school_name = Helper::ValidateSchoolName($_POST['school_name']);
        $year_started = Helper::sanitizeFormString($_POST['year_started']);

        // echo $year_started;
        // return;
        $year_ended = Helper::sanitizeFormString($_POST['year_ended']);
        $address = Helper::ValidateAddress($_POST['address']);

        // echo "
        // School Name: $school_name <br>
        // Year Started: $year_started <br>
        // Year Ended: $year_ended <br>
        // Address: $address <br>
        // ";

        $defaultRedirect = true;

        $parent_url = "process.php?new_student=true&step=enrollee_parent_information";

        if(empty(Helper::$errorArray)){

            // echo "empty";
            if($parent->CheckEnrolleeHasParent($pending_enrollees_id)){

                # Update School

                $schoolUpdate = $parent->UpdateNewEnrolleeSchoolHistory(
                    $pending_enrollees_id,
                    $school_name, $year_started, $year_ended, $address);

                if($schoolUpdate){

                    $defaultRedirect = false;

                    Alert::successAutoRedirect("School history updated.",
                        $parent_url);

                    exit();
                }

            }
            if($parent->CheckEnrolleeHasParent($pending_enrollees_id) == false){

                # Create School
                $schoolCreate = $parent->InsertNewEnrolleeSchoolHistory($pending_enrollees_id,
                    $school_name, $year_started, $year_ended, $address);

                if($schoolCreate){

                    $defaultRedirect = false;
                    // echo "success create";
                    Alert::success("School history data has been successfully created", "$parent_url");
                    exit();
                }

            }

            // if($doesEnrolleeHasSchoolHistoryMade == false){

            //     $addSuccess = $pending->InsertSchoolHistoryAsPending($pending_enrollees_id, $school_name,
            //         $year_started, $year_ended, $address);

            //     if($addSuccess){

            //         Alert::successAutoRedirect("School history completed.",
            //             $parent_url);
            //         exit();
            //     }
            //     // else{

            //     //     header("Location: $parent_url");
            //     //     exit();
            //     // }
            // }
            // if($doesEnrolleeHasSchoolHistoryMade == true){
            //     ## 

            //     $updateSuccess = $pending->UpdateSchoolHistory(
            //         $student_school_history_id, $pending_enrollees_id,
            //         $school_name, $year_started, $year_ended, $address);

            //     if($updateSuccess){

            //         Alert::successAutoRedirect("School history updated.",
            //             $parent_url);
            //         exit();
            //     }else{
            //         header("Location: $parent_url");
            //         exit();
            //     }
            // }

        }
        else{
            $defaultRedirect = false;
        }

        if($defaultRedirect == true){

            header("Location: $parent_url");
            exit(); 
        }
    }

?>


<div class="content">
    <nav>
        <a href="<?php echo $logout_url;?>">
            <i class="fas fa-sign-out-alt"></i>
            <h3>Logout</h3>
        </a>
    </nav>
    <main>
        <div class="floating noBorder">

            

            <header>
                <div class="title">
                    <h2 style="color: var(--titleTheme)">New enrollment form</h2>
                    <small>SY <?php echo $current_term; ?> &nbsp; <?php echo $current_semester; ?> Semester </small>
                </div>
            </header>

            <div class="progress">
                <span class="dot active"><p>Preferred Course/Strand</p></span>
                <span class="line active"></span>
                <span class="dot active"> <p>Personal Information</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Validate Details</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Finished</p></span>
            </div>

            <form method="POST">
                <main>
                    <header>
                        <div class="title">
                            <h4 style="font-weight: bold;">Previous School Information</h4>
                        </div>
                    </header>
                    <br>
                    <div class="row">
                        <span>
                            <?php
                                Helper::EchoErrorField(
                                Constants::$schoolRequired,
                                Constants::$invalidSchoolCharacters,
                                Constants::$schoolIsTooShort,
                                Constants::$schoolIsTooLong
                                );
                            ?>
                            <label for="school_name">School Name <span class="red">*</span></label>
                            <div>
                                <input required type="text" id="school_name" name="school_name" class="form-control" 
                                value="<?php
                                        echo Helper::DisplayText('school_name', $school_name);
                                    ?>">
                            </div>
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <?php
                                Helper::EchoErrorField(
                                Constants::$addressRequired,
                                Constants::$invalidAddressCharacters,
                                Constants::$addressIsTooShort,
                                Constants::$addressIsTooLong
                                );
                            ?>
                            <label for="address">Address <span class="red">*</span></label>
                            <div>
                                <input required type="text" id="address" name="address"
                                class="form-control" placeholder="House No Street Name Barangay City/Municipality" value="<?php
                                    echo Helper::DisplayText('address', $address);
                                ?>">
                            </div>
                        </span>
                    </div>

                    <div class="row">
                        <span>
                            <label for="year_started">Admission Year <span class="red">*</span></label>
                            <div>
                                <!-- <input required type="date" id="year_started" name="year_started"
                                    class="form-control" value="<?php
                                    echo Helper::DisplayText('year_started', $year_started);
                                ?>"> -->
                                <input maxlength="4" required type="text" id="year_started" name="year_started"
                                    class="form-control" placeholder="e.g. 2020" value="<?php
                                    echo Helper::DisplayText('year_started', $year_started);
                                ?>">
                            </div>
                        </span>

                        <span>
                            
                            <label for="year_ended">Graduation Year <span class="red">*</span></label>
                            <div>
                                <!-- <input required type="date" id="year_ended" name="year_ended" 
                                class="form-control" value="<?php
                                    echo Helper::DisplayText('year_started', $year_ended);
                                ?>"> -->
                                <input  maxlength="4" required type="text" id="year_ended" name="year_ended"
                                    class="form-control" placeholder="e.g. 2022" value="<?php
                                    echo Helper::DisplayText('year_ended', $year_ended);
                                ?>">
                            </div>
                        </span>
                    </div>
                </main>

                <div class="action">
                    <button style="margin-right: 9px;"
                    type="button"
                        class="default large"
                        onclick="window.location.href = 'process.php?new_student=true&step=enrollee_requirements';"
                        >
                    Return
                    </button>
                    <button
                        class="default success large"
                        name="student_school_history_btn_<?php echo $pending_enrollees_id ?>" 
                        type="submit"
                    >
                    Proceed
                    </button>
                </div>

            </form>
        </div>
    </main>
</div>