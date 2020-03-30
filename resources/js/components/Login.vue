<template>
    <div class="ibox login-content">
      <div class="text-center">
          <span class="auth-head-icon"><i class="la la-user"></i></span>
      </div>
      <div class="ibox-body" id="login-form">
          <h4 class="font-strong text-center mb-5">LOG IN</h4>
          <div class="form-group mb-4">
              <input class="form-control form-control-line pl-2" type="text" id="email" v-model="email" placeholder="Email">
          </div>
          <div class="form-group mb-4">
              <input class="form-control form-control-line pl-2" type="text" id="license" v-model="license" placeholder="License">
          </div>
          <div class="flexbox mb-5">
                <span>
                    <label class="ui-switch switch-icon mr-2 mb-0">
                        <input type="checkbox" v-model="rememberMe">
                        <span></span>
                    </label>
                    <span class="text-white">Remember</span>
                </span>
            </div>
          <div class="text-center mb-4">
              <button class="btn btn-primary btn-block" @click="login()">LOGIN</button>
          </div>
      </div>
  </div>
</template>

<script>
export default {
    name: 'Login',
    data () {
        return {
            email: "",
            license: "",
            rememberMe: true
        }
    },
    methods: {
        login: function() {
            if (!this.email || !this.license) {
                this.$toastr.warning('Please input your email and license.');
                return false;
            }
            if (this.rememberMe) {
                localStorage.setItem("license", this.license);
                localStorage.setItem("email", this.email);
            } else {
                localStorage.setItem("license", "");
                localStorage.setItem("email", "");
            }
            this.$store.dispatch('LOGIN_HANDLE', {user_email: this.email, license_key: this.license}).then(res => {
                localStorage.setItem("status", 1)
                this.$router.push('/search');
            }, err => {
                var data = err.response.data;
                this.$toastr.warning(data.message);
            })
        }
    },
    mounted () {
        document.body.className = 'AddBackGroundImage';
        this.email = localStorage.getItem("email");
        this.license = localStorage.getItem("license");
        let status = localStorage.getItem("status");
        if (this.email && this.license && status * 1 == 1) {
            this.login();
        }
    }
}
</script>
<style scoped>
.login-content {
    max-width: 400px;
    margin: 150px auto 50px;
    background-color: #00000085 !important;
}
.login_user:focus, #login_password:focus {
    outline: none;
}
.auth-head-icon {
    position: relative;
    height: 60px;
    width: 60px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    background-color: #fff;
    color: #5c6bc0;
    box-shadow: 0 5px 20px #d6dee4;
    border-radius: 50%;
    transform: translateY(-50%);
    z-index: 2;
}
.la {
    font: normal normal normal 16px/1 LineAwesome;
    font-size: inherit;
    text-decoration: inherit;
    text-rendering: optimizeLegibility;
    text-transform: none;
    -moz-osx-font-smoothing: grayscale;
    -webkit-font-smoothing: antialiased;
    font-smoothing: antialiased;
}
.la-user{
    background-image: url("data:image/png;base64,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");
    height: 31px;
    width: 31px;
}
.form-control {
    padding: 1.5rem 1.25rem;
}

</style>