    
    <hr>
    <div class="enrollment_new">

        <header>
            <div class="title">
                <h3>Student Type</h3>
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
                        <select style="width: 85%;" class='form-control' name="program_id" id="program_id">
                        </select>
                    </div>
                </div>

                <div class="form-element courseStrand">
                    <label>Choose Section</label>
                    <div>
                        <select style="width: 85%;" class='form-control' name="course_id" id="course_id">
                        </select>
                    </div>
                </div>
            </span>
        </div>
    </div>
