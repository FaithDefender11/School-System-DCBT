

<?php include_once('../../includes/admin_header.php')?>

<div class="content">
      <nav>Department</nav>
      <main>
        <div class="floating">
          <header>
            <div class="title">
              <h3>School Year</h3>
            </div>
          </header>
          <main>
            <table>
              <tr>
                <td style="font-weight: 700">2022-2023</td>
                <td style="text-align: right">Current</td>
                <td>
                  <button
                    type="button"
                    class="redirect-btn"
                    id="shs-calendar"
                    onclick="shs_calendar()"
                  >
                    <i class="bi bi-arrow-right-circle"></i>
                  </button>
                </td>
              </tr>
            </table>
          </main>
        </div>
      </main>
    </div>