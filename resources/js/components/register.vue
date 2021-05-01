<template>
	
	<div class="container">
		<div class="row">
			<div class="col"></div>
			<div class="col-6">
				<div class="form-group align-midle" style="margin-top: 100px;">

					<alertBox :message="alert.message" :type="alert.type" :show="alert.show"></alertBox>

					<form class="form-group">
						<h2 class="h2 text-center">Register</h2>
						<label for="name">Name</label>
						<input class="form-control" type="text" v-model="user.name" required></input>
						<label for="Email">Email</label>
						<input class="form-control" type="email" name="email" v-model="user.email" required>
						<label for="password">Password</label>
						<input class="form-control" type="password" name="password" v-model="user.password" required>
						<label for="passwordConfirmation">Confirm Password</label>
						<input class="form-control" type="password" v-model="confPass" required>
						<br>	
						<button class="btn btn-success form-control" type="button" @click.prevent="registerUser">Register</button>
					</form>
				</div>
			</div>
			<div class="col"></div>
		</div>
	</div>
</div>	

</template>

<script type="text/javascript">
	
	import alertBox from './alertBox.vue';

	export default {

		components: {

			alertBox,

		},

		data() {

			return {

				user: {

					name: "",
					password: "",
					email: "",

				},

				confPass: "",
				alert: {

					type: "alert alert-danger",
					show: false,
					message: "",

				},

			}
		},

		methods: {

			registerUser(){

				console.log(this.user);

				this.alert.show = false;

				if(this.confPass != this.user.password){

					this.alert.message = "Passwords must match!";
					this.alert.type = "alert alert-danger";
					this.alert.show = true;

				}else{
					this.$toasted.show('Creating your account...');
					this.$axios.post('api/register', this.user)
					.then(response => {

						if(response.data.code != 200){
							this.alert.message = response.data.message;
							this.alert.show = true;
						}else{
							Vue.toasted.show(response.data.message);
							this.$router.push('/login');
						}

					})
					.catch(error => {
						this.alert.show = true;
						this.alert.type = "alert alert-danger";
					})
				}


			},

		}

	}

</script>