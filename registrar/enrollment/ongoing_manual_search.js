//
$(document).on('click', function (event) {
  $('#show_os_student').html('');
});

//
$(document).ready(function () {
  //
  $('#student_unique_id_val').on('dblclick', function () {
    $(this).val('');

    $('#show_os_student').html('');
    $('#student_unique_id_val').focus();
  });

  // Send Search Text to the server
  $('#student_unique_id_val').keyup(function () {
    let searchText = $(this).val();
    // console.log(searchText);

    if (searchText != '') {
      $.ajax({
        url: '../../ajax/admission/choosing_os_search.php',
        method: 'POST',
        data: {
          searchQuery: searchText,
          //   enrollment_id,
          //   student_id,
        },
        success: function (response) {
          $('#show_os_student').html(response);
          //   console.log(response);
        },
      });
    } else {
      $('#show_os_student').html('');
    }
    $('#show_os_student').html('');
  });

  //   $(document).on('click', '.show_search a', function () {
  //     let searchText = $(this).text().trim(); // Extract the text from the clicked <a> element

  //     var pattern = /#(\d+)/; // Regular expression pattern to match the number after "#"

  //     // console.log(searchText);

  //     const matches = searchText.match(pattern);

  //     $('#show_os_student').html('');

  //     console.log(matches);
  //     if (matches) {
  //       let student_unique_number = matches[1]; // Use matches[1] instead of $matches[1]

  //       //   console.log(student_unique_number);

  //       $('#student_unique_id_val').val(student_unique_number);

  //       $('#search_word').val(student_unique_number);

  //       $('#student_unique_id_val').focus();

  //       $('#student_unique_id_val').trigger(
  //         jQuery.Event('keypress', { keyCode: 13 })
  //       );
  //     }

  //   });

  $(document).on('click', '.show_search a', function () {

    

    let searchText = $(this).text().trim(); // Extract the text from the clicked <a> element

    var pattern = /#(\d+)/; // Regular expression pattern to match the number after "#"

    const matches = searchText.match(pattern);

    $('#show_os_student').html('');

    if (matches) {
      let student_unique_number = matches[1];

      $('#student_unique_id_val').val(student_unique_number);

      // Trigger key events to simulate typing
      const inputElement = $('#student_unique_id_val');
      const valueLength = student_unique_number.length;

      for (let i = 0; i < valueLength; i++) {
        const charCode = student_unique_number.charCodeAt(i);
        const event = $.Event('keypress', {
          which: charCode,
          keyCode: charCode,
          charCode: charCode,
        });
        inputElement.trigger(event);
      }

      // Focus on the input field
      inputElement.focus();
    }
  });
});
