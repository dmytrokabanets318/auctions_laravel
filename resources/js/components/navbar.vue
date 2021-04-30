<template>
	
    <nav id="myNav" class="navbar navbar-light bg-light">
        <a class="navbar-brand" @click="$router.push('/auctions') " href="#">Auctions</a>
        <div>

            <router-link class="ml-2" v-if="this.$store.state.user == ''" to="/login">
                <a href="#" class="navbar-brand btn btn-light">Login</a>
            </router-link>
            <router-link v-if="this.$store.state.user == ''" to="/register">
                <a href="#" class="navbar-brand btn btn-light">Register</a>
            </router-link>

            <router-link v-if="this.$store.state.user != ''" to="/my-auctions">
                <a href="#" class="navbar-brand btn btn-light">My Auctions</a>
            </router-link>

                <!-- Make this logout button perform an axios post to 
                    directly logout without chaning components -->
            <a href="#" v-if="this.$store.state.user != ''"
                class="navbar-brand btn btn-light" @click="logout">Logout</a>
        
        </div>
    </nav>

</template>

<script type="text/javascript">
	
	export default {

		data(){

			return{
                search: "",
			}

		},

		methods: {

			logout(){

                this.$store.commit('revokeUser');
                this.$store.commit('revokeUserToken');

                this.$axios.post('/api/logout')
                .then(response => {
                    console.log("gotGere");
                    this.$router.go();
                })
                .catch(error => {
                    console.log(error);
                });

            },

		}

	}

</script>