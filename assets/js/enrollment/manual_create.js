//
$(document).ready(function () {
  $('input[name="selected_department_id"]').on('change', function () {
    var selected_department_id = parseInt($(this).val());
    // console.log(selected_department_id);

    $.ajax({
      url: '../../ajax/enrollment/get_course_strand.php',
      type: 'POST',
      data: {
        selected_department_id,
      },
      dataType: 'json',

      success: function (response) {
        console.log(response);

        let departmentName = response.department_name;

        let programs = response.data;

        var options = '<option selected value="">Select Program</option>';

        var lrnSpan = `
          <span style="margin-left: 720px;">
              <small style="font-weight: bold;">LRN</small>
              <input class="form-control" style="width: 250px;"
                  type="text" name="lrn" id="lrn">
          </span>
        `;

        if (departmentName == 'Senior High School') {
          // $('#populateLRN').html(lrnSpan);
        } else if (departmentName == 'Tertiary') {
          // $('#populateLRN').html('');
        } else {
          // JHS
          $('#populateLRN').html(lrnSpan);
        }

        $.each(programs, function (index, value) {
          //

          options +=
            '<option value="' +
            value.program_id +
            '">' +
            value.program_name +
            '</option>';

          //
        });

        $('#program_id').html(options);
      },
    });
  });

  $('#program_id').on('change', function () {
    var program_id = parseInt($(this).val());

    $.ajax({
      url: '../../ajax/enrollment/populate_section.php',
      type: 'POST',
      data: {
        program_id,
      },
      dataType: 'json',

      success: function (response) {
        // response = response.trim();
        console.log(response);
        var options = '';

        if (response.length === 0) {
          // console.log('empty')
          options += '<option value="NoSection">No Section</option>';

          $('#course_id').html(options);

          var createSectionUrl = `../section/createe_section.php?id=${program_id}&manual_create=true`;

          var anchorHtml = `
            <a style="text-decoration: none; color: inherit" href="${createSectionUrl}" target="_blank">
                <i class="fas fa-plus-circle"></i>
              </a>
          `;

          $('#populateSectionCreate').html(anchorHtml);

          return;
        } else {
          var createSectionUrl = `../section/createe_section.php?id=${program_id}&manual_create=true`;

          var anchorHtml = `
            <a style="text-decoration: none; color: inherit" href="${createSectionUrl}" target="_blank">
                <i class="fas fa-plus-circle"></i>
              </a>
          `;

          $('#populateSectionCreate').html(anchorHtml);

          let disabled = '';

          var options = '<option selected value="">Available Sections</option>';

          $.each(response, function (index, value) {
            var course_id = value.course_id;
            var program_section = value.program_section;
            var enrollment_capacity = value.enrollment_capacity;
            var capacity = value.capacity;
            var non_enrolled_count = value.non_enrolled_count;
            var program_id = value.program_id;

            disabled = enrollment_capacity == capacity ? 'disabled' : '';

            options +=
              '<option value="' +
              course_id +
              '" ' +
              disabled +
              ' >' +
              program_section +
              ' &nbsp; Enrolled: ' +
              enrollment_capacity +
              ' / Capacity: ' +
              capacity + // Removed extra '+' here
              ', Non-enrolled: ' +
              non_enrolled_count +
              '</option>';
          });

          $('#course_id').html(options);
        }
      },
    });
  });

  $('input[name="admission_type"]').on('change', function () {
    console.log('admission_type');
    $('#os_route').prop('checked', false);

    const divToHide = document.querySelectorAll('.enrollment_new');

    // Check if the div exists before trying to hide it
    if (divToHide) {
      divToHide.forEach((div) => {
        div.style.display = 'block';
      });
    }
  });

  $('input[name="os_route"]').on('change', function () {
    console.log('os_route');

    $('input[name="admission_type"]').prop('checked', false);

    const divToHide = document.querySelectorAll('.enrollment_new');

    // Check if the div exists before trying to hide it
    if (divToHide) {
      divToHide.forEach((div) => {
        div.style.display = 'none';
      });
    }
  });

  const radioButton = document.getElementById('radioButton');
  const admission_radio_route = document.getElementById(
    'admission_radio_route'
  );

  if (radioButton) {
    // Add a click event listener to the radio button
    radioButton.addEventListener('click', function () {
      // Check if the radio button is selected
      if (this.checked) {
        window.location.href = 'ongoing_manual.php';
      }
    });
  }

  if (admission_radio_route) {
    admission_radio_route.addEventListener('click', function () {
      // Check if the radio button is selected
      if (this.checked) {
        window.location.href = 'manual_create.php';
      }
    });
    // $('input[name="admission_type"]').prop('checked', true);
  }
});

// document.addEventListener('DOMContentLoaded', function () {

//   //
//   // Attach event listener to "admission_type" radio buttons
//   const admissionTypeRadios = document.querySelectorAll(
//     'input[name="admission_type"]'
//   );
//   //
//   admissionTypeRadios.forEach(function (radio) {
//     radio.addEventListener('change', function () {
//       console.log('admission_type');
//       document.getElementById('os_route').checked = false;

//       const divsToHide = document.querySelectorAll('.enrollment_new');
//       divsToHide.forEach(function (div) {
//         div.style.display = 'block';
//       });
//     });
//   });

//   // Attach event listener to "os_route" radio button
//   document.getElementById('os_route').addEventListener('change', function () {
//     console.log('os_route');
//     admissionTypeRadios.forEach(function (radio) {
//       radio.checked = false;
//     });

//     const divsToHide = document.querySelectorAll('.enrollment_new');
//     divsToHide.forEach(function (div) {
//       div.style.display = 'none';
//     });
//   });
//   //
// });
