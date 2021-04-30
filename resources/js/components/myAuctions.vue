<template>
	
	<div class="container">

		<div class="row">
			<div class="col"></div>
			<div class="col-10">
				
				<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
					
					<ul class="nav nav-tabs">
					  	<li class="nav-item">
					    	<a :class="'nav-link ' + this.allAuctions + ' btn'"
					    		@click="showAllAuctions()"
					    		data-toggle="collapse" :data-target="'#confirmation-' + this.auctionToClose" 
					    		aria-expanded="false" aria-controls="confirmation">All Auctions</a>
					  	</li>
					  	<li class="nav-item">
					    	<a :class="'nav-link ' + this.activeAuctions + ' btn'"
					    		@click="showActiveAuctions()"
					    		data-toggle="collapse" :data-target="'#confirmation-' + this.auctionToClose" aria-expanded="false" aria-controls="confirmation">Active Auctions</a>
					  	</li>
					  	<li class="nav-item">
					    	<a :class="'nav-link ' + this.closedAuctions + ' btn'"
					    		@click="showClosedAuctions()"
					    		data-toggle="collapse" :data-target="'#confirmation-' + this.auctionToClose" aria-expanded="false" aria-controls="confirmation">Closed Auctions</a>
					  	</li>
				  		<li class="nav-item">
				  			<a :class="'nav-link ' + this.wonAuctions + ' btn'" 
				  				@click="showWonAuctions()"
				  				data-toggle="collapse" :data-target="'#confirmation-' + this.auctionToClose" aria-expanded="false" aria-controls="confirmation">Won Auctions</a>
				  		</li>
				  		<li class="nav-item">
				  			<a :class="'nav-link ' + this.biddedAuctions + ' btn'" 
				  				@click="showBiddedAuctions()"
				  				data-toggle="collapse" :data-target="'#confirmation-' + this.auctionToClose"  aria-expanded="false" aria-controls="confirmation">Bidded Auctions</a>
				  		</li>
				  	</ul>

				  	<ul class="navbar-nav"> 
					  	<li class="nav-item" style="float: right;">
					  		<router-link to="/create-auction">
					    		<a href="#" class="btn btn-primary">Create Auction</a>
					    	</router-link>
					  	</li>
				  	</ul>

				</nav>

			</div>
			<div class="col"></div>

		</div>

		<div class="row mt-3" v-if="hasAuctions" v-for="auction in auctions" >
			<div class="col"></div>
			<div class="col-8">
				<div class="card">
				  	<div class="card-header">
				  		<h5 class="mt-2"><strong>{{auction.name}}</strong></h5>
				 	</div>
				  	<div class="card-body">

				    	<p class="card-text">{{auction.description}}</p>
				    	<img class="border border-gray border-rounded" 
				    		style="float: right; position: relative;"
				    		:src="'storage\\upload\\' + auction.photo_url" 
				    		alt="Auction image" width="200" height="150">
				    	
				    	Start date: {{auction.start}}
				    	<span class="border border-danger rounded ml-3" v-if="auction.end != null">End date: {{auction.end}}</span>
				    	<br>
				    	Minumum price: {{auction.min_price}}
				    	<br>
						<p v-if="auction.last_bid_price != null">Last bid: {{auction.last_bid_price}}</p>
						
						<div v-if="canClose">
							<button type="button" class="btn btn-danger" v-if="auction.end == null"
								data-toggle="collapse" :data-target="'#confirmation-' + auction.id" aria-expanded="false" aria-controls="confirmation" @click="auctionToClose = auction.id">
								Close Auction
							</button>
						</div>

						<br>
						<br>
						<br>
						<div class="collapse" :id="'confirmation-' + auction.id">
							<div class="container">
								<h3>Are you sure you want to close the auction?</h3>

								<button type="button" 
									class="btn btn-success" @click="closeAuction(auction)" 
									style="margin-left: 20px; padding-left: 100px; padding-right: 100px;"
									data-toggle="collapse" 
									:data-target="'#confirmation-' + auction.id">
										Yes
								</button>

								<button type="button" class="btn btn-danger" data-toggle="collapse" 
									:data-target="'#confirmation-' + auction.id" style="padding-left:100px; padding-right: 100px;" @click="console.log(auctionToClose)">
										No
								</button>
								
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col"></div>
		</div>

		<div class="jumbotron row mt-3" v-if="!hasAuctions">
			<h4 class="display-4">{{this.message}}</h4>
		</div>

	</div>


</template>

<script type="text/javascript">
	
	export default {

		data() {

			return {

				auctions: [],
				hasAuctions: false,
				allAuctions: "active",
				activeAuctions: "",
				closedAuctions: "",
				wonAuctions: "",
				biddedAuctions: "",
				message: "",
				canClose: true,
				auctionToClose: "",
				remember_token: this.$store.getters.getUserToken,

			}

		},

		methods: {

			getUserAuctions(type){

				let url = 'api/auctions/' + this.$store.state.user + '?set='+type;

				this.$axios.get(url, 
					{headers: {'Authorization' : 'Bearer ' + this.remember_token}})
				.then(response => {

					if(response.data.code == 206){
						this.auctions = null;
						this.hasAuctions = false;
						this.message = response.data.message;
					}else{
						this.auctions = response.data.auctions;
						this.hasAuctions = true;
						this.message = "";
						this.canClose = response.data.close;
						this.auctionToClose = "";
					}				

				})
				.catch(error => {
					console.log(error);
				})

			},

			showAllAuctions(){
				this.allAuctions = "active";
				this.activeAuctions = "";
				this.closedAuctions = "";
				this.wonAuctions = "";
				this.biddedAuctions = "";
				this.getUserAuctions('all');
			},

			showActiveAuctions(){
				this.allAuctions = "";
				this.activeAuctions = "active";
				this.closedAuctions = "";
				this.wonAuctions = "";
				this.biddedAuctions = "";
				this.getUserAuctions('active');
			},

			showClosedAuctions(){
				this.allAuctions = "";
				this.activeAuctions = "";
				this.closedAuctions = "active";
				this.wonAuctions = "";
				this.biddedAuctions = "";
				this.getUserAuctions('closed');
			},

			showWonAuctions(){

				this.allAuctions = "";
				this.activeAuctions = "";
				this.closedAuctions = "";
				this.wonAuctions = "active";
				this.biddedAuctions = "";
				this.getUserAuctions('won');

			},

			showBiddedAuctions(){

				this.allAuctions = "";
				this.activeAuctions = "";
				this.closedAuctions = "";
				this.wonAuctions = "";
				this.biddedAuctions = "active";
				this.getUserAuctions('bidded');

			},

			closeAuction(auction){
				let email = this.$store.state.user;
				this.$axios.put('/api/auction/'+auction.id+'/close', {email}, 
					{headers: {'Authorization' : 'Bearer ' + this.remember_token}})
				.then(response => {
					console.log(response);
					auction.end = true;
					Vue.toasted.show(response.message);
					this.getUserAuctions();
				}).catch(error => {
					console.log(error);
				});
			}

		},

		mounted() {

			this.getUserAuctions();

		}

	}

</script>