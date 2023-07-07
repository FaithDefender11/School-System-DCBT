<?php 

    include_once('../../includes/admin_header.php');

    $createUrl = directoryPath . "create.php";
    $templateUrl = directoryPath . "template.php";
    // echo "im in subject enroll";

    $course = "";
    $department_name = "";
    $department_type = "";
    $back_url = "";

    if(isset($_SESSION['department_type'])){

        $department_type = $_SESSION['department_type'];

        if($department_type == "Senior High School"){
            $course = "Strand";
            $department_name = "Senior High School";
            $back_url = "shs_index.php";
        }else{
            $course = "Courses";
            $department_name = "Tertiary";
            $back_url = "tertiary_index.php";
        }
    }
?>

<div class="content">

        <main>
        <div class="floating" id="shs-sy">
            <div style="margin-bottom: 8px;">
                <a href='shs_index.php'>
                    <button class="text-left btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </a>

                    <h4 class="text-center" style="font-weight: 400;">Choose <?php echo $course;?></h4>
            </div>
            <main>
                <table id="template_table" 
                    class="ws-table-all cw3-striped cw3-bordered" style="margin: 0"> 
                    <thead>
                        <tr class="text-center"> 
                            <th rowspan="2">Track</th>
                            <th rowspan="2">Strand</th>
                            <th rowspan="2">Total Unit</th>
                        </tr>		
                    </thead> 	
                    <tbody>
                        <?php 

                            // $department_name = "Senior High School";

                            $query = null;

                            if($department_name == "Senior High School"){
                                $query = $con->prepare("SELECT * FROM program as t1
                                    INNER JOIN department as t2 ON t2.department_id = t1.department_id
                                    WHERE t2.department_name=:department_name
                                    ");

                                $query->bindValue(":department_name", $department_name);
                                $query->execute();

                            }else if($department_name == "Tertiary"){
                                
                                $query = $con->prepare("SELECT * FROM program as t1
                                    INNER JOIN department as t2 ON t2.department_id = t1.department_id
                                    WHERE t2.department_name!=:department_name
                                    ");

                                $query->bindValue(":department_name", "Senior High School");
                                $query->execute();
                            }

                            

                            if($query->rowCount() > 0){
                            
                                $track = "";
                                
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {


                                    $program_id = $row['program_id'];
                                    $track = $row['track'];
                                    $program_name = $row['program_name'];
                                    $acronym = $row['acronym'];

                                    // if($acronym == "HUMMS" ||$acronym == "ABM" || $acronym == "STEM" )
                                    //     $track = "Academic";
                                    
                                    $strand_url = "subject_program_list_view.php?id=$program_id";

                                    echo "
                                        <tr class='text-center'>
                                            <td>$track</td>
                                            <td>
                                                <a href='$strand_url'>
                                                    $acronym
                                                </a>
                                            </td>
                                            <td>10</td>
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

    <div style="display: none;" class="col-md-10 offset-md-1">
            <h3 class="text-center">Choose <?php echo $course;?></h3>
            <a class="mb-2" href="<?php echo $back_url;?>">
                <button class="btn btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </a> 
            <table  class="table table-striped table-bordered table-hover " 
                style="font-size:13px" cellspacing="0"  > 
                <thead>
                    <tr class="text-center"> 
                        <th rowspan="2">Track</th>
                        <th rowspan="2">Strand</th>
                        <th rowspan="2">Total Unit</th>
                    </tr>	
                </thead> 	
                <tbody>
                    <?php 

                        // $department_name = "Senior High School";

                        $query = null;

                        if($department_name == "Senior High School"){
                            $query = $con->prepare("SELECT * FROM program as t1
                                INNER JOIN department as t2 ON t2.department_id = t1.department_id
                                WHERE t2.department_name=:department_name
                                ");

                            $query->bindValue(":department_name", $department_name);
                            $query->execute();

                        }else if($department_name == "Tertiary"){
                            
                            $query = $con->prepare("SELECT * FROM program as t1
                                INNER JOIN department as t2 ON t2.department_id = t1.department_id
                                WHERE t2.department_name!=:department_name
                                ");

                            $query->bindValue(":department_name", "Senior High School");
                            $query->execute();
                        }

                        

                        if($query->rowCount() > 0){
                        
                            $track = "";
                            
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {


                                $program_id = $row['program_id'];
                                $track = $row['track'];
                                $program_name = $row['program_name'];
                                $acronym = $row['acronym'];

                                // if($acronym == "HUMMS" ||$acronym == "ABM" || $acronym == "STEM" )
                                //     $track = "Academic";
                                
                                $strand_url = "subject_program_list_view.php?id=$program_id";

                                echo "
                                    <tr class='text-center'>
                                        <td>$track</td>
                                        <td>
                                            <a href='$strand_url'>
                                                $acronym
                                            </a>
                                        </td>
                                        <td>10</td>
                                    </tr>
                                ";
                            }
                        }
                    ?>
                </tbody>
            </table>
    </div>

</div>