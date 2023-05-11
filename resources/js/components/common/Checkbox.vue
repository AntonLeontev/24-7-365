<template>
  <div class="form-check" :disabled="disabled">
	<label class="form-check-label lh-base cursor-pointer">
	  	<input 
			class="form-check-input fs-5" 
			type="checkbox" 
			:name="name" 
			:checked="checked" 
	  		:disabled="disabled"
		>
		<slot></slot>
	</label>
</div>
</template>

<script>
export default {
  name: "Test",
  created() {},
  data() {
    return {};
  },
  props: {
	disabled: {default: false},
	checked: {default: false},
	name: {String}
  },
  methods: {},
};
</script>

<style lang="scss" scoped></style>
