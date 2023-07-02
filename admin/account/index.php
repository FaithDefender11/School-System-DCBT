

<?php include_once('../../includes/admin_header.php')?>

    <div class="content">
      <main>
        <div class="floating">
          <header>
            <div class="title">
              <h3>Find Account</h3>
            </div>
          </header>
          <div class="filters">
            <table>
              <tr>
                <th rowspan="2" style="border-right: 2px solid black">
                  Search by
                </th>
                <th><button>ID number</button></th>
                <th><button>Account type</button></th>
                <th><button>Email</button></th>
                <th><button>Name</button></th>
              </tr>
            </table>
          </div>
          <main>
            <input type="text" />
            <button type="submit"><i class="bi bi-search"></i>Search</button>
          </main>
        </div>

        <div class="floating">
          <header>
            <div class="title">
              <h3>Account Details</h3>
            </div>
          </header>
          <main>
            <table>
              <thead>
                <tr>
                  <th>Account type</th>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><button class="danger">Reset Password</button></td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>
      </main>
    </div>