<?php 


    if(isset($_POST['edit_teacher_btn_' . $teacher_id])){

        echo "qwe";


    }
?>

<div class="tabs">
        <?php
            echo "
                <button class='tab' 
                    style='background-color: var(--mainContentBG)'
                    onclick=\"window.location.href = 'info.php?details=show&id=$teacher_id';\">
                    <i class='bi bi-clipboard-check'></i>
                    Details
                </button>
            ";

            echo "
                <button class='tab' 
                    id='shsPayment'
                    style='background-color: var(--them); color: white'
                    onclick=\"window.location.href = 'info.php?subject_load=show&id=$teacher_id';\">
                    <i class='bi bi-book'></i>
                    Subject Load
                </button>
            ";
        ?>
    </div>


    <main>
        <div class="floating">
            <header class="mt-4">
                <div class="title">
                <h4>Teacher Information</h4>
                </div>
            </header>

            <form method="POST">

                <main>
                    <div class="row">
                      <span>
                          <label for="name">Name</label>
                          <div>
                          <input type="text" name="lastname" id="lastname" value="<?php echo $firstname;?>" class="form-control" />
                          <small>Last name</small>
                          </div>
                          <div>
                          <input type="text" name="firstname" id="firstname" value="<?php echo $firstname;?>" class="form-control" />
                          <small>First name</small>
                          </div>
                          <div>
                          <input type="text" name="middle_name" id="middle_name" value="<?php echo $middle_name;?>" class="form-control" />
                          <small>Middle name</small>
                          </div>
                          <div>
                            <input type="text" name="suffix" id="suffix" value="<?php echo $suffix;?>" class="form-control" />
                            <small>Suffix name</small>
                          </div>
                      </span>
                      </div>

                      <div class="row">
                      <span>
                          <label for="status">Status</label>
                          <div>
                          <select name="civil_status" id="civil_status" class="form-control">
                              <option value="Single"<?php echo ($civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                              <option value="Married"<?php echo ($civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                              <option value="Divorced"<?php echo ($civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                              <option value="Widowed"<?php echo ($civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                          </select>
                          </div>
                      </span>

                      <span>
                          <label for="citizenship">Citizenship</label>
                          <div>
                          <input type="text" name="nationality" id="nationality" value="<?php echo $nationality;?>" class="form-control" />
                          </div>
                      </span>

                      <span>
                          <label for="sex">Gender</label>
                          <div>
                          <select name="sex" id="sex" class="form-control">
                              <option value="Male"<?php echo ($sex == "Male") ? " selected" : ""; ?>>Male</option>
                              <option value="Female"<?php echo ($sex == "Female") ? " selected" : ""; ?>>Female</option>
                          </select>
                          </div>
                      </span>
                      </div>

                      <div class="row">
                      <span>
                          <label for="birthdate">Birthdate</label>
                          <div>
                          <input type="date" name="birthday" id="birthday" value="<?php echo $birthday;?>" class="form-control" />
                          </div>
                      </span>
                      <span>
                          <label for="birthplace">Birthplace</label>
                          <div>
                          <input type="text" name="birthplace" id="birthplace" value="<?php echo $birthplace;?>" class="form-control" />
                          </div>
                      </span>
                      <span>
                          <label for="religion">Religion</label>
                          <div>
                          <input type="text" name="religion" id="religion" value="<?php echo $religion;?>" class="form-control" />
                          </div>
                      </span>
                      </div>

                      <div class="row">
                      <span>
                          <label for="address">Address</label>
                          <div>
                          <input type="text" name="address" id="address" value="<?php echo $address;?>" class="form-control" />
                          </div>
                      </span>
                      </div>

                      <div class="row">
                      <span>
                          <label for="phoneNo">Phone no.</label>
                          <div>
                          <input type="text" name="contact_number" id="contact_number" value="<?php echo $contact_number;?>" class="form-control" />
                          </div>
                      </span>
                      <span>
                          <label for="email">Email</label>
                          <div>
                          <input type="email" name="email" id="email" value="<?php echo $email;?>" class="form-control" />
                          </div>
                      </span>
                    </div>
                </main>

                <div class="action modal-footer">
                    <button type="button"
                        onclick="window.location.href = 'edit.php?id=<?php echo $teacher_id?>' "
                        name="edit_teacher_btn_<?php echo $teacher_id;?>"
                        class="default large info" >
                        Edit
                    </button>
                </div>
        </form>

