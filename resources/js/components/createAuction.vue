<template>
	
	<div class="container">
		<div class="row">
			<div class="col"></div>
			<div class="col-6">
				
				<form class="form-group" style="padding-top: 120px;" enctype="multipart/form-data">
					
					<alertBox :message="this.alert.message" :show="this.alert.show" :type="this.alert.type">
						
					</alertBox>	

					<h2 class="display-4 text-center">Create an Auction</h2>

					<label for="name">Name</label>
					<input type="text" class="form-control" required v-model="auction.name">
					<label for="description">Description</label>
					<textarea type="text" class="form-control" rows="4" cols="40" required v-model="auction.description"></textarea>
					<label for="min_price">Minimum Price</label>
					<input type="number" class="form-control" required v-model="auction.min_price">
					<br>
					<div class="custom-file">
					    <input type="file" class="custom-file-input" id="customFile" @change="onFileSelected">
					    <label class="custom-file-label" for="customFile">{{this.fileName}}</label>
				  	</div>
					<br>
					<br>
					<button type="button" @click.prevent="submit" class="btn btn-success form-control">
						Submit
					</button>

				</form>

			</div>
			<div class="col"></div>
		</div>
	</div>

</template>

<script type="text/javascript">
	
	import alertBox from './alertBox.vue';

	export default {

		components: {alertBox},

		data() {

			return {

				fileName : "Choose item's photo",

				auction : {

					name: "",
					description: "",
					min_price: 0,
					photo_url: null,

				},

				alert: {
					message: "",
					show: false,
					type: '',
				},

				api_token: this.$store.getters.getUserToken,

			}

		},

		methods: {

			onFileSelected(event){
				this.fileName = event.target.files[0].name;
				this.auction.photo_url = event.target.files[0];
			},

			checkForm(){

				this.auction.show = false;

				if(this.auction.name == ""){
					this.alert.message = "You must insert the name of your auction";
					return false;
				}

				if(this.auction.description == ""){
					this.alert.message = "You must insert the description of your auction";
					return false;
				}

				if(this.auction.min_price == ""){
					this.alert.message = "You must insert the minimum price of your auction";
					return false;
				}

				if(this.auction.photo_url == "" || this.auction.photo_url == null){
					this.alert.message = "You must insert an image for your photo";
				}

				return true;

			},

			sleep(milliseconds) {
			  	const date = Date.now();
			  	let currentDate = null;
			  	do {
			    	currentDate = Date.now();
			  	} while (currentDate - date < milliseconds);
			},

			submit(){

				if(this.checkForm()){

					let formData = new FormData();
					formData.append('file', this.auction.photo_url);
					formData.append('name', this.auction.name);
					formData.append('description', this.auction.description);
					formData.append('min_price', this.auction.min_price);
					formData.append('email', this.$store.state.user);

					axios.post('api/auction', formData,
						{headers: {'Authorization' : 'Bearer ' + this.api_token}})
					.then(response => {

						if(response.data.code == 400){
							this.alert.message = response.data.message;
							this.alert.type = "alert alert-danger";
							this.alert.show = true;
						}else{
							this.alert.message = response.data.message;
							this.alert.type = "alert alert-success";
							this.alert.show = true;
							Vue.toasted.show('Auction ' + this.auction.name + ' created!');
							console.log(response.data.message);
							this.sleep(2000);
							this.$router.push('/my-auctions')
						}

					})
					.catch(error => {
						console.log(error);						
					});

				}

				


			}

		}

	}

</script>

<style type="text/css" media="screen">
	
</style>