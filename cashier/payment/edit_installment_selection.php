
<?php 

    include_once('../../includes/cashier_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/StudentSubjectGrade.php');
    include_once('../../includes/classes/StudentRequirement.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/EnrollmentPayment.php');
    include_once('../../includes/classes/User.php');
    include_once('../../includes/classes/EnrollmentAudit.php');

    $department = new Department($con, null);

    $school_year = new SchoolYear($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    

    $back_url = "";

    if(isset($_GET['id'])){

        $enrollment_id = $_GET['id'];

        $enrollment = new Enrollment($con);
        $enrollmentPayment = new EnrollmentPayment($con);


        $enrollmentToPay = (float) $enrollment->GetEnrollmentTotalToPayment($enrollment_id);
        $installmentOption = $enrollmentPayment->GetInstallmentOption($enrollment_id);

        // var_dump($installmentOption);

        $DOWN_PAYMENT_PERCENTAGE = 0.4;

        // var_dump($installmentOption);

        
        $downPayment = ($enrollmentToPay * $DOWN_PAYMENT_PERCENTAGE);

        $excessPaymentValue = $enrollmentToPay - $downPayment;

        $back_url = "payment_summary2.php?id=$enrollment_id&enrolled_subject=show&clicked=true";

        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['attach_schedule_payment_btn_' . $enrollment_id])
            && isset($_POST['installment_id'])){
            

            $option = $_POST['installment_id'];

            $optionCount = $enrollmentPayment->GetSelectedPaymentOptionsCount($option);

            $wasSuccessEditPayment = $enrollmentPayment->EditEnrollmentPaymentInstallmentOptions(
                $option, $enrollment_id, $downPayment, $cashierUserId);

            if($wasSuccessEditPayment == true){

                # Update form.
                $wasSuccessUpdate = $enrollment->UpdateEnrollmentInstallmentCount(
                    $enrollment_id, $optionCount, $current_school_year_id);

                if($wasSuccessUpdate){

                    Alert::success("Installment Payment Schedule has been successfully modified.", $back_url);
                    exit();
                }

            }
        }
        ?>

            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <div class='col-md-11 offset-md-1'>
                    <div class='card'>
                        
                        <div class='card-header'>
                            <h4 class='text-center mb-3'>Installment Selection</h4>

                            <span>To pay: <?= $enrollmentToPay; ?></span>
                            <br>
                            <span>Down Payment: <?= $downPayment; ?></span>
                            <br>
                            <span>Remaining Payment: <?= $excessPaymentValue; ?></span>

                        </div>


                        <div class="card-body">
                            <form method='POST'>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Selected Installment plan</label>

                                    <select class="form-control" name="installment_id" id="installment_id">
                                        
                                        <?php
                                            $query = $con->prepare("SELECT * FROM installment
                                                WHERE enable = 1
                                                GROUP BY option
                                            ");
                                            $query->execute();
                                            
                                            echo "<option value='' disabled selected>Choose Installment</option>";

                                            if ($query->rowCount() > 0) {
                                                $i = 0;
                                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                                    $selected = "";  

                                                    $i++;

                                                    $transformedString = ucwords(str_replace('_', ' ', ucfirst($row['option'])));

                                                    if($row['option'] == $installmentOption){
                                                        $selected = "selected";
                                                    }
                                                    


                                                    echo "<option value='" . $row['option'] . "' $selected>" . $transformedString . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div id="paymentPopulateTable">
                                    <?php

                                        if($installmentOption != NULL) {
                                            ?>

                                            <table class="a">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Amount to pay</th>
                                                        <th>Payment Schedule</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    <!-- Add more rows as needed -->
                                                    <?php 

                                                        $query = $con->prepare("SELECT * FROM installment
                                                            WHERE option=:option
                                                            AND enable = 1

                                                        ");
                                                        $query->bindValue(":option", $installmentOption);
                                                        $query->execute();

                                                        if ($query->rowCount() > 0) {

                                                            $i = 0;

                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                                                $i++;
                                                                $amount_to_pay = 555;
                                                                $default_payment_due_db = $row['default_payment_due'];

                                                                $amount_to_pay = ($excessPaymentValue / $query->rowCount());
                                                                $amount_to_pay = number_format($amount_to_pay, 2);

                                                                $default_payment_due = date("M d, Y", strtotime($default_payment_due_db));

                                                                echo "
                                                                    <tr>
                                                                        <td>$i</td>
                                                                        <td>₱$amount_to_pay</td>
                                                                        <td>$default_payment_due</td>
                                                                    </tr>
                                                                ";
                                                            }
                                                        }
                                                    
                                                    ?>
                                                    
                                                </tbody>
                                            </table>

                                            <?php
                                        }
                                    
                                    ?>
                                </div>

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success'
                                    name='attach_schedule_payment_btn_<?php echo $enrollment_id; ?>'
                                    onclick='return confirm("Are you sure? This will change the enrollment payment installment ?");'>Edit Installment</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                

            </div>

            <script>

                function paymentScheduleAttach(){
                    
                }

                $("#installment_id").on('change', function () {

                    var option = $(this).val();

                    var amountToPay = <?php echo json_encode($excessPaymentValue); ?>;

                    console.log(amountToPay);

                    $.ajax({
                        url: '../../ajax/installment/populatePaymentWithDate.php',
                        type: 'POST',
                        data: {
                            option: option,amountToPay
                        },
                        dataType: 'json',
                        success: function (response) {

                            // Clear any existing content in the table
                            $('#paymentPopulateTable tbody').empty();

                            let table = `
                                <table class="a">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount to pay</th>
                                            <th>Payment Schedule</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            `;

                            // Set the HTML content to the div with ID 'paymentPopulateTable'
                            $('#paymentPopulateTable').html(table);

                            // Populate the table rows with data from the 'response' array
                            let options = '';
                            $.each(response, function(index, value) {

                                let date_to_pay = new Date(value.date_to_pay_db);
                                // let formattedDate = date_to_pay.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' });
                                let dateObject = new Date(value.default_payment_due);

                                let formattedDate = new Intl.DateTimeFormat('en-PH', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                                }).format(dateObject);

                                // console.log(formattedDate);
                                        // <td>${value.default_payment_due}</td>

                                options += `
                                    <tr>
                                        <td>${index + 1}</td> <!-- Use 'index + 1' to display the row number -->
                                        <td>₱${value.amount_to_pay}</td>
                                        <td>${formattedDate}</td>
                                    </tr>
                                `;
                            });

                            // Append the generated rows to the table body
                            $('#paymentPopulateTable tbody').append(options);
                        }
                    });
                });
            </script>
        <?php
    }
?>

