<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/TaskType.php');
    // include_once('../../assets/images/');


    $back_url = "task_type_index.php";

    if(isset($_POST['create_task_type'])
        && isset($_POST['task_name'])
        && isset($_POST['enabled'])
        ){

        $task_name = $_POST['task_name'];
        $enabled = intval($_POST['enabled']);

        // var_dump($enabled);
        // return;

        $statement = $con->prepare("INSERT INTO task_type (task_name, enabled) 
            VALUES (:task_name, :enabled)");

        $statement->bindParam(":task_name", $task_name);
        $statement->bindParam(":enabled", $enabled, PDO::PARAM_INT);

        if ($statement->execute()) {
            Alert::success("Task type successfully Created", "task_type_index.php");
            exit();
        } else {
            Alert::error("Error Occured", "task_type_index.php");
            exit();
        }
    }
    
    ?>
    <body>
        <div class="content">
            <nav>
                <a href="<?=$back_url?>">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>
            <main>
                <form method="POST">
                    <div class="row">
                        <span>
                            <label for="task_name">Category</label>
                            <div>
                                <input type="text" id="task_name" name="task_name" placeholder="">
                            </div>
                        </span>

                    </div>
                    <div class="row">
                        <span>
                            <label>
                                <input type="radio" name="enabled" value="1">Enabled
                            </label>
                            <label>
                                <input type="radio" name="enabled" value="0">Disabled
                            </label>
                        </span>
                    </div>

                    <div class="action">
                        <button type="submit" class="clean large" name="create_task_type">Save</button>
                    </div>
                </form>
            </main>
        </div>
    </body>
    <?php
?>

