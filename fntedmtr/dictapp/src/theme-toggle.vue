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
            document.body.classList.replace(`theme-${old}`, `theme-${cur}`);
        }
    },

    created() {
        document.body.classList.add(`theme-${this.theme}`);
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
body, main, input, select {
    transition: background-color 200ms linear;
}

hr {
    transition: border-color 200ms linear;
}

.theme-light {
    --color-bg: white;
    --color-fg: black;
    --color-primary: purple;
    --color-secondary: gray;
    --color-accent: #eee;
}

.theme-dark {
    --color-bg: black;
    --color-fg: white;
    --color-primary: purple;
    --color-secondary: gray;
    --color-accent: #2b2929;
}
</style>
