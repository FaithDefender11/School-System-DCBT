<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/TaskType.php');
    // include_once('../../assets/images/');



    if(isset($_GET['id'])){


        $task_type_id = $_GET['id'];

        $taskType = new TaskType($con, $task_type_id);

        $task_name = $taskType->GetTaskName();
        $enabled = $taskType->GetEnabled();

        // var_dump($enabled);
        if(isset($_POST['edit_task_type_' . $task_type_id])
            && isset($_POST['task_name'])
            && isset($_POST['enabled'])
            ){

            $task_name = $_POST['task_name'];
            $enabled = intval($_POST['enabled']);

            // var_dump($enabled);
            // return;

            $statement = $con->prepare("UPDATE task_type SET task_name = :task_name, enabled = :enabled WHERE task_type_id = :task_type_id");
            $statement->bindParam(":task_name", $task_name);
            $statement->bindParam(":enabled", $enabled, PDO::PARAM_INT);
            $statement->bindParam(":task_type_id", $task_type_id, PDO::PARAM_INT);

            if ($statement->execute()) {
                Alert::success("Task type successfully updated", "task_type_index.php");
                exit();
            } else {
                Alert::error("Error Occurred", "task_type_index.php");
                exit();
            }






        }


        $back_url = "task_type_index.php";
    
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
                                <input value="<?= $task_name?>" type="text" id="task_name" name="task_name" placeholder="">
                            </div>
                        </span>

                    </div>
                    <div class="row">
                        <span>
                            <label>
                                <input type="radio" <?= $enabled == 1 ? "checked" : ""; ?> name="enabled" value="1">Enabled
                            </label>
                            <label>
                                <input <?= $enabled == 0 ? "checked" : ""; ?> type="radio" name="enabled" value="0">Disabled
                            </label>
                        </span>
                    </div>

                    <div class="action">
                        <button type="submit" class="clean large" name="edit_task_type_<?= $task_type_id ?>">Save</button>
                    </div>
                </form>
            </main>
        </div>
    </body>
    <?php
?>

