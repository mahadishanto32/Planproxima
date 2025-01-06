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
                    <li class="breadcrumb-item active"> User Group
                    </li>
                  </ol>
                </div>
              </div>
              <div class=" col-sm-3">
                <router-link class="btn btn-primary add-btn" :to="{ path: '/add_user_group' }"><i
                    class="bx bx-add-alt"></i>
                  New User Group
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
                        <h4>User Group List</h4><br>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Title</th>
                              <th>Action</th>

                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(row, index) in items">
                              <tr :key="row.id">
                                <td>{{ row.id }}</td>
                                <td><b>{{ row.name }}</b> </td>
                                <td>{{ row.title }}</td>

                                <td>
                                  <div class="dropup">
                                    <span
                                      class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                    </span>


                                    <div class="dropdown-menu dropdown-menu-right">
                                      <router-link target="_blank" class="dropdown-item"
                                        :to="{ path: '/permission/' + row.id    }">
                                        <i class="bx bx-edit-alt mr-1"></i>
                                        Permission
                                      </router-link>

                                      <router-link target="_blank" class="dropdown-item"
                                        :to="{ path: '/edit_user_group/' + row.id   }">
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

                            </template>
                          </tbody>
                          <tr v-if="items.length < 1">
                            <td colspan="4">Data not found</td>
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
            .get(this.api_url + "usergroup ", {
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
          await axios.delete(`${this.api_url}usergroup/${id}`, {
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