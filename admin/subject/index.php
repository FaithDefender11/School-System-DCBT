<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/SchoolYear.php');

    $teacher = new Teacher($con);
    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    // Define the available options
    $recordsPerPageOptions = [5, 10, 15]; 

    // Get the selected records per page from the URL parameter 'per_page'
    // Set default value to the first option

    $selectedRecordsPerPage = isset($_GET['per_page']) 
        ? $_GET['per_page'] : $recordsPerPageOptions[0];

    // Generate the dropdown options
    $recordsPerPageDropdown = '<select class="form-control" 
        name="per_page" onchange="this.form.submit()">';

    foreach ($recordsPerPageOptions as $option) {

        // $recordsPerPageDropdown .= '<option value="' . $option . '"';

        $recordsPerPageDropdown .= "<option value=$option";

        if ($option == $selectedRecordsPerPage) {
            $recordsPerPageDropdown .= ' selected';
        }

        $recordsPerPageDropdown .= ">" . $option . " per page</option>";
    }

    $recordsPerPageDropdown .= '</select>';

    // Set the number of results per page
    $resultsPerPage = $selectedRecordsPerPage;

    // Get the current page number from the URL parameter 'page'
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    // Calculate the offset
    $offset = ($currentPage - 1) * $resultsPerPage;

    // Query to retrieve the data with pagination
    $sql = $con->prepare(" SELECT *  FROM subject
        ORDER BY subject_id DESC
        LIMIT :offset, :resultsPerPage
    ");
    $sql->bindValue(":offset", $offset, PDO::PARAM_INT);
    $sql->bindValue(":resultsPerPage", $resultsPerPage, PDO::PARAM_INT);
    $sql->execute();


    ?>
        <div class="row col-md-12">

            <a href="index.php">
                <button class="btn btn-primary btn-sm">
                    Reload
                </button>
            </a>

            <!-- Display the records per page dropdown -->
            <div class="text-right mb-3">
                <form method="GET" class="form-inline">
                    <label for="per_page">Records per page:</label>
                    <?php echo $recordsPerPageDropdown; ?>
                </form>
            </div>

            <table class="table table-striped table-bordered table-hover" style="font-size:13px" cellspacing="0">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2">Id</th>
                        <th rowspan="2">Title</th>
                        <th rowspan="2">Code</th>
                        <th rowspan="2">Level</th>
                        <th rowspan="2">Section</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = $con->prepare("SELECT 
                            t1.*, 
                            t2.program_section 
                        
                            FROM subject as t1

                            INNER JOIN course as t2 ON t2.course_id = t1.course_id

                            ORDER BY t1.course_level ASC
                            LIMIT :offset,
                            :resultsPerPage
                        ");

                        $sql->bindValue(":offset", $offset, PDO::PARAM_INT);
                        $sql->bindValue(":resultsPerPage", $resultsPerPage, PDO::PARAM_INT);

                        $sql->execute();

                        if ($sql->rowCount() > 0) {
                            
                            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                                $subject_id = $row['subject_id'];
                                $subject_title = $row['subject_title'];
                                $subject_code = $row['subject_code'];
                                $course_level = $row['course_level'];
                                $program_section = $row['program_section'];

                                // echo $program_section;

                                echo "
                                    <tr class='text-center'>
                                        <td>$subject_id</td>
                                        <td>$subject_title</td>
                                        <td>
                                            <a style='color: white;' href='edit.php?id=$subject_id'>
                                                $subject_code
                                            </a>
                                        </td>
                                        <td>$course_level</td>
                                        <td>$program_section</td>
                                    </tr>
                                ";
                            }
                        }
                    ?>
                </tbody>
            </table>

            <?php

                $totalResults = $con->query("SELECT COUNT(*) FROM subject")->fetchColumn();

                $totalPages = ceil($totalResults / $resultsPerPage);

                $pagination = '<ul class="pagination">';

                for ($i = 1; $i <= $totalPages; $i++) {
                    
                    $pagination .= '<li class="page-item';
                    if ($i == $currentPage) {
                        $pagination .= ' active';
                    }
                    $pagination .= '">
                            <a class="page-link" href="?page=' . $i . '&per_page=' . $selectedRecordsPerPage . '">' . $i . '</a>
                        </li>';
                }

                $pagination .= '</ul>';

                // Calculate the range of displayed entries
                // Study how the formula
                $startEntry = ($currentPage - 1) * $resultsPerPage + 1;
                $endEntry = min($startEntry + $resultsPerPage - 1, $totalResults);

                // Generate the "Showing X to Y of Z entries" message
                $showingEntries = "Showing $startEntry to $endEntry of $totalResults entries";
            ?>

            <!-- Display the "Showing X to Y of Z entries" message -->
            <div class="text-center"><?php echo $showingEntries; ?></div>

            <!-- Display the pagination links -->
            <div class="text-center"><?php echo $pagination; ?></div>
        </div>

    <?php
    
?>