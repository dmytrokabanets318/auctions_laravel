<template>
  <nav id="myNav" class="navbar navbar-light bg-light">
    <a class="navbar-brand" @click="$router.push('/auctions')" href="#"
      >Auctions</a
    >
    <div>

			<div v-if="!auth">
				<router-link class="ml-2" to="/login">
					<a href="#" class="navbar-brand btn btn-light">Login</a>
				</router-link>
				<router-link to="/register">
					<a href="#" class="navbar-brand btn btn-light">Register</a>
				</router-link>
			</div>

			<div v-if="auth">
      <router-link to="/wallet">
        <a href="#" class="navbar-brand btn btn-light">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            fill="currentColor"
            class="bi bi-wallet2"
            viewBox="0 0 16 16"
          >
            <path
              d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z"
            />
          </svg>
          My Wallet: {{ wallet.balance }} €
        </a>
      </router-link>

      <router-link to="/my-auctions">
        <a href="#" class="navbar-brand btn btn-light">My Auctions</a>
      </router-link>

      <a href="#" class="navbar-brand btn btn-light" @click="logout">Logout</a>

			</div>
    </div>
  </nav>
</template>

<script type="text/javascript">

import {mapGetters} from 'vuex';

export default {
  data() {
    return {
      search: "",
    };
  },

	computed: {
		...mapGetters({
			auth : 'getUser',
      wallet: 'getWallet'
		})
	},

  methods: {
    logout() {

      this.$store.commit("revokeUser");
      this.$store.commit("revokeUserToken");
      this.$store.commit("revokeWallet");

      this.$axios
        .post("/api/logout")
        .then((response) => {
          this.$toasted.show("Logging out...");
          this.$router.go();
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>