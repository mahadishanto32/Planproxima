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
                <router-link class="btn btn-primary add-btn" :to="{ path: '/new_menu' }"><i class="bx bx-add-alt"></i>
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
                        <h4>Menu List</h4><br>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Sl.No</th>
                              <th>Menu Name</th>
                              <th>Menu URL</th>
                              <th>Order By</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(row, index) in items">
                              <tr :key="row.id">
                                <td>{{ index + 1 }}</td>
                                <td><b>{{ row.menu_name }}</b> </td> 
                                <td>{{ row.menu_url }}</td>
                                <td>{{ row.sort }}</td>
                                <td>{{ row.status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                  <div class="dropup">
                                    <span
                                      class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <router-link target="_blank" class="dropdown-item"
                                      :to="{ path: '/edit_menu/' + row.id   }">
                                      <i class="bx bx-edit-alt mr-1"></i>
                                      Edit
                                    </router-link>
                                      <a class="dropdown-item" @click="deleteRow(row.id)"><i
                                          class="bx bx-trash mr-1"></i>
                                        Delete</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <template v-for="(sub_row, i) in row.sub_menu">
                                <tr :key="sub_row.id">
                                  <td>{{ index + 1 }}.{{ i + 1 }}</td>
                                  <td> &rarr; {{ sub_row.menu_name }}</td>
                                  <td> &rarr;  {{ sub_row.menu_url }}</td>
                                  <td> &rarr;  {{ sub_row.sort }}</td>
                                  <td>{{ sub_row.status ? 'Active' : 'Inactive' }}</td>
                                  <td>
                                    <div class="dropup">
                                      <span
                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                      </span>
                                      <div class="dropdown-menu dropdown-menu-right">
                                        <router-link target="_blank" class="dropdown-item"
                                        :to="{ path: '/edit_menu/' + sub_row.id   }">
                                        <i class="bx bx-edit-alt mr-1"></i>
                                        Edit
                                      </router-link>

                                        <a class="dropdown-item" @click="deleteRow(sub_row.id)"><i
                                            class="bx bx-trash mr-1"></i>
                                          Delete</a>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </template>
                            </template>
                          </tbody>
                          <tr v-if="items.length < 1">
                            <td colspan="5" class="center">Data not found</td>
                          </tr>

                          </tbody>
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
        items: [],
        filterForm: new Form({}),
      };
    },
    created() {
      this.getItems();
    },
    methods: {
      async getItems() {


        try {
          await axios
            .get(this.api_url + "menu", {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : "",
              },
            })
            .then((res) => {
              console.log(res);
              this.items = res.data.data;

            });
        } catch (error) {
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
          this.getItems();
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