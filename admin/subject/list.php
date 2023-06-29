
<?php 

    include_once('../../includes/admin_header.php');



    $department_type = "";
    $type = "";
    $back_url = "";

    if(isset($_SESSION['department_type'])){
        $department_type = $_SESSION['department_type'];

        if($department_type == "Senior High School"){
            $type = "shs";
            $back_url = "shs_index.php";


        }else if($department_type == "Tertiary"){
            $type = "tertiary";
            $back_url = "tertiary_index.php";

        }
    }

    $templateUrl = directoryPath . "template.php?type=$type";

?>

<div class="row col-md-12">
    <div class="col-md-10 offset-md-1">
        <h3 class="text-center">Subject Module</h3>
            <a class="mb-2" href="<?php echo $back_url;?>">
                <button class="btn btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </a>    
            <a class="mb-2" href="<?php echo $templateUrl;?>">
                <button class="btn btn btn-success">
                    <i class="fas fa-plus-circle"></i> Add New
                </button>
            </a>    
           
        <table class="table table-striped table-bordered table-hover" 
            style="font-size:14px" cellspacing="0"  > 
            <thead>
                <tr class="text-center"> 
                    <th rowspan="2">Id</th>
                    <th rowspan="2">Code</th>
                    <th rowspan="2">Description</th>
                    <th rowspan="2">Pre Requisite</th>
                    <th rowspan="2">Type</th>  
                    <th rowspan="2">Unit</th>
                    <th rowspan="2">Action</th>
                </tr>	
            </thead> 	
            <tbody>
                <?php 


                    $query = $con->query("SELECT * FROM subject_template
                        -- WHERE course_level=0
                        ");

                    if($department_type == "Senior High School"){
                        $query = $con->query("SELECT * FROM subject_template
                            WHERE program_type=0
                            ");
                    }else if($department_type == "Tertiary"){
                        $query = $con->query("SELECT * FROM subject_template
                            WHERE program_type=1
                            ");
                    }

                    $query->execute();

                    if($query->rowCount() > 0){
                    
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                            $url = "template_edit.php?id=".$row['subject_template_id']."";
                            echo "
                                <tr class='text-center'>
                                    <td>".$row['subject_template_id']."</td>
                                    <td>".$row['subject_code']."</td>
                                    <td>".$row['subject_title']."</td>
                                    <td>".$row['pre_requisite_title']."</td>
                                    <td>".$row['subject_type']."</td>
                                    <td>".$row['unit']."</td>
                                    <td>
                                        <a href='$url'>
                                            <button class='btn btn-sm btn-primary'>
                                                <i class='fas fa-edit'></i>
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
    </div>
</div>