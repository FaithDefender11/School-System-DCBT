

<?php include_once('../../includes/admin_header.php')?>

    <div class="content">
      <nav>Department</nav>
      <main>
        <!--SHS-->
        <div class="floating" id="shs-menu">
          <header>
            <div class="title">
              <h3>Menu</h3>
            </div>
          </header>
          <main>
            <table>
              <tbody>
                <tr>
                  <td style="text-align: left">View Subjects</td>
                  <td style="text-align: left">
                    <a href="SHS-View-Subjects.html"
                      ><i class="bi bi-arrow-right-circle"></i
                    ></a>
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">View Strand Subjects</td>
                  <td style="text-align: left">
                    <a href="SHS-View-Strand-Subjects.html"
                      ><i class="bi bi-arrow-right-circle"></i
                    ></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>
        <div class="floating">
          <header>
            <div class="title">
              <h3>Options</h3>
            </div>
          </header>
          <main>
            <table>
              <tbody>
                <tr>
                  <td style="text-align: left">Automatic Subject Population</td>
                  <td style="text-align: left">
                    <i class="bi bi-info-circle"></i
                    ><input
                      type="checkbox"
                      id="shs-auto-population"
                      name="SHSAutoPopulation"
                    />
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">Manually Populate Sections</td>
                  <td style="text-align: left">
                    <button id="buttonSHS" class="populate">
                      Populate now
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>

        <!--COLLEGE-->
        <div class="floating" id="college-menu">
          <header>
            <div class="title">
              <h3>Menu</h3>
            </div>
          </header>
          <main>
            <table>
              <tbody>
                <tr>
                  <td style="text-align: left">View Subjects</td>
                  <td style="text-align: left">
                    <a href="College-View-Subjects.html"
                      ><i class="bi bi-arrow-right-circle"></i
                    ></a>
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">View Course Subjects</td>
                  <td style="text-align: left">
                    <a href="College-View-Course-Subjects.html"
                      ><i class="bi bi-arrow-right-circle"></i
                    ></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>
        <div class="floating">
          <header>
            <div class="title">
              <h3>Options</h3>
            </div>
          </header>
          <main>
            <table>
              <tbody>
                <tr>
                  <td style="text-align: left">Automatic Subject Population</td>
                  <td style="text-align: left">
                    <i class="bi bi-info-circle"></i
                    ><input
                      type="checkbox"
                      id="shs-auto-population"
                      name="SHSAutoPopulation"
                    />
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">Manually Populate Sections</td>
                  <td style="text-align: left">
                    <button id="buttonSHS" class="populate">
                      Populate now
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>
      </main>
    </div>