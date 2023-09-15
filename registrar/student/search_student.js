$(document).ready(function () {

  $('#search').on('dblclick', function () {
    $(this).val('');

    $('#show_list_student').html('');

    // location.reload();
    
  });
   
  // Send Search Text to the server
  $('#search').keyup(function () {
    let searchText = $(this).val();
    // console.log(searchText);

    if (searchText != '') {
      //
      $.ajax({
        url: 'action.php',
        method: 'POST',
        data: {
          searchQuery: searchText,
        },
        success: function (response) {
          $('#show_list_student').html(response);
        },
      });
    } else {
      $('#show_list_student').html('');
    }
    //
  });

  $(document).on('click', 'a', function () {

    const searchText = $(this).text(); // Extract the text from the clicked <a> element
    const parts = searchText.split(' - ');
    const numberPart = parts[0];

    $('#search').val(searchText);
    $('#student_unique_id_val').val(parseInt(numberPart));
    $('#search_word').val(searchText);

    $('#show_list_student').html('');
  });
});
