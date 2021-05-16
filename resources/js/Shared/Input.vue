<template>
    <div>
        <label v-if="label" class="form-label uppercase" :for="id">{{ label }}</label>

        <input :id="id"
               ref="input"
               class="form-input"
               :class="{ error: error }"
               :type="type"
               :value="value"
               @input="$emit('input', $event.target.value)" />
        <div v-if="error" class="form-error">{{ error }}</div>
        <p>{{ emailValidation }}</p>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true
        },
        type: {
            type: String,
            default: 'text',
        },
        value: String,
        label: String,
        error: String,
    },
    emits: ['input'],
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
    },
}
</script>
