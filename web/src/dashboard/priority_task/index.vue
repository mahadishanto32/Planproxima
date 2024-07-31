<template>
  <div>
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-1 mt-0">
            <div class="row breadcrumbs-top">
              <div class="col-sm-9">
                <div class="breadcrumb-wrapper col-9">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                    </li>
                    <li class="breadcrumb-item active"> Priority Tasks 
                    </li>
                  </ol>
                </div>
              </div>
              <div class=" col-sm-3">
                <router-link class="btn btn-primary add-btn" :to="{ path: '/priority_task_add' }"><i
                    class="bx bx-add-alt"></i>
                  New Tasks
                </router-link>
              </div>

            </div>
          </div>
        </div>
        <div class="content-body">
          <div class="users-list-filter px-1">
          </div>

          <section id="basic-datatable">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">

                      <div class="table-responsive">
                        <h4> Priority Tasks </h4>
                      </div>
                      <div class="row">
                        <div class="col-md-3" v-if="(deptItems.length > 1)">
                          <div class="form-group">
                            <label for="Profession">Department </label>
                            <div class="controls">
                              <select class="form-control" v-on:change="getItems()" v-model="filterForm.dept_id"
                                id="users-list-verified">
                                <option value="">All Department </option>
                                <option v-for="row in deptItems" :key="row.id" :value="row.id">
                                  {{ row.name }}
                                </option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="Profession">Quarter </label>
                            <div class="controls">
                              <select  class="form-control" v-on:change="getItems()"
                                v-model="filterForm.quarter" id="users-list-verified">
                               
                                <option v-for="row in quarter_months" :key="row.id" :value="row.id">
                                  {{ row.name }}
                                </option> 
                              </select>
                            </div>
                          </div>
                        </div>


                      </div>
                      <table class="table table-bordered table-sm task-table">
                        <thead class="thead-dark">

                          <tr>
                            <th rowspan="2">Priority</th>
                            <th rowspan="2">Major Task </th>
                            <th colspan="6">Weightage </th>
                            <th rowspan="2">Achievement (%)</th>

                            <th rowspan="2">Action</th>
                          </tr>
                          <tr>

                            <template v-if="filterForm.quarter == 3">
                              <th>January (%)</th>
                              <th>February (%)</th>
                              <th>March (%)</th>
                            </template>
                            <template v-if="filterForm.quarter == 4">
                              <th>April (%)</th>
                              <th>May (%)</th>
                              <th>June (%)</th>
                            </template>
                            <template v-if="filterForm.quarter == 1">
                              <th>July (%)</th>
                              <th>August (%)</th>
                              <th>September (%)</th>
                            </template>
                            <template v-if="filterForm.quarter == 2">
                              <th>October (%)</th>
                              <th>November (%)</th>
                              <th>December (%)</th>
                            </template>
                            <th>Qtr-{{ filterForm.quarter }} (%)</th>
                            <th>Half Year (%)</th>
                            <th>Year (%)</th>

                          </tr>

                        </thead>
                        <tbody>
                          <template v-for="(quarters, index) in items">
                            <tr class="text-center" :key="index">
                              <th colspan="10"> {{ quarters.dept.name }} ({{ quarters.user?.name }}) : Priority (Quarter
                                -{{
                                  quarters.quarter_id }})

                              </th>
                            </tr>
                            <template v-for="(item, index2) in quarters.tasks">
                              <tr class="text-center" :key="index2" :class="item.quarter_achiv == 100 ? 'bg-green' : ''">
                                <td @click="showLog(index, index2)">Priority {{ index2 + 1 }} </td>
                                <td style="text-align: left;">
                                  <p>{{ item.task }}</p>
                                  <div class="reply_reply">
                                    <!-- Check if item.msmcount is greater than 0 -->
                                    <div class="number" v-if="item.msmcount > 0"
                                      :style="{ background: item.upread > 0 ? 'red' : 'green' }">
                                      {{ item.upread == 1 ? '1' : item.upread > 1 ? '1+' : item.msmcount }}
                                    </div>
                                    <i class="bx bx-comment" @click="openChat(index, index2)"></i>
                                  </div>

                                </td>
                                <template v-if="filterForm.quarter == 3">
                                  <td :class="getCurrentMonth() == 'jan' ? 'current_month' : ''">{{ item.jan }}</td>
                                  <td :class="getCurrentMonth() == 'feb' ? 'current_month' : ''">{{ item.feb }}</td>
                                  <td :class="getCurrentMonth() == 'mar' ? 'current_month' : ''">{{ item.mar }}</td>
                                </template>
                                <template v-if="filterForm.quarter == 4">
                                  <td :class="getCurrentMonth() == 'apr' ? 'current_month' : ''">{{ item.apr }}</td>
                                  <td :class="getCurrentMonth() == 'may' ? 'current_month' : ''">{{ item.may }}</td>
                                  <td :class="getCurrentMonth() == 'jun' ? 'current_month' : ''">{{ item.jun }}</td>
                                </template>
                                <template v-if="filterForm.quarter == 1">
                                  <td :class="getCurrentMonth() == 'jul' ? 'current_month' : ''">{{ item.jul }}</td>
                                  <td :class="getCurrentMonth() == 'aug' ? 'current_month' : ''">{{ item.aug }}</td>
                                  <td :class="getCurrentMonth() == 'sep' ? 'current_month' : ''">{{ item.sep }}</td>
                                </template>
                                <template v-if="filterForm.quarter == 2">
                                  <td :class="getCurrentMonth() == 'oct' ? 'current_month' : ''">{{ item.oct }}</td>
                                  <td :class="getCurrentMonth() == 'nov' ? 'current_month' : ''">{{ item.nov }}</td>
                                  <td :class="getCurrentMonth() == 'dec' ? 'current_month' : ''">{{ item.dec }}</td>
                                </template>

                                <td>{{ item.quarter_weightage }}</td>
                                <td>{{ item.year_weightage }}</td>
                                <td>{{ item.half_year_weightage  }} </td>

                                <td>
                                  <div class="progress-container">
                                    <div class="progress-bar" :style="{ width: item.quarter_achiv + '%' }"></div>
                                    <span class="percentage-label">{{ item.quarter_achiv }}%  </span>
                                  </div>
                                </td>
                                <td v-if="index2 == 0" :rowspan="quarters.tasks.length">
                                  <template v-if="quarters.created_by == user_data.id">
                                    <router-link :to="{ path: '/priority_task_edit/' + quarters.id }"><i
                                        class="bx bx-edit-alt"></i>Edit</router-link> <br><br>

                                    <a @click="delete_row(quarters.id)" class="delete-icon"><i
                                        class="bx bx-trash"></i>Delete</a>
                                  </template>

                                </td>
                              </tr>
                            </template>
                          </template>
                        </tbody>

                      </table>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>

    <modal width="70%" height="70%" style="padding:50px" name="logs">
      <i @click="hiddenLog()" class="bx bx-x-circle  x-circle"></i>
      <div class="app-content ">
        <div class="card">
          <p>Priority {{ logItem.priority_value }} : {{ logItem.task }} [logs]</p> <br>


          <table class="table table-bordered table-sm task-table">
            <thead class="thead-dark">
              <tr>
                <th rowspan="2">Major Task </th>
                <th colspan="6">Weightage </th>
                <th rowspan="2">Achievement (%)</th>
              </tr>
              <tr>
                <template v-if="filterForm.quarter == 3">
                  <th style="">January (%)</th>
                  <th style="">February (%)</th>
                  <th style="">March (%)</th>
                </template>
                <template v-if="filterForm.quarter == 4">
                  <th style="">April (%)</th>
                  <th style="">May (%)</th>
                  <th style="">June (%)</th>
                </template>
                <template v-if="filterForm.quarter == 1">
                  <th style="">July (%)</th>
                  <th style="">August (%)</th>
                  <th style="">September (%)</th>
                </template>
                <template v-if="filterForm.quarter == 2">
                  <th style="">October (%)</th>
                  <th style="">November (%)</th>
                  <th style="">December (%)</th>
                </template>
                <th style="">Qtr-2 (%)</th>
                <th style="">Half Year (%)</th>
                <th style="">Year(%)</th>
              </tr>

            </thead>
            <tbody>

              <template v-for="(item, i) in logItems">
                <template v-if="i == 0">
                  <tr class="text-center">
                    <td style="text-align: left;">
                      {{ item.task }}


                    </td>
                    <template v-if="filterForm.quarter == 3">
                      <td>{{ item.jan }}</td>
                      <td>{{ item.feb }}</td>
                      <td>{{ item.mar }}</td>
                    </template>
                    <template v-if="filterForm.quarter == 4">
                      <td>{{ item.apr }}</td>
                      <td>{{ item.may }}</td>
                      <td>{{ item.jun }}</td>
                    </template>
                    <template v-if="filterForm.quarter == 1">
                      <td>{{ item.jul }}</td>
                      <td>{{ item.aug }}</td>
                      <td>{{ item.sep }}</td>
                    </template>
                    <template v-if="filterForm.quarter == 2">
                      <td>{{ item.oct }}</td>
                      <td>{{ item.nov }}</td>
                      <td>{{ item.dec }}</td>
                    </template>

                    <td>{{ item.half_year_weightage }}</td>
                    <td>{{ item.year_weightage }}</td>
                    <td>{{ item.quarter_weightage }}</td>

                    <td>
                      <div class="progress-container">
                        <div class="progress-bar" :style="{ width: item.quarter_achiv + '%' }"></div>
                        <span class="percentage-label">{{ item.quarter_achiv }}%</span>
                      </div>
                    </td>

                  </tr>

                </template>

                <template v-if="i > 0">
                  <tr class="text-center">
                    <td style="text-align: left;">
                      <p :class="getTaskClass(item.task, logItems[i - 1].task)">{{ item.task }}</p>

                    </td>
                    <template v-if="filterForm.quarter == 3">
                      <td>
                        <p :class="getMonthClass(item.jan, logItems[i - 1].jan)">{{ item.jan }}</p>
                      </td>
                      <td>
                        <p :class="getMonthClass(item.feb, logItems[i - 1].feb)">{{ item.feb }}</p>
                      </td>
                      <td>
                        <p :class="getMonthClass(item.mar, logItems[i - 1].mar)">{{ item.mar }}</p>
                      </td>
                    </template>
                    <template v-if="filterForm.quarter == 4">
                      <td>
                        <p :class="getMonthClass(item.apr, logItems[i - 1].apr)">{{ item.apr }}</p>
                      </td>
                      <td>
                        <p :class="getMonthClass(item.may, logItems[i - 1].may)">{{ item.may }}</p>
                      </td>
                      <td>
                        <p :class="getMonthClass(item.jun, logItems[i - 1].jun)">{{ item.jun }}</p>
                      </td>
                    </template>
                    <template v-if="filterForm.quarter == 1">
                      <td>
                        <p :class="getMonthClass(item.jul, logItems[i - 1].jul)">{{ item.jul }}</p>
                      </td>
                      <td>
                        <p :class="getMonthClass(item.aug, logItems[i - 1].aug)">{{ item.aug }}</p>
                      </td>
                      <td>
                        <p :class="getMonthClass(item.sep, logItems[i - 1].sep)">{{ item.sep }}</p>
                      </td>
                    </template>
                    <template v-if="filterForm.quarter == 2">
                      <td>
                        <p :class="getMonthClass(item.oct, logItems[i - 1].oct)">{{ item.oct }}</p>
                      </td>
                      <td>
                        <p :class="getMonthClass(item.nov, logItems[i - 1].nov)">{{ item.nov }}</p>
                      </td>
                      <td>
                        <p :class="getMonthClass(item.dec, logItems[i - 1].dec)">{{ item.dec }}</p>
                      </td>
                    </template>


                    <td>
                      <p :class="getMonthClass(item.half_year_weightage, logItems[i - 1].half_year_weightage)">{{
                        item.half_year_weightage }}</p>
                    </td>
                    <td>
                      <p :class="getMonthClass(item.year_weightage, logItems[i - 1].year_weightage)">{{
                        item.year_weightage }}</p>
                    </td>
                    <td>
                      <p :class="getMonthClass(item.quarter_weightage, logItems[i - 1].quarter_weightage)">{{
                        item.quarter_weightage }}</p>

                    </td>

                    <td>
                      <div class="progress-container">
                        <div class="progress-bar" :style="{ width: item.quarter_achiv + '%' }"></div>
                        <span class="percentage-label">{{ item.quarter_achiv }}%</span>
                      </div>
                    </td>

                  </tr>

                </template>



              </template>
            </tbody>

          </table>

        </div>

      </div>
    </modal>

    <div v-if="showChat" class="chat-ui">
      <div class="message-unit-head">
        <span class="chat-ui-cancel" @click="showChat = false">X</span>
        <h2>Comments </h2>
        <p>{{ commentItem.task }}</p>

      </div>

      <div class="message-unit">
        <template v-for="(item, i) in commentsReply">
          <div class="container" v-if="item.created_by != user_data.id">
            <img src="https://bpt.ssgbd.com/assets/app-assets/images/logo/logo.png" alt="Avatar" style="width:100%;">
            <p>{{ item.comment }} </p>
            <p style="font-size: 12px; color: #9b9898;">{{ item.name }}</p>
            <span class="time-right">{{ formatDateTime(item.created_at) }}</span>
          </div>

          <div class="container darker" v-if="item.created_by == user_data.id">
            <img src="https://bpt.ssgbd.com/assets/app-assets/images/logo/logo.png" alt="Avatar" class="right"
              style="width:100%;">
            <p>{{ item.comment }}</p>
            <p style="font-size: 12px; color: #9b9898;">{{ item.name }}</p>
            <span class="time-left"> {{ formatDateTime(item.created_at) }}</span>
          </div>
        </template>
        <p style="height: 300px;">

        </p>


      </div>

      <div class="reply-unit">

        <form @submit.prevent="comment()" style="width: 80%; padding: 0 0 0 10px;">
          <div class="row">
            <div class="col-md-10" style="padding-right: 10px;">
              <textarea rows="1" v-model="commontForm.comment" class="form-control "
                placeholder="Comment heare.... "></textarea>
            </div>
            <div class="col-md-2" style="padding-right: 10px;">
              <button type="submit" class="btn btn-primary">Send</button>

            </div>
          </div>

        </form>



      </div>
    </div>
  </div>
</template>

<script>
import axios from "../../axios_instance";
import { Form } from "vform";
export default {
  props: {},
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      showChat: false,
      newMessage: "",
      deptItems: [],
      items: [],
      commentItem: {},
      logItem: {},
      logItems: [],
      commentsReply: [],
      token: this.$localStorage.get("d_token"),
      user_data: JSON.parse(this.$localStorage.get("user")),
      base_url: window.base_url,
      api_url: window.api_url,

      filterForm: new Form({
        user_id: "",
        limit: "50",
        quarter: 0 ,
        dept_id: "",
        year: this.year,
      }),
      commontForm: new Form({
        comment: "",
        priority_item_task_id: 0,
        is_read: 0,
      }),

    };
  },
  created() {
    this.role_id = this.user_data.role_id; 
    this.filterForm.quarter = this.getCurrentQuarterId(); 
    this.getItems();
    this.getDept();
  },
  methods: {
    hiddenLog() {
      this.$modal.hide("logs");
    },
    showLog(index, index2) {

      this.logItem = this.items[index].tasks[index2];
      this.logItems = [];
      this.getLogs();
      this.$modal.show("logs");
    },

    async getLogs() {
      let where = '?1=1';

      console.log(this.logItem);

      if (this.logItem.id) {
        where += '&priority_task_item_id=' + this.logItem.id;
      }
      let loader = this.$loading.show();
      try {
        await axios
          .get(this.api_url + "priority_task_logs" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            data
          }) => {
            this.logItems = data.data;
            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
    },
    formatDateTime(dateTime) {
      const date = new Date(dateTime);
      const year = date.getFullYear();
      const monthName = date.toLocaleString('en-us', { month: 'short' });
      const day = date.getDate();
      const time = date.toLocaleTimeString();
      const formattedDay = day.toString().padStart(2, '0');

      return `${year} ${monthName} ${formattedDay}, ${time}`;
    },

    openChat(index, index2) {
      this.commentItem = this.items[index].tasks[index2];
      this.commontForm.priority_item_task_id = this.commentItem.id;
      this.getReply();
      this.showChat = true;
    },
    sendMessage() {
      if (this.newMessage.trim() !== "") {
        this.messages.push({ text: this.newMessage, sender: "user" });
        this.newMessage = "";
      }
    },
    comment() {
      try {
        if (this.commontForm.comment != "") {
          let loader = this.$loading.show();
          // this.addForm.task = this.$refs.editor.getContent();
          this.commontForm.post(this.api_url + "priority_task_comments", {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          }).then((res) => {
            console.log(res);
            console.log(console.log(res.headers));
            if (res.data.success) {
              this.$toasted.show(res.data.message, {
                theme: "bubble",
                duration: 5000,
                position: "bottom-right",
              });
              this.commontForm.comment = "";
              this.getReply();
              this.getItems();
            }
            loader.hide();
            this.$router.push('/priority_tasks');
          }, (error) => {
            console.log(error);
            loader.hide();
          })
        }
      } catch (error) {
        // loader.hide(); 
        console.log(error);
      }

    },
    async getReply() {
      let where = '?1=1';

      if (this.commontForm.priority_item_task_id) {
        where += '&priority_item_task_id=' + this.commontForm.priority_item_task_id;
      }
      let loader = this.$loading.show();
      try {
        await axios
          .get(this.api_url + "priority_task_comments" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            data
          }) => {
            this.commentsReply = data.data;
            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
    },
    async getItems() {
      let where = '?1=1';
      if (this.filterForm.quarter) {
        where += '&quarter=' + this.filterForm.quarter;
      }

      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id;
      }

      where += "&year=" + this.year;

      if (this.filterForm.user_id) {
        where += '&wing_id=' + this.filterForm.user_id;
      }
      let loader = this.$loading.show();
      try {
        await axios
          .get(this.api_url + "priority_tasks" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            data
          }) => {
            this.items = data.data;
            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
    },
    async getDept() {
      let loader = this.$loading.show();
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          loader.hide();
          this.deptItems = data.data;
        } else {
          loader.hide();
        }
      });
    },
    async delete_row(id) {
      // console.log(id);
      let loader = this.$loading.show();
      try {
        await axios
          .delete(this.api_url + "priority_tasks/" + id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            res
          }) => {
            if (res.data.success) {
              this.$toasted.show(res.data.message, {
                theme: "bubble",
                duration: 5000,
                position: "bottom-right",
              });
            }

            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
      this.getItems();
    },

  },
  computed: {
    getTaskClass() {
      return (currentMonth, previousMonth) => {
        return currentMonth == previousMonth ? '' : 'text-red';
      };
    },
    getMonthClass() {
      return (currentMonth, previousMonth) => {
        return currentMonth > previousMonth ? 'text-red' : currentMonth < previousMonth ? 'text-red' : '';
      };
    }
  }
}
  ;
</script>
<style>
/* styles.css */
.text-red {
  color: red;
}

.text-green {
  color: green;
}

.progress-container {
  height: 30px;
  position: relative;
  background-color: #A8A6A6;
  border-radius: 50px;
  box-shadow: 2px 2px 2px #4CAF4FBB;
  /* Replace 'your-shadow-color' with the desired shadow color */
}

.progress-bar {
  height: 100%;
  width: 0;
  background: linear-gradient(to bottom, #4CAF50, #4CAF4F70, #4CAF50);
  /* Gradient from green to blue to green, top to bottom */
  position: absolute;
  transition: width 0.5s;
  border-radius: 50px;
}



.percentage-label {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #fff;
}

.chat-ui {
  position: fixed;
  right: 0;
  top: 150px;
  width: 500px;
  border-radius: 9px !important;
  border: 1px solid #fff;
  /* Adjust the width as needed */
  height: 100%;
  background-color: #f0f0f0;
  border-left: 1px solid #ccc;
  z-index: 1000;
  overflow-y: auto;
  padding: 10px;
}

.chat-ui-cancel {
  position: fixed;
  right: 18px;
  font-size: 17px;
  color: #fff;
  background: red;
  border-radius: 50px;
  font-weight: bold;
  width: 22px;
  text-align: center;
}

.message-unit {
  height: 100%;
  background: #fff;
  padding: 10px;
  border-radius: 9px !important;
}

.reply-unit {
  position: fixed;
  bottom: 0px;
  width: inherit;
  background: #02145b;
  padding: 10px;
}

.chat-messages {
  /* Style for the chat messages area */
}

.chat-input {
  /* Style for the input field and send button */
  display: flex;
  align-items: center;
  border-top: 1px solid #ccc;
  padding: 10px;
}

.chat-input input {
  flex: 1;
  margin-right: 10px;
}

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right: 0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}

.reply_reply {
  color: #e65e0c;
  font-size: 19px;
  position: absolute;
  right: 0px;
  background: #dbd9d9;
  padding: 5px;
  border-radius: 7px 0 0 0;
  bottom: 0px;

}

.number {
  position: absolute;
  top: -17px;
  right: 0px;
  color: white;
  border-radius: 17px;
  width: 20px;
  height: 22px;
  font-size: 15px;
  align-content: center;
  text-align: center;
}

.task-table .thead-dark th {
  background: #e65e0c !important;
  border-color: 1px solid #DFE3E7 !important;
}

.current_month {
  background: #f4d2c55e;
}

.bg-green {
  background: #00800036;
}

.table thead {
  text-transform: none !important;
}

.table thead tr th {
  font-size: 16px;
}
</style>
