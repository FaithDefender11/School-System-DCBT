<?php 

    include_once('../../includes/admin_elms_header.php');



?>

<div class="content">

    <nav>
        <h3>Department</h3>
        <div class='form-box'>
            <div class='button-box'>
                <a style='color: white;' href='$shs_department_url'>
                    <button  type='button' class='toggle-btn'>
                        SHS
                    </button>
                </a>
                <a style='color: white;' href='tertiary_index.php'>
                    <button  type='button' class='toggle-btn'>
                        Tertiary
                    </button>
                </a>
            </div>
        </div>
    </nav>

    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h4>Program Code List</h4>
                </div>
                
            </header>
            <main>

                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Program Code</th>
                            <th>Section Subject Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                           

                            $query = $con->prepare("SELECT 
                                * FROM subject_program

                                WHERE department_type =:department_type
                            ");

                            $query->bindValue(":department_type", "SHS");
                            $query->execute();

                            if($query->rowCount() > 0){

                            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                                

                                $subject_program_id = $row['subject_program_id'];
                                $subject_code = $row['subject_code'];

                                $sectionHasSameCodeUrl = "section_code_list.php?id=$subject_program_id";
                                
                                echo "
                                    <tr>
                                        <td>$subject_program_id</td>
                                        <td>$subject_code</td>
                                        <td>
                                            <a style='color: inherit;' href='$sectionHasSameCodeUrl'>
                                                10
                                            </a>
                                        </td>
                                        <td>
                                            <a href='code_topics.php?id=$subject_program_id'>
                                                <button class='btn btn-primary'>
                                                    <i class='fas fa-eye'></i>
                                                </button>
                                            </a>
                                        </td>
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

