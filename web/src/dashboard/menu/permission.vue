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
                    <li class="breadcrumb-item active"> Menu Setup
                    </li>
                  </ol>
                </div>
              </div>
              <div class=" col-sm-3">
                <router-link class="btn btn-primary add-btn" :to="{ path: '/new_projects' }"><i
                    class="bx bx-add-alt"></i>
                  New Menu
                </router-link>
              </div>

            </div>
          </div>
        </div>
        <div class="content-body">
          <div class="users-list-filter px-1">

          </div>
          <!-- Zero configuration table -->
          <section id="basic-datatable">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">

                      <div class="table-responsive">
                        <h4>Menu List </h4><br>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Sl.No</th>
                              <th>Menu Name</th>
                              <th>Action</th>

                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(row, index) in items">
                              <tr :key="row.id">
                                <td>{{ index + 1 }}</td>
                                <td><b>{{ row.menu_name }}</b> </td>

                                <td>
                                  <input type="checkbox" value="1" v-model="row.view">
                                </td>
                              </tr>
                              <template v-for="(sub_row, i) in row.sub_menu">
                                <tr :key="sub_row.id">
                                  <td>{{ index + 1 }}.{{ i + 1 }}</td>
                                  <td> &rarr; {{ sub_row.menu_name }}</td>
                                  <td>
                                    <input type="checkbox" value="1" v-model="sub_row.view">
                                  </td>
                                </tr>
                              </template>
                            </template>
                          </tbody>
                          <tr v-if="items.length < 1">
                            <td colspan="4">Data not found</td>
                          </tr>

                          </tbody>
                          <button type="button"
                            class=" btn btn-primary btn_bottom_fixed add-btn btn-lg d-flex align-items-center  "
                            @click="update()"> Update </button>
                        </table>

                        <br>
                        <br>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import axios from 'axios';
  import { Form } from "vform";
  export default {
    data() {
      return {
        search: '',
        base_url: window.base_url,
        api_url: window.api_url,
        token: this.$localStorage.get("d_token"),
        id: this.$route.params.id,
        items: [],
        updateForm: new Form({
          items: []
        }),
      };
    },
    created() {
      this.getItems();
    },
    methods: {
      update() {

        this.updateForm.items = this.items;
        this.updateForm.post(
          this.api_url + "update_menu_permission/" + this.id + "?type=" + (this.$route.query.type ? this.$route.query.type : 'role'), 
          {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {

          if (res.data.success) {
            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
          }




        }, (error) => {
          console.log(error);

        })

      },
      async getItems() {
        try {
          const response = await axios.get(
            this.api_url + "get_menu_permission/" + this.id + "?type=" + (this.$route.query.type ? this.$route.query.type : 'role'),
            {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : "",
              },
            }
          ); 
          this.items = response.data.data;
          this.updatForm.items = this.items;

        } catch (error) { 
          console.error(error);
        }
      },

      async deleteRow(id) {
        // // //  
        try {
          await axios.delete(`${this.api_url}menu/${id}`, {
            headers: {
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          });
          this.items = this.items.filter(item => item.id !== id);
          // 
        } catch (error) {
          // 
          console.error("Error deleting item", error);
        }
      },
    },
    computed: {
      filteredItems() {
        return this.items.filter(item => {
          return item.menu_name.toLowerCase().includes(this.search.toLowerCase());
        });
      },
    },
  };
</script>

<style scoped>
  /* Add any styles you need for your component here */
</style>