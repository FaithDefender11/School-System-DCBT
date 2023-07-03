

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
            <div class="scheduler">
              <nav>
                <a class="dropdown gas" id="GAS">
                  <span class="span-toggle" id="gas-span">GAS</span>
                  <input
                    type="button"
                    id="section-gas-1"
                    value="Section1"
                    onclick="toggleTable('table-gas-1','section-gas-1', 'gas-span')"
                  />
                  <input
                    type="button"
                    id="section-gas-2"
                    value="Section2"
                    onclick="toggleTable('table-gas-2','section-gas-2', 'gas-span')"
                  />
                </a>
                <a class="dropdown ict" id="ICT">
                  <span class="span-toggle" id="ict-span">ICT</span>
                  <input
                    type="button"
                    id="section-ict-1"
                    value="Section1"
                    onclick="toggleTable('table-ict-1','section-ict-1', 'ict-span')"
                  />
                  <input
                    type="button"
                    id="section-ict-2"
                    value="Section2"
                    onclick="toggleTable('table-ict-2','section-ict-2', 'ict-span')"
                  />
                </a>
                <a class="dropdown humss" id="HUMSS">
                  <span class="span-toggle" id="humss-span">HUMSS</span>
                  <input
                    type="button"
                    id="section-humss-1"
                    value="Section1"
                    onclick="toggleTable('table-humss-1','section-humss-1', 'humss-span')"
                  />
                  <input
                    type="button"
                    id="section-humss-2"
                    value="Section2"
                    onclick="toggleTable('table-humss-2','section-humss-2', 'humss-span')"
                  />
                </a>
                <a class="dropdown abm" id="ABM">
                  <span class="span-toggle" id="abm-span">ABM</span>
                  <input
                    type="button"
                    id="section-abm-1"
                    value="Section1"
                    onclick="toggleTable('table-abm-1','section-abm-1', 'abm-span')"
                  />
                  <input
                    type="button"
                    id="section-abm-2"
                    value="Section2"
                    onclick="toggleTable('table-abm-2','section-abm-2', 'abm-span')"
                  />
                </a>
                <a class="dropdown ia" id="IA">
                  <span class="span-toggle" id="ia-span">IA</span>
                  <input
                    type="button"
                    id="section-ia-1"
                    value="Section1"
                    onclick="toggleTable('table-ia-1','section-ia-1', 'ia-span')"
                  />
                  <input
                    type="button"
                    id="section-ia-2"
                    value="Section2"
                    onclick="toggleTable('table-ia-2','section-ia-2', 'ia-span')"
                  />
                </a>
                <a class="dropdown he" id="HE">
                  <span class="span-toggle" id="he-span">HE</span>
                  <input
                    type="button"
                    id="section-he-1"
                    value="Section1"
                    onclick="toggleTable('table-he-1','section-he-1', 'he-span')"
                  />
                  <input
                    type="button"
                    id="section-he-2"
                    value="Section2"
                    onclick="toggleTable('table-he-2','section-he-2', 'he-span')"
                  />
                </a>
              </nav>
              <div class="schedule-editor">
                <div class="schedule-table" id="table-gas-1">
                  <table class="a">
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
                        <td class="important">Subject 1</td>
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
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
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
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
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
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
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
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-gas-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-ict-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-ict-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-humss-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-humss-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-abm-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-abm-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-ia-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-ia-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-he-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-he-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
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
            <button class="tab" id="shs-room">
              <i class="bi bi-clipboard-check"></i>
              Room
            </button>
            <button class="tab" id="shs-section">
              <i class="bi bi-journal-arrow-down"></i>
              Section
            </button>
          </div>

          <div class="action" id="shs-room-tab">
            <div class="input" id="shs-room-select">
              <p>Room</p>
              <select name="Room">
                <option value="">*insert room*</option>
              </select>
            </div>
            <span>
              <button class="icon">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
            </span>
          </div>

          <div class="action" id="shs-section-tab">
            <div class="input" id="shs-section-select">
              <p>Section</p>
              <select name="Room">
                <option value="">*insert section*</option>
              </select>
            </div>
            <span>
              <button class="icon">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
            </span>
          </div>

          <main id="shs-room-table">
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
                  <td>mmw</td>
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

          <main id="shs-section-table">
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
            <div class="scheduler">
              <nav>
                <a class="dropdown bcm" id="BCM">
                  <span class="span-toggle" id="bcm-span">BCM</span>
                  <input
                    type="button"
                    id="section-bcm-1"
                    value="Section1"
                    onclick="toggleTable('table-bcm-1', 'section-bcm-1', 'bcm-span')"
                  />
                  <input
                    type="button"
                    id="section-bcm-2"
                    value="Section2"
                    onclick="toggleTable('table-bcm-2', 'section-bcm-2', 'bcm-span')"
                  />
                </a>
                <a class="dropdown bpe" id="BPE">
                  <span class="span-toggle" id="bpe-span">BPE</span>
                  <input
                    type="button"
                    id="section-bpe-1"
                    value="Section1"
                    onclick="toggleTable('table-bpe-1', 'section-bpe-1', 'bpe-span')"
                  />
                  <input
                    type="button"
                    id="section-bpe-2"
                    value="Section2"
                    onclick="toggleTable('table-bpe-2', 'section-bpe-2', 'bpe-span')"
                  />
                </a>
                <a class="dropdown bae" id="BAE">
                  <span class="span-toggle" id="bae-span">BAE</span>
                  <input
                    type="button"
                    id="section-bae-1"
                    value="Section1"
                    onclick="toggleTable('table-bae-1', 'section-bae-1', 'bae-span')"
                  />
                  <input
                    type="button"
                    id="section-bae-2"
                    value="Section2"
                    onclick="toggleTable('table-bae-2', 'section-bae-2', 'bae-span')"
                  />
                </a>
                <a class="dropdown bse" id="BSE">
                  <span class="span-toggle" id="bse-span">BSE</span>
                  <input
                    type="button"
                    id="section-bse-1"
                    value="Section1"
                    onclick="toggleTable('table-bse-1', 'section-bse-1', 'bse-span')"
                  />
                  <input
                    type="button"
                    id="section-bse-2"
                    value="Section2"
                    onclick="toggleTable('table-bse-2', 'section-bse-2', 'bse-span')"
                  />
                </a>
                <a class="dropdown btte" id="BTTE">
                  <span class="span-toggle" id="btte-span">BTTE</span>
                  <input
                    type="button"
                    id="section-btte-1"
                    value="Section1"
                    onclick="toggleTable('table-btte-1', 'section-btte-1', 'btte-span')"
                  />
                  <input
                    type="button"
                    id="section-btte-2"
                    value="Section2"
                    onclick="toggleTable('table-btte-2', 'section-btte-2', 'btte-span')"
                  />
                </a>
              </nav>
              <div class="schedule-editor">
                <div class="schedule-table" id="table-bcm-1">
                  <table class="a">
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
                        <td class="important">Subject 1</td>
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
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
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
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
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
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
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
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bcm-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-bpe-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bpe-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-bae-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bae-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-bse-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bse-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-btte-1">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-btte-2">
                  <table class="a">
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
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
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
            <button class="tab" id="college-room">
              <i class="bi bi-clipboard-check"></i>
              Room
            </button>
            <button class="tab" id="college-section">
              <i class="bi bi-journal-arrow-down"></i>
              Section
            </button>
          </div>

          <div class="action" id="college-room-tab">
            <div class="input" id="college-room-select">
              <p>Room</p>
              <select name="Room">
                <option value="">*insert room*</option>
              </select>
            </div>
            <span>
              <button class="icon">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
            </span>
          </div>

          <div class="action" id="college-section-tab">
            <div class="input" id="college-section-select">
              <p>Section</p>
              <select name="Room">
                <option value="">*insert section*</option>
              </select>
            </div>
            <span>
              <button class="icon">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
            </span>
          </div>

          <main id="college-room-table">
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

          <main id="college-section-table">
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