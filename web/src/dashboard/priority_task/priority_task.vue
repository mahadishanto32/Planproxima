<!-- AnotherTemplate.vue -->
<template>
  <div>
    <table v-if="prioritytasks.length > 0" class="table table-bordered table-sm task-table">
      <thead class="thead-dark">
        <tr>
          <th rowspan="2">Priority</th>
          <th rowspan="2">Major Task </th>
          <th colspan="4">Weightage </th>
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
          <th>Qtr-2 (%)</th>

        </tr>
      </thead>
      <tbody>
        <template v-for="(item, index2) in prioritytasks">
          <tr class="text-center" :key="index2" :class="item.quarter_achiv == 100 ? 'bg-green' : ''">
            <td>Priority {{ index2 + 1 }}</td>
            <td style="text-align: left;">{{ item.task }}
              <div class="reply_reply">
                <!-- Check if item.msmcount is greater than 0 -->
                <div class="number" v-if="item.msmcount > 0" :style="{ background: item.upread > 0 ? 'red' : 'green' }">
                  {{ item.upread == 1 ? '1' : item.upread > 1 ?
                    '1+' : item.msmcount }}
                </div>
                <i class="bx bx-comment" @click="openChat(index2)"></i>
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
            <td>
              <div class="progress-container">
                <div class="progress-bar" :style="{ width: item.quarter_achiv + '%' }">
                </div>
                <span class="percentage-label">{{ item.quarter_achiv
                }}%</span>
              </div>
            </td>
            <td v-if="index2 == 0" :rowspan="prioritytasks.length">
              <router-link
                :to="{ path: '/priority_task_edit/' + item.priority_task_id + '?redirect_to=' + currentPath }"><i
                  class="bx bx-edit-alt"></i>Edit</router-link>


            </td>

          </tr>
        </template>

      </tbody>
    </table>
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
import {
  Form
} from "vform";
export default {

  components: {
  },
  data() {
    return {
      showChat: false,
      newMessage: "",
      commentItem: {},
      commentsReply: [],
      year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      user_data: JSON.parse(this.$localStorage.get("user")),
      role_id: '',
      prioritytasks: [],
      filterForm: new Form({
        limit: "50",
        quarter: 0,
        year: this.year,
      }),
      commontForm: new Form({
        comment: "",
        priority_item_task_id: 0,
        is_read: 0,
      }),
      currentPath: null,

    }
  },
  created() {
    this.filterForm.quarter = this.getCurrentQuarterId();
    this.currentPath = this.$route.path;
    this.role_id = this.user_data.role_id;
    if (this.user_data.role_id < 6) {
      this.getPriority();
    }

  },
  methods: {
    openChat(index) {

      this.commentItem = this.prioritytasks[index];
      this.commontForm.priority_item_task_id = this.commentItem.id;
      // Show the chat UI when the "Comment" button is clicked
      this.getReply();
      this.showChat = true;
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
    sendMessage() {
      // Implement logic to send a message
      // For example, you can push the new message to an array of messages
      // and clear the input field
      if (this.newMessage.trim() !== "") {
        // Push the message to the list of messages
        // You can define this.messages as an array in your data
        this.messages.push({ text: this.newMessage, sender: "user" });

        // Clear the input field
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
              this.getPriority();
            }
            loader.hide();
            //this.$router.push('/priority_tasks');
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

    async getPriority() {
      // 2 is Qtr  
      try {
        await axios
          .get(this.api_url + "priority_tasks_show_quarter/" + this.getCurrentQuarterId() + "?year=" + this.year, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            data
          }) => {
            this.prioritytasks = data.data.tasks;
          });
      } catch (error) {
      }
    },

  },

  computed: {},
};


</script>
<style>
.table thead {
  text-transform: none !important;
}

.table thead tr th {
  font-size: 16px;
}

.bg-green {
  background: #00800036;
}

.table thead {
  text-transform: none !important;
}
</style>

 