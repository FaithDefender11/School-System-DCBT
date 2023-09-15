

<?php 

    function getSelectValue($name, $optionValue) {
        if (isset($_POST[$name]) && $_POST[$name] === $optionValue) {
            echo 'selected';
        }
    }

?>

<div class="enrollment_new">
    <main >
        <header>
            <div class="title">
                <h4 style="font-weight: bold;">Student Information</h4>
                <div class="row">
                    <span style="margin-left: 500px;">
                        <small style="font-weight: bold;">LRN</small>
                        <?php 
                            echo Helper::getError(Constants::$LRNRequired);
                            echo Helper::getError(Constants::$LRNUnique);
                        ?>
                        <input class="form-control" style="width: 250px;"
                            type="text" name="lrn" required id="lrn"    
                            value="<?php  
                            echo Helper::DisplayText('lrn', $lrn);
                            ?>">
                    </span>
                </div>
            </div>
        </header>

        <div class="row">
            <span>
                <label for="name">Name</label>
                <div>
                    <?php 
                        Helper::EchoErrorField(
                            Constants::$lastNameRequired,
                            Constants::$invalidLastNameCharacters,
                            Constants::$lastNameIsTooShort, Constants::$lastNameIsTooLong
                        );
                    ?>
                    <input class="read_only form-control" type="text"
                        name="lastname" id="lastName"  placeholder="Last name" 
                        value="<?php  
                            echo Helper::DisplayText('lastname', $lastname);
                        ?>">
                    <small>Last name</small>
                </div>
            <div>
                <?php 
                    Helper::EchoErrorField(Constants::$firstNameRequired, Constants::$invalidFirstNameCharacters,
                        Constants::$firstNameIsTooShort, Constants::$firstNameIsTooLong);
                ?>
                <input class="read_only form-control"
                    type="text" name="firstname" 
                    placeholder="First name"
                    id="firstName" 
                    value="<?php
                        echo Helper::DisplayText('firstname', $firstname);
                    ?>"
                    >
                <small>First name</small>
            </div>

            <div>
                <?php
                    Helper::EchoErrorField(
                        Constants::$middleNameRequired,
                        Constants::$invalidMiddleNameCharacters,
                        Constants::$middleNameIsTooShort,
                        Constants::$middleNameIsTooLong);
                ?>
                <input class="read_only form-control" type="text" name="middle_name" id="middleName" 
                    placeholder="Middle name"
                    value="<?php
                        echo Helper::DisplayText('middle_name', $middle_name);
                    ?>">
                <small>Middle name</small>
            </div>
            <div>
                <?php
                    echo Helper::getError(Constants::$invalidSuffixNameCharacters);
                ?>
                <input maxlength="3" class="form-control" 
                    type="text" name="suffix" id="suffixName" placeholder="e.g. Jr, Sr, II"
                    value="<?php 
                    echo Helper::DisplayText('suffix', $suffix);
                ?>">
                <small>Suffix name</small>
            </div>
            </span>
        </div>

        <div class="row">
            <span>
                <?php
                    echo Helper::getError(Constants::$civilStatusRequired);
                    echo Helper::getError(Constants::$invalidCivilStatusCharacters);
                ?>
                <label for="civil_status">Status</label>
                <div>
                    <!-- <select class="form-control" id="civil_status" name="civil_status" class="form-control">
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                    </select> -->

                    <select class="form-control" name="civil_status" id="civil_status">
                        <option value="Single" <?php getSelectValue('civil_status', 'Single'); ?>>Single</option>
                        <option value="Married" <?php getSelectValue('civil_status', 'Married'); ?>>Married</option>
                    </select>
                </div>
            </span>

            <span>
                <!-- <p style="color:orange; font-size: 11px;">Input error</p> -->
                <?php
                    echo Helper::getError(Constants::$nationalityRequired);
                    echo Helper::getError(Constants::$invalidNationalityCharacters);
                ?>
                
                <label for="citizenship">Citizenship</label>
                <div>
                    <input class="form-control" style="width: 220px;" 
                        type="text" name="nationality" 
                        id="nationality"
                        value="<?php 
                            echo Helper::DisplayText('nationality', $nationality);
                        ?>">
                </div>
            </span>
            <span>
                <?php
                    echo Helper::getError(Constants::$genderRequired);
                    echo Helper::getError(Constants::$invalidGenderCharacters);
                ?>
                <label for="sex">Gender</label>
                <div>
                    <select class="form-control" name="sex" id="sex">
                        <option value="Male" <?php getSelectValue('sex', 'Male'); ?>>Male</option>
                        <option value="Female" <?php getSelectValue('sex', 'Female'); ?>>Female</option>
                    </select>
                </div>
            </span>
        </div>

        <div class="row">
            <span>
            <label for="birthdate">Birthdate</label>
                <div>
                    <input type="date" id="birthday"
                        name="birthday" class="form-control" required
                        value="<?php 
                            echo Helper::DisplayText('birthday', $birthday);
                        ?>">
                </div>
            </span>
            <span>
                <?php
                    echo Helper::getError(Constants::$religionRequired);
                    echo Helper::getError(Constants::$invalidReligionCharacters);
                ?>

                <label for="religion">Religion</label>
                <div>
                    <!-- <select class="form-control" name="religion" id="religion">
                        <option value="Catholic">Catholic</option>
                        <option value="Christian">Christian</option>
                        <option value="Other">Other</option>
                    </select> -->
                    <input type="text" id="religion" placeholder="e.g. Catholic, Christian, Other" name="religion" class="form-control"
                        value="<?php
                            echo Helper::DisplayText('religion', $religion);
                        ?>">
                </div>
            </span>
            <span>
                <?php
                    Helper::EchoErrorField(
                        Constants::$birthPlaceRequired,
                        Constants::$invalidBirthPlaceCharacters,
                        Constants::$birthPlaceIsTooShort,
                        Constants::$birthPlaceIsTooLong
                    );
                ?>
                <label for="birthplace">Birthplace</label>
                <div>
                    <input type="text" id="birthplace" name="birthplace" 
                        class="form-control"  
                        value="<?php 
                            echo Helper::DisplayText('birthplace', $birthplace);
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
                        Constants::$addressIsTooLong);
                    ?>
                <label for="address">Address</label>
                <div>
                    <input style="text-align: start;" type="text" 
                    id="address" name="address" 
                    class="form-control" 
                    value="<?php
                        echo Helper::DisplayText('address', $address);
                    ?>">

                </div>
            </span>
        </div>

        <div class="row">
            <span>
                <?php
                    echo Helper::getError(Constants::$ContactNumberRequired);
                    echo Helper::getError(Constants::$invalidContactNumberCharacters);
                    echo Helper::getError(Constants::$invalidContactNumber2Characters);
                ?>
                <label for="phone">Contact no.</label>
                <div>
                    <input type="tel" id="contact_number"
                        name="contact_number" class="form-control"
                        value="<?php
                            echo Helper::DisplayText('contact_number', $contact_number);
                        ?>">
                </div>
            </span>
            <span>
               
                <label for="email">Email</label>
                <?php 
                    echo Helper::getError(Constants::$EmailRequired);
                    echo Helper::getError(Constants::$EmailUnique);
                    echo Helper::getError(Constants::$invalidEmailCharacters);
                ?>
                <div>
                    <input type="email" id="email" name="email" 
                    class="read_only form-control" 
                    value="<?php 
                        echo Helper::DisplayText('email', $email);
                    ?>">
                </div>
            </span>
        </div>

        <hr>
        <!-- FATHER -->
        <div id="father_info">
            <header>
                <div class="title">
                    <h4>Father's Information</h4>
                </div>
            </header>

            <div class="row">
                <span>
                    <label for="name">Name</label>
                    <div>
                        <?php 
                            Helper::EchoErrorField(
                                Constants::$fatherLastNameRequired,
                                Constants::$invalidFatherLastNameCharacters,
                                Constants::$fatherLastNameIsTooShort,
                                Constants::$fatherLastNameIsTooLong
                            );
                        ?>
                        <input type="text" name="father_lastname" class="form-control" 
                            value="<?php 
                                    echo Helper::DisplayText('father_lastname', $father_lastname);  
                                ?>">
                        <small>Last name</small>
                    </div>
                    <div>
                        <?php 
                            Helper::EchoErrorField(
                                Constants::$fatherFirstNameRequired,
                                Constants::$invalidFatherFirstNameCharacters,
                                Constants::$fatherFirstNameIsTooShort,
                                Constants::$fatherFirstNameIsTooLong
                            );
                        ?>
                        <input type="text" name="father_firstname" class="form-control"
                            value="<?php 
                                echo Helper::DisplayText('father_firstname', $father_firstname);  
                            ?>">
                        <small>First name</small>
                    </div>
                    <div>
                        <?php 
                            Helper::EchoErrorField(
                                Constants::$fatherMiddleNameRequired,
                                Constants::$invalidFatherMiddleNameCharacters,
                                Constants::$fatherMiddleNameIsTooShort,
                                Constants::$fatherMiddleNameIsTooLong
                            );
                        ?>
                        <input type="text" name="father_middle" class="form-control" 
                            value="<?php 
                                echo Helper::DisplayText('father_middle', $father_middle);  
                            ?>">
                        <small>Middle name</small>
                    </div>
                    <div>
                        <?php
                            echo Helper::getError(Constants::$invalidFatherSuffixNameCharacters);
                        ?>
                        <input type="text" placeholder="e.g. Jr, Sr, II" name="father_suffix" maxlength="3" class="form-control"
                            value="<?php 
                                echo Helper::DisplayText('father_suffix', $father_suffix);
                            ?>">
                        <small>Suffix name</small>
                    </div>
                </span>
            </div>

            <div class="row">
                <span>
                    <?php
                        echo Helper::getError(Constants::$fatherContactNumberRequired);
                        echo Helper::getError(Constants::$invalidFatherContactNumberCharacters);
                        echo Helper::getError(Constants::$invalidFatherContactNumber2Characters);
                    ?>
                    <label for="phone">Phone no.</label>
                    <div>
                        <input type="tel" id="father_contact_number" name="father_contact_number" class="form-control" 
                            value="<?php 
                                echo Helper::DisplayText('father_contact_number', $father_contact_number);;
                            ?>">
                    </div>
                </span>
                <span>
                    <!-- <?php
                        echo Helper::getError(Constants::$fatherEmailRequired);
                        echo Helper::getError(Constants::$invalidFatherEmailCharacters);
                    ?>
                    <label for="email">Email</label>
                    <div>
                        <input type="text" id="father_email" name="father_email" class="form-control"
                            value="<?php 
                                echo Helper::DisplayText('father_email', "");;
                            ?>">
                    </div> -->
                </span>
                <span>
                    <?php 
                        echo Helper::getError(Constants::$fatherOccupationRequired);
                        echo Helper::getError(Constants::$invalidFatherOccupationCharacters);
                        echo Helper::getError(Constants::$fatherOccupationIsTooShort);
                        echo Helper::getError(Constants::$fatherOccupationIsTooLong);
                    ?>
                    <label for="occupation">Occupation</label>
                    <div>
                        <input type="text" id="father_occupation" name="father_occupation"
                            class="form-control" 
                            value="<?php 
                                echo Helper::DisplayText('father_occupation', $father_occupation);
                            ?>">
                    </div>
                </span>
            </div>
        </div>

        <hr>
        <!-- MOTHER -->
        <div id="mother_info">
            <header>
                <div class="title">
                <h4>Mother's Information</h4>
                </div>
            </header>

            <div class="row">
                <span>
                    
                    <label for="name">Name</label>
                    <div>
                        <?php 
                            echo Helper::getError(Constants::$motherLastNameRequired);
                            echo Helper::getError(Constants::$invalidMotherLastNameCharacters);
                            echo Helper::getError(Constants::$motherLastNameIsTooShort);
                            echo Helper::getError(Constants::$motherLastNameIsTooLong);
                        ?>

                        <input type="text" name="mother_lastname" class="form-control"\
                            value="<?php 
                                echo Helper::DisplayText('mother_lastname', $mother_lastname); 
                            ?>">

                        <small>Last name</small>
                    </div>

                    <div>
                        <?php 
                            echo Helper::getError(Constants::$motherFirstNameRequired);
                            echo Helper::getError(Constants::$invalidMotherFirstNameCharacters);
                            echo Helper::getError(Constants::$motherFirstNameIsTooShort);
                            echo Helper::getError(Constants::$motherFirstNameIsTooLong);
                        ?>
                        <input type="text" name="mother_firstname" class="form-control"
                            value="<?php 
                                echo Helper::DisplayText('mother_firstname', $mother_firstname); 
                            ?>">
                        <small>First name</small>
                    </div>

                    <div>
                        <?php 
                            echo Helper::getError(Constants::$motherMiddleNameRequired);
                            echo Helper::getError(Constants::$invalidMotherMiddleNameCharacters);
                            echo Helper::getError(Constants::$motherMiddleNameIsTooShort);
                            echo Helper::getError(Constants::$motherMiddleNameIsTooLong);
                        ?>
                        <input type="text" name="mother_middle" class="form-control" 
                            value="<?php 
                                echo Helper::DisplayText('mother_middle', $mother_middle); 
                            ?>">
                        
                        <small>Middle name</small>
                    </div>
                    <!-- <div>
                        <input type="text" name="mother_suffix" class="form-control" maxlength="3" value="<?php echo $mother_suffix; ?>">
                        
                        <small>Suffix name</small>
                    </div> -->
                </span>
            </div>
            <div class="row">
                <span>
                    <?php 
                        echo Helper::getError(Constants::$motherContactNumberRequired);
                        echo Helper::getError(Constants::$invalidMotherContactNumberCharacters);
                        echo Helper::getError(Constants::$invalidMotherContactNumber2Characters);
                    ?>
                    <label for="phone">Phone no.</label>
                    <div>
                        <input type="tel" id="mother_contact_number" name="mother_contact_number" class="form-control" 
                            value="<?php
                                echo Helper::DisplayText('mother_contact_number', $mother_contact_number); 
                            ?>">
                    </div>
                </span>
                <span>

                    <!-- <?php 
                        echo Helper::getError(Constants::$motherEmailRequired);
                        echo Helper::getError(Constants::$invalidMotherEmailCharacters);
                    ?>
                    <label for="email">Email</label>
                    <div>
                        <input type="text" id="mother_email" name="mother_email" class="form-control" 
                            value="<?php 
                                echo Helper::DisplayText('mother_email', ""); 
                            ?>">
                    </div> -->
                </span>

                <span>
                    <?php 
                        echo Helper::getError(Constants::$motherOccupationRequired);
                        echo Helper::getError(Constants::$invalidMotherOccupationCharacters);
                        echo Helper::getError(Constants::$motherOccupationIsTooShort);
                        echo Helper::getError(Constants::$motherOccupationIsTooLong);
                    ?>
                    <label for="occupation">Occupation</label>
                    <div>
                        <input type="text" id="mother_occupation" name="mother_occupation" class="form-control"
                            value="<?php 
                                echo Helper::DisplayText('mother_occupation', $mother_occupation); 
                            ?>">
                    </div>
                </span>
            </div>
        </div>

        <hr>

        <div class="guardian_info">
            <header>
                <div class="title">
                <h3>Guardian's Information</h3>
                </div>
            </header>

            <div class="row">
                <span>
                <label for="name">Name</label>
                <div>
                    <?php 
                        echo Helper::getError(Constants::$guardianLastNameRequired);
                        echo Helper::getError(Constants::$invalidGuardianLastNameCharacters);
                        echo Helper::getError(Constants::$guardianLastNameIsTooShort);
                        echo Helper::getError(Constants::$guardianLastNameIsTooLong);
                    ?>
                    <input type="text" name="parent_lastname" class="form-control"
                        value="<?php 
                            echo Helper::DisplayText('parent_lastname', $parent_lastname); 
                        ?>">
                    <small>Last name *</small>
                </div>

                <div>
                    <?php 
                        echo Helper::getError(Constants::$guardianFirstNameRequired);
                        echo Helper::getError(Constants::$invalidGuardianFirstNameCharacters);
                        echo Helper::getError(Constants::$guardianFirstNameIsTooShort);
                        echo Helper::getError(Constants::$guardianFirstNameIsTooLong);
                    ?>
                    <input type="text" name="parent_firstname" class="form-control" 
                        value="<?php 
                            echo Helper::DisplayText('parent_firstname',  $parent_firstname); 
                        ?>">

                    <small>First name *</small>
                </div>
                
                <div>
                    <?php 
                        echo Helper::getError(Constants::$guardianMiddleNameRequired);
                        echo Helper::getError(Constants::$invalidGuardianMiddleNameCharacters);
                        echo Helper::getError(Constants::$guardianMiddleNameIsTooShort);
                        echo Helper::getError(Constants::$guardianMiddleNameIsTooLong);
                    ?>
                    <input type="text" name="parent_middle_name" class="form-control" 
                        value="<?php 
                            echo Helper::DisplayText('parent_middle_name', $parent_middle_name); 
                        ?>">
                    <small>Middle name *</small>
                </div>
                <div>
                    <?php
                        echo Helper::getError(Constants::$invalidGuardianSuffixNameCharacters);
                    ?>
                    <input type="text" name="parent_suffix" class="form-control"
                        maxlength="3" 
                        value="<?php 
                            echo Helper::DisplayText('parent_suffix', $parent_suffix); 
                        ?>">
                    <small>Suffix name</small>
                </div>
                </span>
            </div>
            <div class="row">
                <span>
                    <label for="phone">Phone no *</label>
                    <?php
                        echo Helper::getError(Constants::$guardianContactNumberRequired);
                        echo Helper::getError(Constants::$invalidGuardianContactNumberCharacters);
                        echo Helper::getError(Constants::$invalidGuardianContactNumber2Characters);
                    ?>
                    
                    <div>
                        <input type="tel" id="parent_contact_number" name="parent_contact_number" class="form-control"
                            value="<?php 
                                echo Helper::DisplayText('parent_contact_number', $parent_contact_number); 
                            ?>">
                    </div>

                    
                    <label for="occupation">Occupation</label>

                        <?php 
                            echo Helper::getError(Constants::$guardianOccupationRequired);
                            echo Helper::getError(Constants::$invalidGuardianOccupationCharacters);
                            echo Helper::getError(Constants::$guardianOccupationIsTooShort);
                            echo Helper::getError(Constants::$guardianOccupationIsTooLong);
                        ?>
                    <div>
                        <input type="text" id="parent_occupation" name="parent_occupation" class="form-control"
                            value="<?php 
                                echo Helper::DisplayText('parent_occupation', $parent_occupation); 

                            ?>">
                    </div>
                    <label for="relationship">Relationship *</label>
                    <div>
                        <?php 
                            echo Helper::EchoErrorField(
                                Constants::$guardianRelationshipRequired,
                                Constants::$invalidGuardianRelationshipCharacters,
                                Constants::$guardianRelationshipIsTooShort,
                                Constants::$guardianRelationshipIsTooLong
                            );
                        ?>
                        <input class="form-control"
                            type="text"  name="parent_relationship"
                            id="parent_relationship"
                           
                            value="<?php 
                                echo Helper::DisplayText('parent_relationship', $parent_relationship); 

                            ?>">
                    </div>
                </span>
            </div>
        </div>

    </main>
</div>


