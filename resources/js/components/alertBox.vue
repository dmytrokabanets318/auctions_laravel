<template>
	
	<div :class="type" role="alert" v-if="show">
		{{message}} 
		<div style="float: right; margin-top: -6px;">
			<button type="button" class="btn" @click="show = false">X</button>
		</div>
	</div>

</template>

<script type="text/javascript">
	
	export default {

		props: {

			message: String,
			show: Boolean,
			type: String,

		}


	}

</script>