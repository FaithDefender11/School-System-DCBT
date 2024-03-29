

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
                <a class="drop gas" id="GAS">
                  <span class="span-toggle" id="gas-span">GAS</span>
                  <label for="Grade 11">Grade 11</label>
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
                  <label for="Grade 12">Grade 12</label>
                  <input
                    type="button"
                    id="section-gas-3"
                    value="Section1"
                    onclick="toggleTable('table-gas-3','section-gas-3', 'gas-span')"
                  />
                  <input
                    type="button"
                    id="section-gas-4"
                    value="Section2"
                    onclick="toggleTable('table-gas-4','section-gas-4', 'gas-span')"
                  />
                </a>
                <a class="drop ict" id="ICT">
                  <span class="span-toggle" id="ict-span">ICT</span>
                  <label for="Grade 11">Grade 11</label>
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
                  <label for="Grade 12">Grade 12</label>
                  <input
                    type="button"
                    id="section-ict-3"
                    value="Section1"
                    onclick="toggleTable('table-ict-3','section-ict-3', 'ict-span')"
                  />
                  <input
                    type="button"
                    id="section-ict-4"
                    value="Section2"
                    onclick="toggleTable('table-ict-4','section-ict-4', 'ict-span')"
                  />
                </a>
                <a class="drop humss" id="HUMSS">
                  <span class="span-toggle" id="humss-span">HUMSS</span>
                  <label for="Grade 11">Grade 11</label>
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
                  <label for="Grade 12">Grade 12</label>
                  <input
                    type="button"
                    id="section-humss-3"
                    value="Section1"
                    onclick="toggleTable('table-humss-3','section-humss-3', 'humss-span')"
                  />
                  <input
                    type="button"
                    id="section-humss-4"
                    value="Section2"
                    onclick="toggleTable('table-humss-4','section-humss-4', 'humss-span')"
                  />
                </a>
                <a class="drop abm" id="ABM">
                  <span class="span-toggle" id="abm-span">ABM</span>
                  <label for="Grade 11">Grade 11</label>
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
                  <label for="Grade 12">Grade 12</label>
                  <input
                    type="button"
                    id="section-abm-3"
                    value="Section1"
                    onclick="toggleTable('table-abm-3','section-abm-3', 'abm-span')"
                  />
                  <input
                    type="button"
                    id="section-abm-4"
                    value="Section2"
                    onclick="toggleTable('table-abm-4','section-abm-4', 'abm-span')"
                  />
                </a>
                <a class="drop ia" id="IA">
                  <span class="span-toggle" id="ia-span">IA</span>
                  <label for="Grade 11">Grade 11</label>
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
                  <label for="Grade 12">Grade 12</label>
                  <input
                    type="button"
                    id="section-ia-3"
                    value="Section1"
                    onclick="toggleTable('table-ia-3','section-ia-3', 'ia-span')"
                  />
                  <input
                    type="button"
                    id="section-ia-4"
                    value="Section2"
                    onclick="toggleTable('table-ia-4','section-ia-4', 'ia-span')"
                  />
                </a>
                <a class="drop he" id="HE">
                  <span class="span-toggle" id="he-span">HE</span>
                  <label for="Grade 11">Grade 11</label>
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
                  <label for="Grade 12">Grade 12</label>
                  <input
                    type="button"
                    id="section-he-3"
                    value="Section1"
                    onclick="toggleTable('table-he-3','section-he-3', 'he-span')"
                  />
                  <input
                    type="button"
                    id="section-he-4"
                    value="Section2"
                    onclick="toggleTable('table-he-4','section-he-4', 'he-span')"
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
                <div class="schedule-table" id="table-gas-3">
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
                <div class="schedule-table" id="table-gas-4">
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
                <div class="schedule-table" id="table-ict-3">
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
                <div class="schedule-table" id="table-ict-4">
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
                <div class="schedule-table" id="table-humss-3">
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
                <div class="schedule-table" id="table-humss-4">
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
                <div class="schedule-table" id="table-abm-3">
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
                <div class="schedule-table" id="table-abm-4">
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
                <div class="schedule-table" id="table-ia-3">
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
                <div class="schedule-table" id="table-ia-4">
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
                <div class="schedule-table" id="table-he-3">
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
                <div class="schedule-table" id="table-he-4">
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
            <div class="action">
              <div class="dropdown">
                <button class="icon">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item"
                    ><i class="bi bi-printer"></i>Print Schedule</a
                  >
                </div>
              </div>
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
          </div>

          <div class="action" id="shs-section-tab">
            <div class="input" id="shs-section-select">
              <p>Section</p>
              <select name="Room">
                <option value="">*insert section*</option>
              </select>
            </div>
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
                <a class="drop bcm" id="BCM">
                  <span class="span-toggle" id="bcm-span">BCM</span>
                  <label for="1st Year">1st Year</label>
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
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-bcm-3"
                    value="Section1"
                    onclick="toggleTable('table-bcm-3', 'section-bcm-3', 'bcm-span')"
                  />
                  <input
                    type="button"
                    id="section-bcm-4"
                    value="Section2"
                    onclick="toggleTable('table-bcm-4', 'section-bcm-4', 'bcm-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-bcm-5"
                    value="Section1"
                    onclick="toggleTable('table-bcm-5', 'section-bcm-5', 'bcm-span')"
                  />
                  <input
                    type="button"
                    id="section-bcm-6"
                    value="Section2"
                    onclick="toggleTable('table-bcm-6', 'section-bcm-6', 'bcm-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-bcm-7"
                    value="Section1"
                    onclick="toggleTable('table-bcm-7', 'section-bcm-7', 'bcm-span')"
                  />
                  <input
                    type="button"
                    id="section-bcm-8"
                    value="Section2"
                    onclick="toggleTable('table-bcm-8', 'section-bcm-8', 'bcm-span')"
                  />
                </a>
                <a class="drop bpe" id="BPE">
                  <span class="span-toggle" id="bpe-span">BPE</span>
                  <label for="1st Year">1st Year</label>
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
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-bpe-3"
                    value="Section1"
                    onclick="toggleTable('table-bpe-3', 'section-bpe-3', 'bpe-span')"
                  />
                  <input
                    type="button"
                    id="section-bpe-4"
                    value="Section2"
                    onclick="toggleTable('table-bpe-4', 'section-bpe-4', 'bpe-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-bpe-5"
                    value="Section1"
                    onclick="toggleTable('table-bpe-5', 'section-bpe-5', 'bpe-span')"
                  />
                  <input
                    type="button"
                    id="section-bpe-6"
                    value="Section2"
                    onclick="toggleTable('table-bpe-6', 'section-bpe-6', 'bpe-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-bpe-7"
                    value="Section1"
                    onclick="toggleTable('table-bpe-7', 'section-bpe-7', 'bpe-span')"
                  />
                  <input
                    type="button"
                    id="section-bpe-8"
                    value="Section2"
                    onclick="toggleTable('table-bpe-8', 'section-bpe-8', 'bpe-span')"
                  />
                </a>
                <a class="drop bae" id="BAE">
                  <span class="span-toggle" id="bae-span">BAE</span>
                  <label for="1st Year">1st Year</label>
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
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-bae-3"
                    value="Section1"
                    onclick="toggleTable('table-bae-3', 'section-bae-3', 'bae-span')"
                  />
                  <input
                    type="button"
                    id="section-bae-4"
                    value="Section2"
                    onclick="toggleTable('table-bae-4', 'section-bae-4', 'bae-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-bae-5"
                    value="Section1"
                    onclick="toggleTable('table-bae-5', 'section-bae-5', 'bae-span')"
                  />
                  <input
                    type="button"
                    id="section-bae-6"
                    value="Section2"
                    onclick="toggleTable('table-bae-6', 'section-bae-6', 'bae-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-bae-7"
                    value="Section1"
                    onclick="toggleTable('table-bae-7', 'section-bae-7', 'bae-span')"
                  />
                  <input
                    type="button"
                    id="section-bae-8"
                    value="Section2"
                    onclick="toggleTable('table-bae-8', 'section-bae-8', 'bae-span')"
                  />
                </a>
                <a class="drop bse" id="BSE">
                  <span class="span-toggle" id="bse-span">BSE</span>
                  <label for="1st Year">1st Year</label>
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
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-bse-3"
                    value="Section1"
                    onclick="toggleTable('table-bse-3', 'section-bse-3', 'bse-span')"
                  />
                  <input
                    type="button"
                    id="section-bse-4"
                    value="Section2"
                    onclick="toggleTable('table-bse-4', 'section-bse-4', 'bse-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-bse-5"
                    value="Section1"
                    onclick="toggleTable('table-bse-5', 'section-bse-5', 'bse-span')"
                  />
                  <input
                    type="button"
                    id="section-bse-6"
                    value="Section2"
                    onclick="toggleTable('table-bse-6', 'section-bse-6', 'bse-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-bse-7"
                    value="Section1"
                    onclick="toggleTable('table-bse-7', 'section-bse-7', 'bse-span')"
                  />
                  <input
                    type="button"
                    id="section-bse-8"
                    value="Section2"
                    onclick="toggleTable('table-bse-8', 'section-bse-8', 'bse-span')"
                  />
                </a>
                <a class="drop btte" id="BTTE">
                  <span class="span-toggle" id="btte-span">BTTE</span>
                  <label for="1st Year">1st Year</label>
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
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-btte-3"
                    value="Section1"
                    onclick="toggleTable('table-btte-3', 'section-btte-3', 'btte-span')"
                  />
                  <input
                    type="button"
                    id="section-btte-4"
                    value="Section2"
                    onclick="toggleTable('table-btte-4', 'section-btte-4', 'btte-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-btte-5"
                    value="Section1"
                    onclick="toggleTable('table-btte-5', 'section-btte-5', 'btte-span')"
                  />
                  <input
                    type="button"
                    id="section-btte-6"
                    value="Section2"
                    onclick="toggleTable('table-btte-6', 'section-btte-6', 'btte-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-btte-7"
                    value="Section1"
                    onclick="toggleTable('table-btte-7', 'section-btte-7', 'btte-span')"
                  />
                  <input
                    type="button"
                    id="section-btte-8"
                    value="Section2"
                    onclick="toggleTable('table-btte-8', 'section-btte-8', 'btte-span')"
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
                <div class="schedule-table" id="table-bcm-3">
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
                <div class="schedule-table" id="table-bcm-4">
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
                <div class="schedule-table" id="table-bcm-5">
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
                <div class="schedule-table" id="table-bcm-6">
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
                <div class="schedule-table" id="table-bcm-7">
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
                <div class="schedule-table" id="table-bcm-8">
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
                <div class="schedule-table" id="table-bpe-3">
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
                <div class="schedule-table" id="table-bpe-4">
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
                <div class="schedule-table" id="table-bpe-5">
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
                <div class="schedule-table" id="table-bpe-6">
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
                <div class="schedule-table" id="table-bpe-7">
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
                <div class="schedule-table" id="table-bpe-8">
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
                <div class="schedule-table" id="table-bae-3">
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
                <div class="schedule-table" id="table-bae-4">
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
                <div class="schedule-table" id="table-bae-5">
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
                <div class="schedule-table" id="table-bae-6">
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
                <div class="schedule-table" id="table-bae-7">
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
                <div class="schedule-table" id="table-bae-8">
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
                <div class="schedule-table" id="table-bse-3">
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
                <div class="schedule-table" id="table-bse-4">
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
                <div class="schedule-table" id="table-bse-5">
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
                <div class="schedule-table" id="table-bse-6">
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
                <div class="schedule-table" id="table-bse-7">
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
                <div class="schedule-table" id="table-bse-8">
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
                <div class="schedule-table" id="table-btte-3">
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
                <div class="schedule-table" id="table-btte-4">
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
                <div class="schedule-table" id="table-btte-5">
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
                <div class="schedule-table" id="table-btte-6">
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
                <div class="schedule-table" id="table-btte-7">
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
                <div class="schedule-table" id="table-btte-8">
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
            <div class="action">
              <div class="dropdown">
                <button class="icon">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item"
                    ><i class="bi bi-printer"></i>Print Schedule</a
                  >
                </div>
              </div>
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
          </div>

          <div class="action" id="college-section-tab">
            <div class="input" id="college-section-select">
              <p>Section</p>
              <select name="Room">
                <option value="">*insert section*</option>
              </select>
            </div>
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
    <script src="../../assets/js/dropdownMenu.js"></script>