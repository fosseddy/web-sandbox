<script>
import Icon from "@/icons/icon.vue";

export default {
    components: { Icon },

    props: {
    },

    emits: ["query-word"],

    data() {
        return {
            word: "",
            theme: "light",
            font: "Serif"
        };
    },

    computed: {
        themeIcon() {
            return this.theme == "light" ? "moon" : "sun";
        }
    },

    watch: {
        theme(cur, old) {
            document.body.classList.replace(
                `theme-picker--${old}`,
                `theme-picker--${cur}`
            );
        },

        font(cur, old) {
            document.body.classList.replace(
                `font-picker--${old}`,
                `font-picker--${cur}`
            );
        }
    },

    methods: {
    },

    created() {
        document.body.classList.add(`font-picker--${this.font}`);
        document.body.classList.add(`theme-picker--${this.theme}`);
    }
};
</script>

<template>
    <header class="dbg-r">
        <div class="settings-wrapper">
            <Icon name="logo" class="icon--md" />

            <div class="settings">
                <select v-model="font">
                    <option>Serif</option>
                    <option>Mono</option>
                    <option>Sans-Serif</option>
                </select>

                <div class="theme-picker">
                    <input type="checkbox"
                           v-model="theme"
                           true-value="dark"
                           false-value="light"
                    >
                    <Icon :name="themeIcon" />
                </div>
            </div>
        </div>

        <form @submit.prevent="$emit('query-word', word)">
            <input v-model="word">
            <Icon name="search" class="icon--xsm" />
        </form>
    </header>
</template>

<style scoped>
.settings-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.settings {
    display: flex;
    align-items: center;
}

.theme-picker {
    display: flex;
    align-items: center;
}
</style>

<style>
.theme-picker--light {
    --color-bg: white;
    --color-fg: black;
}

.theme-picker--dark {
    --color-bg: black;
    --color-fg: white;
}

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
