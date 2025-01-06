<template>
  <div>
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
              <div class="col-sm-12">
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb p-0 mb-1">
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                    </li>
                    <li class="breadcrumb-item active">Department</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="content-body">
          <!-- Input Section with Fixed Position -->
          <section id="organization-revenue" class="fixed-revenue-section">
            <div class="card">
              <div class="card-content">
                <div class="row">
                  <div class="col-md-6 offset-md-2">
                    <label for="organizationRevenue" class="form-label">Organization Revenue</label>
                    <input
                      type="number"
                      v-model="organization_revenue"
                      class="form-control"
                      placeholder="Enter organization revenue"
                      id="organizationRevenue"
                    />

                    <!-- Inverse Bar Chart Display for Remaining Percentage -->
                    <div class="bar-chart-container">
                      <div class="bar-chart">
                        <div
                          class="bar-fill"
                          :style="{ width: `${100 - remainingPercentage}%`, backgroundColor: barColor }"
                        ></div>
                      </div>
                      <small :class="{ 'text-danger': totalPercentage > 100, 'text-muted': totalPercentage <= 100 }">
                        <i>Remaining Value: {{ remainingValue }} ({{ remainingPercentage }}%)</i>
                      </small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Spacer to account for fixed section height -->
          <div class="spacer"></div>

          <!-- Data Table Section -->
          <section id="basic-datatable">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="col-6 offset-md-3">
                      <div class="alert alert-danger mt-3" v-if="errorMessage">
                        {{ errorMessage }}
                      </div>
                      <div class="alert alert-success mt-3" v-if="successMessage">
                        {{ successMessage }}
                      </div>
                    </div>
                    <div class="card-body card-dashboard">
                      <div class="table-responsive fixed-table-wrapper">
                        <table class="table table-bordered table-hover">
                          <thead class="table-light fixed-header">
                            <tr>
                              <th>Department Name</th>
                              <th>Objective</th>
                              <th>Contribution Value</th>
                              <th>Contribution (%)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(row, index) in items" :key="row.id">
                              <td>{{ row.name }}</td>
                              <td>
                                <input
                                  type="text"
                                  v-model="row.objective"
                                  class="form-control"
                                  placeholder="Objective"
                                />
                                <small v-if="rowErrors[`data.${index}.objective`]" class="text-danger">
                                  {{ rowErrors[`data.${index}.objective`][0] }}
                                </small>
                              </td>
                              <td>
                                <input
                                  type="number"
                                  v-model="row.contribution_value"
                                  @input="updatePercentage(row)"
                                  class="form-control"
                                  placeholder="00"
                                />
                              </td>
                              <td>
                                <input
                                  type="number"
                                  v-model="row.contribution_percentage"
                                  @input="updateValue(row)"
                                  class="form-control"
                                  placeholder="00%"
                                />
                              </td>
                            </tr>
                            <tr v-if="items.length < 1">
                              <td colspan="4" class="text-center text-muted">Data not found</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <button @click="saveChanges" class="btn btn-primary mt-3">Save Changes</button>
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
import axios from "../../axios_instance";

export default {
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      organization_revenue: 0,
      token: this.$localStorage.get("d_token"),
      items: [],
      errorMessage: "",
      successMessage: "",
      rowErrors: {}, // Holds validation errors for each row
    };
  },
  created() {
    this.getItems();
  },
  methods: {
    async getItems() {
      try {

        

      const revenueResponse = await axios.get(this.api_url + "organization-revenue", {
      headers: {
        "Content-Type": "application/json",
        Authorization: this.token ? `Bearer ${this.token}` : "",
      },
    });
    this.organization_revenue = revenueResponse.data.data.revenue;


        const { data } = await axios.get(this.api_url + "department-contributions", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        });
        this.items = data.data.map((item) => ({
          ...item,
          contribution_value: item.contribution_value || 0,
          contribution_percentage: item.contribution_percentage || 0,
        }));
      } catch (error) {
        console.error("Error fetching department contributions:", error);
      }


    },
    updatePercentage(row) {
      this.errorMessage = "";
      if (this.organization_revenue > 0) {
        row.contribution_percentage = ((row.contribution_value / this.organization_revenue) * 100).toFixed(2);
      }
      const totalPercentage = this.items.reduce((sum, item) => sum + parseFloat(item.contribution_percentage), 0);
      if (totalPercentage > 100) {
        this.errorMessage = "Total contribution percentage cannot exceed 100%";
        row.contribution_percentage = 0;
      }
    },
    updateValue(row) {
      this.errorMessage = "";
      row.contribution_value = ((row.contribution_percentage / 100) * this.organization_revenue).toFixed(2);
      const totalValue = this.items.reduce((sum, item) => sum + parseFloat(item.contribution_value), 0);
      if (totalValue > this.organization_revenue) {
        this.errorMessage = "Total contribution value cannot exceed organization revenue";
        row.contribution_value = 0;
      }
    },
    async saveOrganizationRevenue() {
      try {
        await axios.post(
          this.api_url + "organization-revenue/save_changes",
          {
            revenue: this.organization_revenue,
            revenue_year: new Date().getFullYear()
          },
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          }
        );
        this.successMessage = "Organization revenue updated successfully!";
      } catch (error) {
        this.errorMessage = "Failed to update organization revenue.";
      }
    },
    async saveChanges() {
      await this.saveOrganizationRevenue(); // Save organization revenue

      try {
        await axios.post(
          this.api_url + "department-contributions/save_changes",
          { data: this.items },
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          }
        );
        this.successMessage = "Changes saved successfully!";
        this.errorMessage = "";
        this.rowErrors = {}; // Clear any existing row errors
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.rowErrors = error.response.data.errors;
        } else {
          this.errorMessage = "Failed to save department contributions.";
        }
      }
    },
  },
  watch: {
    organization_revenue(newRevenue) {
      this.items.forEach((row) => {
        row.contribution_value = ((row.contribution_percentage / 100) * newRevenue).toFixed(2);
      });
    },
  },
  computed: {
    remainingValue() {
      const totalContribution = this.items.reduce((sum, item) => sum + parseFloat(item.contribution_value || 0), 0);
      return (this.organization_revenue - totalContribution).toFixed(2);
    },
    remainingPercentage() {
      if (this.organization_revenue > 0) {
        const totalContribution = this.items.reduce((sum, item) => sum + parseFloat(item.contribution_value || 0), 0);
        return ((this.organization_revenue - totalContribution) / this.organization_revenue * 100).toFixed(2);
      }
      return 0;
    },
    barColor() {
      return this.remainingPercentage > 100 ? 'red' : '#4caf50';
    },
  },
};
</script>

<style scoped>
.content-header-left {
  margin-bottom: 20px;
}

#organization-revenue .form-label {
  font-weight: bold;
}

.fixed-revenue-section {
  position: fixed;
  top: 60px;
  width: 100%;
  background-color: #ffffff;
  z-index: 1000;
  padding: 15px 0;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.spacer {
  height: 120px;
}

.bar-chart-container {
  margin-top: 15px;
  text-align: center;
}

.bar-chart {
  width: 100%;
  background-color: #e0e0e0;
  border-radius: 8px;
  height: 25px;
  overflow: hidden;
  margin-bottom: 10px;
}

.bar-fill {
  height: 100%;
  transition: width 0.5s ease;
}

.fixed-table-wrapper {
  max-height: 500px;
  overflow-y: auto;
  position: relative;
}

.fixed-header {
  position: sticky;
  top: 0;
  z-index: 10;
  background-color: #f8f9fa;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
}

.table-light {
  background-color: #f8f9fa;
}

.text-danger {
  color: red;
}
</style>
