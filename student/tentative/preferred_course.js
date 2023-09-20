$(document).ready(function () {
  var admission_type = null;
  var department_type = null;

  var department_type = null;
  var department_type = null;
  var department_type = null;

  var checked_admission = document.querySelector(
    'input[name="admission_type"]:checked'
  );

  var selected_admission_type = $('input[name="admission_type"]:checked').val();
  // console.log(selected_admission_type);
  // var checked_department_type = document.querySelector(
  //   'input[name="department_type"]:checked'
  // );

  // var checked_course_level = document.querySelector(
  //   'input[name="course_level"]:checked'
  // );

  // if (
  //   checked_admission.value == 'Transferee' &&
  //   checked_department_type.value == 'Tertiary'
  // ) {
  //   document.getElementById('college_checkbox').style.display = 'block';
  //   // console.log(checked_admission.value);
  //   // console.log(checked_department_type.value);
  //   // console.log(checked_course_level.value);
  // }

  // if (
  //   checked_admission.value == 'Transferee' &&
  //   checked_department_type.value == 'Senior High School'
  // ) {
  //   document.getElementById('shs_checkbox').style.display = 'block';
  // }
  // console.log(checked_admission.value);
  // console.log(checked_department_type.value);
  // console.log(checked_course_level.value);

  // $('input[name="admission_type"]').on('change', function () {
  //   admission_type = $(this).val();

  //   if (admission_type == 'New') {
  //     UncheckedDepartmentType();
  //     RemoveLevelRadios();
  //     document.getElementById('transferee_details').style.display = 'none';
  //   }

  //   if (admission_type == 'Transferee') {
  //     UncheckedDepartmentType();
  //     $('input[name="course_level"]').prop('required', true);
  //   }
  // });

  //
  //

  $('input[name="department_type"]').on('change', function () {
    department_type = $(this).val();

    // console.log(department_type);

    if (admission_type != null) {
      admission_type = admission_type.trim();
    }

    if (admission_type == 'New') {
      RemoveLevelRadios();
    }

    if (admission_type == 'Transferee' && admission_type != 'New') {
      document.getElementById('transferee_details').style.display = 'block';

      if (department_type == 'Senior High School') {
        document.getElementById('college_checkbox').style.display = 'none';
        document.getElementById('shs_checkbox').style.display = 'block';
      } else if (department_type == 'Tertiary') {
        document.getElementById('college_checkbox').style.display = 'block';
        document.getElementById('shs_checkbox').style.display = 'none';
      }
    }

    $.ajax({
      url: '../../ajax/tentative/get_course_strand.php',
      type: 'POST',
      data: {
        department_type,
      },
      dataType: 'json',

      success: function (response) {
        var options = '<option selected value="">Select Program</option>';

        $.each(response, function (index, value) {
          options +=
            '<option value="' +
            value.program_id +
            '">' +
            value.program_name +
            '</option>';
        });

        $('#program_id').html(options);

        if (department_type == 'Senior High School') {
          var level_options = '<option selected value="">Choose Level</option>';

          level_options += '<option  value="11">Grade 11</option>';
          level_options += '<option  value="12">Grade 12</option>';

          $('#choose_level').html(level_options);
        }
        if (department_type == 'Tertiary') {
          var level_options = '<option selected value="">Choose Level</option>';

          level_options += '<option  value="1">1st Year</option>';
          level_options += '<option  value="2">2nd Year</option>';
          level_options += '<option  value="3">3rd Year</option>';
          level_options += '<option  value="4">4th Year</option>';

          $('#choose_level').html(level_options);
        }
      },
    });

    //
  });

  function UncheckedDepartmentType() {
    var yearLevelRadios = document.querySelectorAll(
      'input[name="department_type"]'
    );
    yearLevelRadios.forEach(function (radioButton) {
      radioButton.checked = false;
      // console.log('qwe')
    });
  }
  function RemoveLevelRadios() {
    document.getElementById('college_checkbox').style.display = 'none';
    document.getElementById('shs_checkbox').style.display = 'none';
  }
});

function PreferredBtn(pending_enrollees_id) {
  var pending_enrollees_id = parseInt(pending_enrollees_id);

  var selected_department_type = $(
    'input[name="department_type"]:checked'
  ).val();

  var selected_program_id = $('#program_id').val();
  var selected_choose_level = $('#choose_level').val();

  var selected_admission_type = $('input[name="admission_type"]:checked').val();

  if (
    selected_admission_type == 'New' &&
    selected_department_type == 'Senior High School'
  ) {
    // console.log('qwe')
    selected_choose_level = 11;
    selected_choose_level = parseInt(selected_choose_level);
  } else if (
    selected_admission_type == 'New' &&
    selected_department_type == 'Tertiary'
  ) {
    selected_choose_level = $('input[name="course_level"]:checked').val();

    selected_choose_level = 1;
    selected_choose_level = parseInt(selected_choose_level);
  }

  // console.log(selected_admission_type);
  // console.log(selected_department_type);
  // console.log(selected_choose_level);
  // console.log(selected_program_id);

  var hasError = false;

  if (selected_choose_level == undefined) {
    hasError = true;
    alert('Please input the Course Level field.');
    return;
  }

  if (selected_program_id == null || selected_program_id == '') {
    hasError = true;
    alert('Please input the Course/Strand field.');
    return;
  }
  // console.log(selected_admission_type);

  if (hasError == false) {
    selected_program_id = parseInt(selected_program_id);
    selected_choose_level = parseInt(selected_choose_level);

    // console.log(selected_admission_type);
    // console.log(selected_department_type);
    // console.log(selected_choose_level);
    // console.log(selected_program_id);

    // console.log(pending_enrollees_id);

    $.ajax({
      url: '../../ajax/tentative/add_preferred_course.php',
      type: 'POST',
      data: {
        selected_admission_type,
        selected_department_type,
        selected_choose_level,
        selected_program_id,
        pending_enrollees_id,
      },
      dataType: 'json',
      success: function (response) {
        // response = response.trim();

        // if(response)

        console.log(response);

        if (response[0].output == 'preferred_update_success') {
          Swal.fire({
            icon: 'success',
            title: 'Successfully Save Changes',
            timer: 1200,
            showCancelButton: false,
            confirmButtonText: 'Wait',
          }).then(() => {
            var url = 'process.php?new_student=true&step=enrollee_information';
            window.location.href = url;
            // location.reload();
          });
        } else if (response[0].output == '') {
          var url = 'process.php?new_student=true&step=enrollee_information';
            window.location.href = url;
        }

        if (response[0].departmentRequiredError) {
          // console.log(response[0].departmentRequiredError);
          $('.department_error').text(
            `* ${response[0].departmentRequiredError}`
          );
        }

        if (response[0].departmentInvalidError) {
          $('.department_error').text(
            `* ${response[0].departmentInvalidError}`
          );
        }

        if (response[0].admissionTypeRequiredError) {
          $('.admission_error').text(
            `* ${response[0].admissionTypeRequiredError}`
          );
        }

        if (response[0].admissionTypeInvalidError) {
          $('.admission_error').text(
            `* ${response[0].admissionTypeInvalidError}`
          );
        }

        if (response[0].courseLevelRequiredError) {
          console.log(response[0].courseLevelRequiredError);

          $('.course_level_error').text(
            `* ${response[0].courseLevelRequiredError}`
          );
        }

        if (response[0].courseLevelInvalidError) {
          console.log(response[0].courseLevelInvalidError);
          $('.course_level_error').text(
            `* ${response[0].courseLevelInvalidError}`
          );
        }

        // if (response == 'preferred_update_success') {
        //   Swal.fire({
        //     icon: 'success',
        //     title: 'Successfully Save Changes',
        //     timer: 1200,
        //     showCancelButton: false,
        //     confirmButtonText: 'Wait',
        //   }).then(() => {
        //     var url = 'process.php?new_student=true&step=enrollee_information';
        //     window.location.href = url;
        //     // location.reload();
        //   });
        // }
        // else if (response == '') {
        //   var url = 'process.php?new_student=true&step=enrollee_information';
        //   window.location.href = url;
        // }
        // 
      },
    });
  }
}

// $(document).ready(function () {
//   $("button[name='preferred_btn']").click(function () {
//     const enrolleeId = $(this).data('enrollee-id');

//     // const selectedAdmissionTypes = [];
//     // $("input[name='admission_type']:checked").each(function () {
//     //   selectedAdmissionTypes.push($(this).val());
//     // });

//     // // Now you have an array "selectedAdmissionTypes" containing the values of all checked radio inputs
//     // console.log(selectedAdmissionTypes);

//     const admissionType = $("input[name='admission_type']:checked").val();

//     // Now you have the value of the selected radio input
//     // You can use this value to perform further actions based on the selected radio input
//     console.log(admissionType);
//   });
// });
