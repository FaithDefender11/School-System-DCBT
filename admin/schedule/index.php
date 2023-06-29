

<?php include_once('../../includes/admin_header.php')?>

    <div class="content">
      <nav>
        <h3>Department</h3>
        <div class="form-box">
          <div class="button-box">
            <div id="btn"></div>
            <button
              type="button"
              class="toggle-btn"
              id="shs-btn"
              onclick="shs()"
            >
              SHS
            </button>
            <button
              type="button"
              class="toggle-btn"
              id="college-btn"
              onclick="college()"
            >
              College
            </button>
          </div>
        </div>
      </nav>
      <main>
        <!--SHS SCHEDULE-->
        <div class="floating" id="shs-scheduler">
          <header>
            <div class="title">
              <h3>Scheduler <em>SHS *insert school year*</em></h3>
            </div>
          </header>
          <main>
            <div class="section-table">
              <div class="selector">
                <select name="GAS" id="GAS">
                  <option value="">*insert section*</option>
                </select>
                <select name="ICT" id="ICT">
                  <option value="">*insert section*</option>
                </select>
                <select name="HUMSS" id="HUMSS">
                  <option value="">*insert section*</option>
                </select>
                <select name="ABM" id="ABM">
                  <option value="">*insert section*</option>
                </select>
                <select name="IA" id="IA">
                  <option value="">*insert section*</option>
                </select>
                <select name="HE" id="HE">
                  <option value="">*insert section*</option>
                </select>
              </div>
              <div class="table-schedule">
                <table>
                  <thead>
                    <tr>
                      <th>Subject name</th>
                      <th>Room</th>
                      <th>Day</th>
                      <th>End time</th>
                      <th>Start time</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Subject 1</td>
                      <td>
                        <select name="room">
                          <option value="r101">R101</option>
                        </select>
                      </td>
                      <td>
                        <select name="day">
                          <option value="monday">Monday</option>
                        </select>
                      </td>
                      <td></td>
                      <td></td>
                      <td><i class="bi bi-plus"></i></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </main>
        </div>

        <div class="floating" id="shs-schedule-overview">
          <header>
            <div class="title">
              <h3>Schedule Overview <em>SHS</em></h3>
            </div>
          </header>
          <div class="tabs">
            <button class="tab" id="room">
              <i class="bi bi-clipboard-check"></i>
              Room
            </button>
            <button class="tab" id="section">
              <i class="bi bi-journal-arrow-down"></i>
              Section
            </button>
          </div>

          <header id="shs-room-tab">
            <div class="title">
              <small id="room-tab">Room</small>
            </div>
            <div class="room-selector">
              <select name="Room" id="">
                <option value="">*insert room*</option>
              </select>
            </div>
            <div class="action">
              <button class="icon">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
            </div>
          </header>

          <header id="shs-section-tab">
            <div class="title">
              <small id="section-tab">Section</small>
            </div>
            <div class="room-selector">
              <select name="Section" id="">
                <option value="">*insert section*</option>
              </select>
            </div>
            <div class="action">
              <button class="icon">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
            </div>
          </header>

          <main>
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>Monday</th>
                  <th>Tuesday</th>
                  <th>Wednesday</th>
                  <th>Thursday</th>
                  <th>Friday</th>
                  <th>Saturday</th>
                </tr>
              </thead>
              <tbody class="timestamp">
                <tr>
                  <td>7:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>

        <!--COLLEGE SCHEDULE-->
        <div class="floating" id="college-scheduler">
          <header>
            <div class="title">
              <h3>Scheduler <em>College *insert school year*</em></h3>
            </div>
          </header>
          <main>
            <div class="section-table">
              <div class="selector">
                <select name="BCM" id="BCM">
                  <option value="">*insert section*</option>
                </select>
                <select name="BPE" id="BPE">
                  <option value="">*insert section*</option>
                </select>
                <select name="BAE" id="BAE">
                  <option value="">*insert section*</option>
                </select>
                <select name="BSE" id="BSE">
                  <option value="">*insert section*</option>
                </select>
                <select name="BTTE" id="BTTE">
                  <option value="">*insert section*</option>
                </select>
              </div>
              <div class="table-schedule">
                <table>
                  <thead>
                    <tr>
                      <th>Subject name</th>
                      <th>Room</th>
                      <th>Day</th>
                      <th>End time</th>
                      <th>Start time</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Subject 1</td>
                      <td>
                        <select name="room">
                          <option value="r101">R101</option>
                        </select>
                      </td>
                      <td>
                        <select name="day">
                          <option value="monday">Monday</option>
                        </select>
                      </td>
                      <td></td>
                      <td></td>
                      <td><i class="bi bi-plus"></i></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </main>
        </div>

        <div class="floating" id="college-schedule-overview">
          <header>
            <div class="title">
              <h3>Schedule Overview <em>College</em></h3>
            </div>
          </header>
          <div class="tabs">
            <button class="tab" id="room">
              <i class="bi bi-clipboard-check"></i>
              Room
            </button>
            <button class="tab" id="section">
              <i class="bi bi-journal-arrow-down"></i>
              Section
            </button>
          </div>

          <header id="college-room-tab">
            <div class="title">
              <small id="room-tab">Room</small>
            </div>
            <div class="room-selector">
              <select name="Room" id="">
                <option value="">*insert room*</option>
              </select>
            </div>
            <div class="action">
              <button class="icon">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
            </div>
          </header>

          <header id="college-section-tab">
            <div class="title">
              <small id="section-tab">Section</small>
            </div>
            <div class="room-selector">
              <select name="Section" id="">
                <option value="">*insert section*</option>
              </select>
            </div>
            <div class="action">
              <button class="icon">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
            </div>
          </header>

          <main>
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>Monday</th>
                  <th>Tuesday</th>
                  <th>Wednesday</th>
                  <th>Thursday</th>
                  <th>Friday</th>
                  <th>Saturday</th>
                </tr>
              </thead>
              <tbody class="timestamp">
                <tr>
                  <td>7:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>
      </main>
    </div>
    <script src="../../assets/js/schedule.js"></script>