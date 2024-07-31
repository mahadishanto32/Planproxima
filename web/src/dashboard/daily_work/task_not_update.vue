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
                      <router-link :to="{ path: '/' }">
                        <i class="bx bx-home-alt"> </i>
                      </router-link>
                    </li>
                    <li class="breadcrumb-item active">Daily Work Schedule Status </li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <div class="row">
            <div class="col-12 col-sm-6 col-lg-2">
              <label class="control-label">Date </label>
              <fieldset class="form-group">
                <datepicker @closed="datepickerClosedFunction" :disabled-dates="state.disabledDates"
                  v-model="filterForm.date" name="date" class="form-control"></datepicker>
              </fieldset>
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
              <label class="control-label">Status </label>

              <fieldset class="form-group">
                <select class="form-control" v-model="filterForm.status" id="users-list-verified">
                  <option value="">All</option>
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </fieldset>
            </div>
            <!-- <fieldset class="form-group">
                <datepicker @closed="datepickerClosedFunction" :disabled-dates="state.disabledDates"
                  v-model="filterForm.date" name="date" class="form-control"></datepicker>
              </fieldset> -->
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="card">

              <table class="tablesaw table-striped table-hover table-bordered table" data-tablesaw-mode="columntoggle">
                <thead>
                  <tr>
                    <th scope="col">SL.No.</th>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Department</th>
                    <th scope="col">Today's Status</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(row, index) in items">
                    <template
                      v-if="(filterForm.status == 'Yes' && Number(row.task_status) > 0) || (filterForm.status == 'No' && Number(row.task_status) == 0) || (filterForm.status == '')">
                      <tr :key="row.id" v-bind:class="[Number(row.task_status) > 0 ? 'done' : 'not_done']">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.employee_id }}</td>
                        <td>{{ row.user_name }}</td>
                        <td>{{ row.designation }}</td>
                        <td>{{ row.dept_name }}</td>
                        <td>{{ Number(row.task_status) > 0 ? 'Yes' : 'No' }}</td>
                      </tr>
                    </template>
                  </template>
                </tbody>
                <tbody v-if="items.length > 0">
                  <tr v-if="filterForm.status == 'Yes' || filterForm.status == ''">
                    <th class="text-right" colspan="5">Yes</th>
                    <th>{{ totalEntry(items) }}</th>
                  </tr>
                  <tr v-if="filterForm.status == 'No' || filterForm.status == ''">
                    <th class="text-right" colspan="5">No</th>
                    <th>{{ totalNotEntry(items) }}</th>
                  </tr>
                  <tr v-if="filterForm.status == ''">
                    <th class="text-right" colspan="5">Total</th>
                    <th>{{ items.length }}</th>
                  </tr>

                </tbody>
              </table>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "../../axios_instance";
import { Form } from "vform";
import Select2 from 'v-select2-component';
import Datepicker from "vuejs-datepicker";
import { VueEditor } from "vue2-editor";
export default {
  props: {},
  components: {
    Datepicker,
    VueEditor,
    'Select2': Select2,
  },
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      user_data: JSON.parse(this.$localStorage.get("user")),
      items: [],
      filterForm: new Form({
        date: new Date(),
        status: '',
      }),
      state: {
        disabledDates: {
          to: new Date(2021, 0, 0), // Disable all dates up to specific date
          from: new Date(), // Disable all dates after specific date

        }
      }
    };
  },
  created() {
    this.role_id = this.user_data.role_id;
    this.dept_id = this.user_data.dept_id;
    this.getItems();
  },
  methods: {
    datepickerClosedFunction() {
      this.getItems();
    },
    async getItems() {
      let where = '?1=1'
      if (this.filterForm.date) {
        where += "&date=" + this.format_Date(this.filterForm.date);
      }
      await axios
        .get(this.api_url + "daliy_task_report" + where, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        })
        .then(({
          data
        }) => {
          if (data.success) {
            this.items = data.data;
          }
        });
    },
    totalEntry(type = 'yes') {
      var total = 0;
      for (let index = 0; index < this.items.length; index++) {
        if (Number(this.items[index].task_status) > 0) {
          total = total + 1;
        }
      }
      return total;
    },
    totalNotEntry(type = 'yes') {
      var total = 0;
      for (let index = 0; index < this.items.length; index++) {
        if (Number(this.items[index].task_status) == 0) {
          total = total + 1;
        }
      }
      return total;
    }
  },
  computed: {},
};
</script>
 