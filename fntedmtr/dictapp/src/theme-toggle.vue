<script>
import Icon from "@/icons/icon.vue";

export default {
    components: { Icon },

    data() {
        return {
            theme: "light"
        };
    },

    computed: {
        themeIcon() {
            return this.theme == "light" ? "moon" : "sun";
        }
    },

    watch: {
        theme(cur, old) {
            document.body.classList.replace(`theme--${old}`, `theme--${cur}`);
        }
    },

    created() {
        document.body.classList.add(`theme--${this.theme}`);
    }
};
</script>

<template>
    <div class="theme-picker">
        <input type="checkbox"
               v-model="theme"
               true-value="dark"
               false-value="light"
        >
        <Icon :name="themeIcon" />
    </div>
</template>

<style scoped>
.theme-picker {
    display: flex;
    align-items: center;
}
</style>

<style>
.theme--light {
    --color-bg: white;
    --color-fg: black;
}

.theme--dark {
    --color-bg: black;
    --color-fg: white;
}
</style>
