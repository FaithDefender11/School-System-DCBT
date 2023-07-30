<?php 
    include_once('../../includes/registrar_header.php');

    if (isset($_SESSION['enrollment_form_id'])) {
        unset($_SESSION['enrollment_form_id']);
    }
?>


    <div class="col-md-12 row table-responsive">
        <h4 class="text-center">Registrar Enrollment History List</h4>
        <button onclick="window.location.href = 'manual_create.php' " class="btn btn-success">Enroll Here</button>
        <table class="table table-bordered ">
            <thead>
                <tr>
                <th>Header 1</th>
                <th>Header 2</th>
                <th>Header 3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>Data 1</td>
                <td>Data 2</td>
                <td>Data 3</td>
                </tr>
                <tr>
                <td>Data 4</td>
                <td>Data 5</td>
                <td>Data 6</td>
                </tr>
                <tr>
                <td>Data 7</td>
                <td>Data 8</td>
                <td>Data 9</td>
                </tr>
            </tbody>
        </table>
    </div>

<?php include_once('../../includes/footer.php') ?>
