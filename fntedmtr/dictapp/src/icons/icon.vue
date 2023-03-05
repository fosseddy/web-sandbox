<script>
import Search from "./search.vue";
import Logo from "./logo.vue";
import Moon from "./moon.vue";
import Sun from "./sun.vue";

export default {
    components: { Search, Logo, Moon, Sun },

    props: {
        name: { type: String, required: true }
    }
};
</script>

<template>
    <component :is="name" class="icon"></component>
</template>

<style>
.icon {
    width: 24px;
    height: 24px;
    stroke-width: 1px;
    stroke: black;
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.icon--xsm {
    width: 16px;
    height: 16px;
}

.icon--sm {
    width: 24px;
    height: 24px;
}

.icon--md {
    width: 48px;
    height: 48px;
}

.icon--lg {
    width: 96px;
    height: 96px;
}
</style>
