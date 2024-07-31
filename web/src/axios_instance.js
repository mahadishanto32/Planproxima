
const axios = require("axios");
const defaultOptions = { 
    baseURL: '', 
    headers: {
        "Content-Type": "application/json",
        "Usertimezone": window.timezone,
        "Latitude": '',
        "Longitude": '',
        "App": 'public',
    }
};

let axiosInstance = axios.create(defaultOptions);
axiosInstance.interceptors.request.use(function(config) { 
    return config;
    //let token = localStorageService.getItem("d_token");
    // if (this.$localStorage.get("d_token")) {
    //     let token = this.$localStorage.get("d_token");
    //     let apptype = this.$localStorage.get("user_role");
    //     config.headers.Authorization = token ? `Bearer ${token}` : "";
    //     config.headers.App = apptype ? apptype : "public";
    //     return config;
    // }

});

// Response interceptor
// axiosInstance.interceptors.response.use(response => {
//     // Check response for authentication status or errors
//     console.error('API Error:here check-222', error);
//     return response;
//   }, error => {
//     // Handle errors globally
//     console.error('API Error:here check', error);
//     return Promise.reject(error);
//   });


axiosInstance.interceptors.response.use(
    response => {
      // Check response for authentication status or errors
    //   console.log('API Response:', response);
      return response;
    },
    error => {
      // Handle errors globally
    //   
  
      // Handle specific error status codes
      if (error.response && error.response.status === 401) {
        // console.error('API Error ---------:', error);
        // Redirect to login page or perform other actions
        // For example:
        window.location.href = '/login';
      }
  
      return Promise.reject(error);
    }
  );

export default axiosInstance;