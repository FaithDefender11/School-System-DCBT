
<?php 

    

?>

<hr>
<div class="enrollment_old">
    <main>
        <header>
            <div class="title">
                <h3>Student Information</h3>
                <div class="row">
                    <span style="margin-left: 680px;">
                        <div>
                        <small>LRN</small>
                        <input  value="136-736-050-357" class="text-center form-control" style="width: 250px;" type="text" name="lrn" id="lrn">
                        </div>
                    </span>
                </div>
            </div>
        </header>

        <div class="row">
            <span>
                <label for="name">Name</label>
                <div>
                    <input class="form-control" type="text" name="lastname" id="lastName" placeholder="Last name">
                    <small>Last name</small>
                </div>
                <div>
                    <input class="form-control" type="text" name="firstname" id="firstName" placeholder="First name">
                    <small>First name</small>
                </div>
                <div>
                    <input class="form-control" type="text" name="middle_name" id="middleName" placeholder="Middle name">
                    <small>Middle name</small>
                </div>
                <div>
                    <input class="form-control" type="text" name="suffix" id="suffixName" placeholder="Suffix name">
                    <small>Suffix name</small>
                </div>
            </span>
        </div>
        <div class="row">
            <span>
                <label for="status">Status</label>
                <div>
                    <select id="status" name="civil_status" class="form-control">
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                    </select>
                </div>
            </span>
            <span>
                <label for="citizenship">Citizenship</label>
                <div>
                    <input class="form-control" value="Filipino" style="width: 220px;" type="text" name="nationality" id="nationality">
                </div>
            </span>
            <span>
                <label for="gender">Gender</label>
                <div>
                    <select class="form-control" name="sex" id="sex">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </span>
        </div>
        <div class="row">
            <span>
                <label for="birthdate">Birthdate</label>
                <div>
                    <input type="date" id="birthday" name="birthday" class="form-control">
                </div>
            </span>
            <span>
                <label for="religion">Religion</label>
                <div>
                    <input value="None" type="text" id="religion" name="religion" class="form-control">
                </div>
            </span>
            <span>
                <label for="birthplace">Birthplace</label>
                <div>
                    <input value="Pasig City" type="text" id="birthplace" name="birthplace" class="form-control">
                </div>
            </span>
        </div>

        <div class="row">
            <span>
                <label for="address">Address</label>
                <div>
                    <input style="text-align: start;" type="text" id="address" name="address" class="form-control">
                </div>
            </span>
        </div>

        <div class="row">
            <span>
                <label for="phone">Phone no.</label>
                <div>
                    <input type="tel" id="contact_number" name="contact_number" class="form-control">
                </div>
            </span>
            <span>
                <label for="email">Email</label>
                <div>
                    <input type="email" id="email" name="email" class="form-control">
                </div>
            </span>
        </div>


        <header>
            <div class="title">
                <h3>Father's Information</h3>
            </div>
        </header>

        <div class="row">
            <span>
                <label for="name">Name</label>
                <div>
                    <input type="text" name="father_lastname" class="form-control">
                    <small>Last name</small>
                </div>
                <div>
                    <input type="text" name="father_firstname" class="form-control">
                    <small>First name</small>
                </div>
                <div>
                    <input type="text" name="father_middle" class="form-control">
                    <small>Middle name</small>
                </div>
                <div>
                    <input type="text" name="father_suffix" class="form-control">
                    <small>Father suffix</small>
                </div>
            </span>
        </div>

        <div class="row">
            <span>
                <label for="phone">Phone no.</label>
                <div>
                    <input type="tel" id="father_contact_number" name="father_contact_number" class="form-control">
                </div>
            </span>
            <span>
                <label for="email">Email</label>
                <div>
                    <input type="text" id="father_email" name="father_email" class="form-control">
                </div>
            </span>
            <span>
                <label for="occupation">Occupation</label>
                <div>
                    <input type="text" id="father_occupation" name="father_occupation" class="form-control">
                </div>
            </span>
        </div>


        <header>
            <div class="title">
                <h3>Mother's Information</h3>
            </div>
        </header>

        <div class="row">
            <span>
                <label for="name">Name</label>
                <div>
                    <input type="text" name="mother_lastname" class="form-control">
                    <small>Last name</small>
                </div>
                <div>
                    <input type="text" name="mother_firstname" class="form-control">
                    <small>First name</small>
                </div>
                <div>
                    <input type="text" name="mother_middle" class="form-control">
                    <small>Middle name</small>
                </div>
                <div>
                    <input type="text" name="mother_suffix" class="form-control">
                    <small>Mother suffix</small>
                </div>
            </span>
        </div>
        <div class="row">
            <span>
                <label for="phone">Phone no.</label>
                <div>
                    <input type="tel" id="mother_contact_number" name="mother_contact_number" class="form-control">
                </div>
            </span>
            <span>
                <label for="email">Email</label>
                <div>
                    <input type="text" id="mother_email" name="mother_email" class="form-control">
                </div>
            </span>
            <span>
                <label for="occupation">Occupation</label>
                <div>
                    <input type="text" id="mother_occupation" name="mother_occupation" class="form-control">
                </div>
            </span>
        </div>

        <header>
            <div class="title">
                <h3>Guardian's Information</h3>
            </div>
        </header>

        <div class="row">
            <span>
                <label for="name">Name</label>
                <div>
                    <input type="text" name="parent_lastname" class="form-control">
                    <small>Last name</small>
                </div>
                <div>
                    <input type="text" name="parent_firstname" class="form-control">
                    <small>First name</small>
                </div>

                <div>
                    <input type="text" name="parent_middle_name" class="form-control">
                    <small>Middle name</small>
                </div>

                <div>
                    <input type="text" name="parent_suffix" class="form-control">
                    <small>Guardian suffix</small>
                </div>
            </span>
        </div>
        <div class="row">
            <span>
                <label for="phone">Phone no.</label>
                <div>
                    <input type="tel" id="parent_contact_number" name="parent_contact_number" class="form-control">
                </div>
            </span>
            <span>
                <label for="email">Email</label>
                <div>
                    <input type="text" id="parent_email" name="parent_email" class="form-control">
                </div>
            </span>
            <span>
                <label for="occupation">Occupation</label>
                <div>
                    <input type="text" id="parent_occupation" name="parent_occupation" class="form-control">
                </div>
            </span>

            <span>
                <label for="relationship">Relationship</label>
                <div>
                    <input class="form-control" type="text" name="relationship" id="relationship">
                </div>
            </span>
        </div>
    </main>
</div>



