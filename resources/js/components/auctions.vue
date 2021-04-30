<template>
	
	<div>

		<div class="container" v-if="hasAuctions">

			<div class="row">
				<div class="col"></div>
				<div class="col-8">
					<h2 class="display-2 text-center">Auctions</h2>
					<div class="input-group" style="border-radius: 50px;">
						<input class="form-control"
						type="text"  
						name="searchAuction" @keyup="searchAuction()" 
						placeholder="Search..." v-model="search">
						<div class="input-group-append">
							<div class="dropdown">
								<button class="btn btn-light border border-gray dropdown-toggle" 
								type="button" id="dropdownMenuButton" data-toggle="dropdown" 
								aria-haspopup="true" aria-expanded="false" style="border-radius: 0px;">
								Order By : {{orderBy.selected}}
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item" style="padding-right: 80px;" href="#" 
								@click="orderButton(item)" v-for="item in orderBy.option">{{item.name}}</a> 
							</div>
						</div>
					</div>
				</div>

				<div class="mt-5 jumbotron" v-if="!hasSearchedAuctions">
					<h4 class="display-4">There are no auctions with the name "{{this.search}}"</h4>
				</div>

			</div>
			<div class="col"></div>
		</div>

		
		<div class="row">
			
			<div class="col-md-4" v-for="auction in auctions">
				
				<div class="card mt-5" style="border-radius: 10px;">

					<img class="card-img-top" width="300" height="300" :src="'storage\\upload\\' + auction.photo_url" 
					alt="Auction image" style="border-radius-top: 10px;"></img>

					<div class="card-body">
						<h5 class="card-title">{{auction.name}}</h5>
						<p class="card-text">{{auction.description}}</p>
						<p>Start date: {{auction.start}}</p>
						<p>Minimum price: {{auction.min_price}}€</p>
						<p v-if="auction.last_bid_price != null || auction.last_bid_price != 0">
							Last bid price: {{auction.last_bid_price}}€
						</p>
						
						<div class="input-group mb-3" v-if="user">
							<div v-if="auction.last_bid_price != 0">
								<input type="text" class="form-control" v-model="auction.bid_price" :placeholder="'Bid price: ' + auction.last_bid_price + '€'">
							</div>
							<div v-else>
								<input type="text" class="form-control" v-model="auction.bid_price" :placeholder="'Bid price: ' + auction.min_price + '€'">
							</div>
							<div class="input-group-append">
								<button class="btn btn-outline-secondary"
								@click="bidAuction(auction, auction.bid_price)" type="button">Bid</button>
							</div>
						</div>
						<div v-show="auction.bidded">
							<alertBox :message="alert.message" 
							:type="alert.type" :show="alert.show">
						</alertBox>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="container" v-else>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">
			
			<div class="jumbotron" style="margin-top: 250px;">
				<h1>It appears there are no auctions active...</h1>
				<!-- TODO later -> use v-if to determine if user is logged in -->
				<h4 v-if="this.$store.state.user == ''">Login or register to start one!</h4>
				<div v-else class="text-center"> 
					<h3>Create one yourself!</h3>
					<br>
					<router-link to="create-auction">
						<button type="button" class="btn btn-primary">Create Auction</button>
					</router-link>	
				</div>
			</div>	
			
		</div>
		<div class="col"></div>
	</div>
</div>	

</div>

</template>

<script>
	
	import alertBox from './alertBox.vue';

	export default {

		data() {

			return {

				auctions: [],
				hasAuctions: false,
				search: "",
				hasSearchedAuctions: true,
				alert: {
					message: "",
					type: "",
					show: false
				},
				user: this.$store.getters.getUser,
				api_token: this.$store.getters.getUserToken,
				orderBy: {
					option: [
					{name: 'Earliest', data: 'start desc'}, 
					{name: 'Oldest', data: 'start asc'},
					{name: 'Name Asc', data: 'name asc'},
					{name: 'Name Desc', data: 'name desc'},
					{name: 'Price (Lowest to Highest)', data: 'last_bid_price asc'},
					{name: 'Price (Highest to Lowest)', data: 'last_bid_price desc'},
					],
					selected: "Earliest",
				}

			}

		},

		methods: {

			getAuctions(order){
				this.hasSearchedAuctions = true;
				this.$axios.get('/api/auctions?orderBy='+order)
				.then(response => {
					this.auctions = response.data;
					this.hasAuctions = true;
				})
				.catch(error => {
					console.log(error);
				});
			},

			bidAuction(auction, bidPrice){
				this.alert.show = false;
				let email = this.$store.state.user;
				this.$axios.put('/api/auction/' + auction.id, {email, bidPrice},
					{headers: {'Authorization' : 'Bearer ' + this.api_token}})
				.then(response => {
					this.alert.message = response.data.message;
					auction.bidded = true;
					if(response.data.code != 200){
						this.alert.type = "alert alert-danger";
					}else{
						this.alert.type = "alert alert-success";
						auction.last_bid_price = bidPrice;
						//this.getAuctions();
					}

					this.alert.show = true;

				})
				.catch(error => {
					console.log(error);
				});

			},

			searchAuction(order){

				if(order === undefined){
					for(var i=0; i<this.orderBy.option.length; i++){
						console.log(this.orderBy.option);
						if(this.orderBy.option[i].name == this.orderBy.selected){
							order = this.orderBy.option[i].data;
							console.log(order);
						}
					}
				}

				if(this.search != ''){
					this.$axios.get('api/searchAuction?search='+this.search+'&orderBy='+order)
					.then(response => {

						if(response.data.code != 206){
							this.hasSearchedAuctions = true;
							this.auctions = response.data;
						}else{
							this.hasSearchedAuctions = false;
							this.auctions = null;
						}

					}).catch(error => {
						console.log(error);
					}); 

				}else{
					this.getAuctions(order);
				}
				
			},

			orderButton(order){

				this.orderBy.selected = order.name;

				if(this.search != ''){
					this.searchAuction(order.data);
				}else{
					this.getAuctions(order.data);
				}

			}

		},

		mounted(){
			this.getAuctions();
		}

	};

</script>

<style type="text/css" media="screen">
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
	/* display: none; <- Crashes Chrome on hover */
	-webkit-appearance: none;
	margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

input[type=number] {
	-moz-appearance:textfield; /* Firefox */
}
</style>