<template>
  <div class="container">
    <div class="row">
      <div class="col"></div>
      <div class="col-5" style="margin-top: 150px;">

        <form action="#" class="form-group">
          <h1 class="display-3 text-center">Login</h1>
          <label for="email">Email</label>
          <input type="email" required v-model="user.email" class="form-control">
          <label for="password">Password</label>
          <input type="password" required v-model="user.password" class="form-control">
          <br>
          <button class="btn btn-success form-control" @click.prevent="login">Login</button>
          <br>
          <AlertBox class="mt-3"
            :message="alert.message" 
            :type="alert.type" 
            :show="alert.show"
            @close="alert.show = false">
          </AlertBox>
          <p class="text-center mt-4">
            <router-link to="forgotPassword">
              <a href="#" title="ForgotPass"><h5>Forgot Password?</h5></a>
            </router-link>
          </p>
        </form>

      </div>
      <div class="col"></div>
    </div>
  </div>
</template>

<script>

  export default {

    components: {
      AlertBox: () => import('./alertBox'),
    },

    data(){
      return {

        user: {
          email : "",
          password: "",
        },
        alert: {
          message : "",
          type: "alert alert-danger",
          show: false,
        }

      }
    },

    methods: {

      login(){

        this.alert.show = false;

        if(this.user.name == ""){

          this.alert.message = "Please insert your credentials";
          this.alert.show = true;

        }else if(this.user.password == ""){

          this.alert.show = true;
          this.alert.message = "Please insert your credentials";

        }else {

          Vue.toasted.show("Loggin in...");
          this.alert.show = false;
          this.$axios.post('api/login', this.user)
          .then(response => {

            if(response.data.code == 401){
              this.alert.message = response.data.message;
              this.alert.show = true;
            }else{
              Vue.toasted.success("Logged in!");
              this.$store.commit('storeUserToken', response.data.token);
              this.$store.commit('storeUser', response.data.user);
              this.$store.commit('storeUserId', response.data.id);
              this.$router.push('/auctions');
            }


          })
          .catch(error => {

            this.alert.show = true;
            this.alert.message = "Error: " + error.response;
            console.log(error);

          });

        }

      }

    }

  }

</script>