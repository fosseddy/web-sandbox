<script>
export default {
    data() {
        return {
            font: "Serif"
        };
    },

    watch: {
        font(cur, old) {
            document.body.classList.replace(
                `font-picker--${old}`,
                `font-picker--${cur}`
            );
        }
    },

    created() {
        document.body.classList.add(`font-picker--${this.font}`);
    }
};
</script>

<template>
    <select v-model="font">
        <option>Serif</option>
        <option>Mono</option>
        <option>Sans-Serif</option>
    </select>
</template>

<style scoped>
</style>

<style>
.font-picker--Serif {
    font-family: serif;
}

.font-picker--Mono {
    font-family: monospace;
}

.font-picker--Sans-Serif {
    font-family: sans-serif;
}
</style>
