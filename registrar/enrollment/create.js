function showTransferee() {
  var shsSelection = document.getElementById('shs-checkbox');
  var collegeSelection = document.getElementById('college-checkbox');

  var transfereeHeader = document.getElementById('transferee-details');
  var courseStrandSelect = document.getElementById('course-strand');

  var transfereeCheckbox = document.getElementById('transferee');
  var shsCheckbox = document.getElementById('shs');
  var collegeCheckbox = document.getElementById('college');

  if (transfereeCheckbox.checked && shsCheckbox.checked) {
    shsSelection.style.display = 'flex';
    transfereeHeader.style.display = 'block';
    collegeSelection.style.display = 'none';
  } else if (transfereeCheckbox.checked && collegeCheckbox.checked) {
    collegeSelection.style.display = 'flex';
    transfereeHeader.style.display = 'block';
    shsSelection.style.display = 'none';
  } else {
    shsSelection.style.display = 'none';
    collegeSelection.style.display = 'none';
    transfereeHeader.style.display = 'none';
  }

  collegeCheckbox.addEventListener('change', function () {
    if (collegeCheckbox.checked) {
      updateOptions('College');
    }
  });

  shsCheckbox.addEventListener('change', function () {
    if (shsCheckbox.checked) {
      updateOptions('Senior High');
    }
  });

  function updateOptions(admissionType) {
    var courseStrandOptions = {
      College: [
        'Bachelor of Christian Ministries (BCM)',
        'Bachelor of Arts in English (ABE)',
        'Bachelor of Science in Entrepreneurship (BSENTREP)',
        'Bachelor of Science in Teachers Education (BTTE)',
        'Bachelor of Physical Education (BPE)',
      ],
      'Senior High': [
        'Accountancy, Business and Management (ABM)',
        'Humanities and Social Science (HUMMS)',
        'General Academic Strand (GAS)',
        'Information and Communication Technology (ICT)',
        'Industrial Arts - Consumer Electronics/Electrical Installation Maintenance (IA)',
      ],
    };

    courseStrandSelect.innerHTML = '';

    courseStrandOptions[admissionType].forEach(function (option) {
      var optionElement = document.createElement('option');
      optionElement.value = option;
      optionElement.textContent = option;
      courseStrandSelect.appendChild(optionElement);
    });
  }
}
