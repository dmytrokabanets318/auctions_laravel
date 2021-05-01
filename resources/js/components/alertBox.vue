<template>
	
	<div :class="[type, animation]" role="alert" v-if="show" >
		{{message}} 
		<div style="float: right; margin-top: -6px;">
			<button type="button" class="btn" @click="close()">X</button>
		</div>
	</div>

</template>

<script type="text/javascript">
	
	export default {
		props: {
			message: String,
			show: Boolean,
			type: String,
			animation: String,
		},
		methods: {
			close(){
				this.$emit('closing');
			}
		}
	};
</script>