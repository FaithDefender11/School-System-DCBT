// Removes the show_subject_code_list div if user clicked outside its div

$(document).on('click', function (event) {
  $('#show_subject_code_list').html('');
});

$(document).ready(function () {
  //

  $('#search_curriculum_subject_code').on('dblclick', function () {
    // Empty the
    $(this).val('');

    $('#show_subject_code_list').html('');

    document.getElementById('findCurriculumForm').submit();

    $('button[name="search_student"]').click();
  });

  // Send Search Text to the server
  $('#search_curriculum_subject_code').keyup(function () {
    //
    var student_program_id = parseInt($('#student_program_id').val());
    var student_id = parseInt($('#student_id').val());

    // console.log(enrollment_id);
    // console.log(student_id);

    let searchText = $(this).val();
    // console.log(searchText);

    if (searchText != '') {
      $.ajax({
        url: '../../ajax/admission/find_credit_subject_code.php',
        method: 'POST',
        data: {
          searchQuery: searchText,
          student_program_id,
          student_id,
        },
        success: function (response) {
            $('#show_curriculum_subject_list').html(response);
        //   console.log(response);
        },
      });
    } else {
      $('#show_curriculum_subject_list').html('');
    }
  });

  // click class show_search under his <a>

    $(document).on('click', '.show_search a', function () {
      let searchText = $(this).text(); // Extract the text from the clicked <a> element

      searchText = searchText.trim();

      $('#search_subject_code').val(searchText);

      // var providedSubjectProgramId = $('#provided_subject_program_id').val();

      //   $('#show_curriculum_subject_list').html('');

      // Remove the bugs of incorrect getting of value.
      let providedSubjectProgramId = $(this)
        .next('input[name="provided_subject_program_id"]')
        .val();

      //  Store the value of providedSubjectProgramId into #selected_subject_program_id
      
      $('#selected_subject_program_id').val(parseInt(providedSubjectProgramId));

      $('#search_word').val(searchText);

      // console.log(providedSubjectProgramId);

      document.getElementById('findCurriculumForm').submit();
      $('button[name="search_student"]').click();
    });


});
