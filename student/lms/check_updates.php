

<?php 

    require_once("../../includes/config.php");


    // $stmt = $con->prepare("SELECT COUNT(*) as announcement_count FROM announcement");
    // $stmt->execute();
    // $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // $announcementCount = $result['announcement_count'];

    $stmt = $con->prepare("SELECT COUNT(*) as announcement_count FROM announcement");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $announcementCount = $result['announcement_count'];

    if(isset($_GET['last_count'])){

        $clientLastCount = isset($_GET['last_count']) ? $_GET['last_count'] : null;

        if ($clientLastCount === null || $clientLastCount != $announcementCount) {
            echo 'update_available';
        
        } else {
            echo 'no_update';
        }
        
    }

 

?>
