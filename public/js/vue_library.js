
//<my-currency-input :name="'cost_temp'" v-model="cost_temp"></my-currency-input>
Vue.component('my-currency-input', {
	props: ["value", "name"],
	template: `
		<div>
			<input :name="name" type="text" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true" required/>
		</div>`,
	data: function() {
		return {
			isInputActive: false
		}
	},
	computed: {
		displayValue: {
			get: function() {
				if (this.isInputActive) {
					// Cursor is inside the input field. unformat display value for user
					return this.value.toString()
				} else {
					// User is not modifying now. Format display value for user interface
					return parseFloat(this.value).toFixed(2).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
				}
			},
			set: function(modifiedValue) {
				// Recalculate value after ignoring "$" and "," in user input
				let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""))
				// Ensure that it is not NaN
				if (isNaN(newValue)) {
					newValue = 0
				}
				this.$emit('input', newValue);
			}
		}
	}
});