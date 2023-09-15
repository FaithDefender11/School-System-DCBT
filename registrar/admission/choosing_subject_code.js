//
$(document).on('click', function (event) {
  $('#show_subject_code_list').html('');
});
//
$(document).ready(function () {
  //

  $('#search_subject_code').on('dblclick', function () {
    $(this).val('');

    $('#show_subject_code_list').html('');

    document.getElementById('choosingSubjectCodeForm').submit();
    // Click the search button visually
    $('button[name="search_student"]').click();

    $('#search_subject_code').focus();

    // location.reload();
  });

  // Send Search Text to the server
  $('#search_subject_code').keyup(function () {
    var enrollment_id = parseInt($('#student_enrollment_id').val());
    var student_id = parseInt($('#student_id').val());

    // console.log(enrollment_id);
    // console.log(student_id);

    let searchText = $(this).val();
    // console.log(searchText);

    if (searchText != '') {
      //
      $.ajax({
        url: '../../ajax/admission/choosing_subject_code.php',
        method: 'POST',
        data: {
          searchQuery: searchText,
          enrollment_id,
          student_id,
        },
        success: function (response) {
          $('#show_subject_code_list').html(response);
          // console.log(response);
        },
      });
    } else {
      $('#show_subject_code_list').html('');
    }
  });

  // click class show_search under his <a>
  $(document).on('click', '.show_search a', function () {
    let searchText = $(this).text(); // Extract the text from the clicked <a> element
    //   const parts = searchText.split(' - ');
    //   const numberPart = parts[0];

    searchText = searchText.trim();

    $('#search_subject_code').val(searchText);

    // var providedSubjectProgramId = $('#provided_subject_program_id').val();

    $('#show_subject_code_list').html('');

    // Remove the bugs of incorrect getting of value.
    // Getting the correct provided_subject_program_id

    let providedSubjectProgramId = $(this)
      .next('input[name="provided_subject_program_id"]')
      .val();

    $('#selected_subject_program_id').val(parseInt(providedSubjectProgramId));

    $('#search_word').val(searchText);

    // console.log(providedSubjectProgramId);

    document.getElementById('choosingSubjectCodeForm').submit();

    // Click the search button visually

    // $('input[name="search_student"]').click();

    $('button[name="search_student"]').click();

    $('#search_subject_code').focus();
  });
});
