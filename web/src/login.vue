<template>
  <div id="app">
    <div class="row mt-5">
      <div class="col-12 col-sm-4 col-md-3 mx-auto">
        <div class="card cus_card mt-3">
          <div class="row">
            <div class="col-12 mx-auto">
              <div class="image_area text-center">
                <img
                  src="@/assets/cus_logo.png"
                  alt="Image"
                  class="img-fluid custom_logo"
                />
              </div>
              <h2 class="text-center"><b>Plan Proxima</b></h2>
            </div>
          </div>
          <div class="card-body">
            <form @submit.prevent="login()">
              <div class="form-group mb-50">
                <label for="exampleInputEmail1"
                  >Email address/Employee ID</label
                >
                <input
                  type="text"
                  name="email"
                  v-model="loginForm.email"
                  :class="{ 'is-invalid': loginForm.errors.has('email') }"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Email address/Employee ID"
                />
              </div>
              <div class="form-group">
                <label class="text-bold-600" for="exampleInputPassword1"
                  >Password</label
                >
                <input
                  type="password"
                  name="password"
                  v-model="loginForm.password"
                  :class="{ 'is-invalid': loginForm.errors.has('password') }"
                  class="form-control"
                  id="exampleInputPassword1"
                  placeholder="Password"
                />
              </div>
              <div
                class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center"
              >
                <div class="text-right">
                  <a class="card-link" @click="poup()"
                    ><small>Reset New Password?</small></a
                  >
                </div>
              </div>
              <button
                type="submit"
                class="btn btn-primary glow w-100 position-relative"
              >
                Login<i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
#app {
  margin: 0;
  padding: 0;
  overflow: hidden;
  font-family: 'Open Sans', sans-serif !important;
}
.custom_logo {
  width: 150px;
}
.image_area {
  padding: 2rem 0;
}
.cus_card {
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px !important;
  min-width: 420px !important;
}
</style>
<script>
import Image from '@/assets/cus_logo.png'
import { Form } from 'vform'
export default {
  name: 'App',
  base_url: window.base_url,
  components: {},
  data() {
    return {
      imageUrl: Image,
      images: [
        'https://cdn.pixabay.com/photo/2015/12/12/15/24/amsterdam-1089646_1280.jpg',
        'https://cdn.pixabay.com/photo/2016/02/17/23/03/usa-1206240_1280.jpg',
        'https://cdn.pixabay.com/photo/2016/12/04/19/30/berlin-cathedral-1882397_1280.jpg',
        'https://cdn.pixabay.com/photo/2016/02/17/23/03/usa-1206240_1280.jpg',
      ],
      timer: null,
      currentIndex: 0,
      api_url: window.api_url,
      base_url: window.base_url,
      step: 1,
      token: this.$localStorage.get('d_token'),
      loginForm: new Form({
        email: '',
        password: '',
      }),
      forget: new Form({
        email: '',
        code: '',
        password: '',
        confirm_password: '',
      }),
    }
  },
  mounted: function () {
    this.startSlide()
  },
  methods: {
    startSlide: function () {
      this.timer = setInterval(this.next, 4000)
    },

    next: function () {
      this.currentIndex += 1
    },
    prev: function () {
      this.currentIndex -= 1
    },
    poup() {
      this.$modal.show('popup-singel')
    },
    hide_pop() {
      this.step = 1
      this.$modal.hide('popup-singel')
    },
    forgotPassword() {
      console.log(this.forget)

      let loader = this.$loading.show()
      this.forget
        .post(this.api_url + 'auth/mailcheck', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(
          (res) => {
            if (res.data.status == 1) {
              this.$toasted.show(res.data.message, {
                theme: 'outline',
                duration: 5000,
                position: 'top-right',
              })
              this.step = 2
            }
            if (res.data.status == 0) {
              this.$toasted.show(res.data.message, {
                theme: 'bubble',
                duration: 5000,
                position: 'bottom-right',
              })
            }
            loader.hide()
          },
          (error) => {
            console.log(error)
            loader.hide()
          }
        )
    },
    forgotPasswordCode() {
      console.log(this.forget)

      let loader = this.$loading.show()
      this.forget
        .post(this.api_url + 'auth/mailcheck_code', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(
          (res) => {
            if (res.data.status == 1) {
              this.step = 3
              this.$toasted.show(res.data.message, {
                theme: 'outline',
                duration: 5000,
                position: 'top-right',
              })
            }
            if (res.data.status == 0) {
              this.$toasted.show(res.data.message, {
                theme: 'bubble',
                duration: 5000,
                position: 'bottom-right',
              })
            }
            loader.hide()
          },
          (error) => {
            console.log(error)
            loader.hide()
          }
        )
    },
    forgotPasswordNewPass() {
      console.log(this.forget)

      let loader = this.$loading.show()
      this.forget
        .post(this.api_url + 'auth/reset_password', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(
          (res) => {
            if (res.data.status == 1) {
              this.hide_pop()
              //this.step = 3 ;
              this.$toasted.show(res.data.message, {
                theme: 'outline',
                duration: 5000,
                position: 'top-right',
              })
            }
            if (res.data.status == 0) {
              this.$toasted.show(res.data.message, {
                theme: 'bubble',
                duration: 5000,
                position: 'bottom-right',
              })
            }
            loader.hide()
          },
          (error) => {
            console.log(error)
            loader.hide()
          }
        )
    },
    login() {
      try {
        let loader = this.$loading.show()
        this.loginForm
          .post(this.api_url + 'auth/login', {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(
            (res) => {
              if (res.data.status == 1) {
                this.$toasted.show(res.data.message, {
                  theme: 'outline',
                  duration: 5000,
                  position: 'top-right',
                })
                this.$localStorage.set('d_token', res.data.access_token)
                this.$localStorage.set('user', JSON.stringify(res.data.user))

                // console.log(res.data.user);
                this.$router.push('/home/d')
                //this.$router.go("/home/l");
              }
              loader.hide()
            },
            (error) => {
              console.log(error)
              loader.hide()
            }
          )
      } catch (error) {
        // loader.hide();
        console.log(error)
      }
    },
  },
  computed: {
    currentImg: function () {
      return this.images[Math.abs(this.currentIndex) % this.images.length]
    },
  },
  created() {},
}
</script>

<style>
input.form-control.input_custom_2.is-invalid {
  border-color: #f00;
}
</style>
