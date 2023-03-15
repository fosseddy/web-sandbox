<script>
export default {
    components: {},

    data() {
        return {
            transition: "slide-right",
            tabnum: 4,
            tab: 1
        };
    },

    computed: {
    },

    watch: {
    },

    methods: {
        nextTab() {
            if (this.tab < this.tabnum) {
                this.tab += 1;
                this.transition = "slide-right";

            }
        },

        prevTab() {
            if (this.tab > 1) {
                this.tab -= 1;
                this.transition = "slide-left";
            }
        },

        submit() {
            console.log("submitting data...");
        }
    }
};
</script>

<template>
    <header>
        <ul>
            <li v-for="n in 4" :key="n" :class="{active: n == tab}">{{n}}</li>
        </ul>
    </header>

    <main>
        <Transition :name="transition">
            <section v-if="tab == 1">
                <h2>Personal info</h2>
                <p>Please provide your name, email, address, and phone number.</p>
                <form>
                    <div>
                        <label for="name">Name</label>
                        <input id="name">
                    </div>
                    <div>
                        <label for="email">Email Address</label>
                        <input id="email">
                    </div>
                    <div>
                        <label for="phone">Phone Number</label>
                        <input id="phone">
                    </div>
                </form>
            </section>

            <section v-else-if="tab == 2">
                <h2>Select your plan</h2>
                <p>You have the option of monthly or yearly billing.</p>
                <form>
                    <div>
                        <input id="arcade" type="checkbox">
                        <label for="arcade">Arcade</label>
                        <p>$9/mo</p>
                    </div>
                    <div>
                        <input id="advanced" type="checkbox">
                        <label for="advanced">Advanced</label>
                        <p>$12/mo</p>
                    </div>
                    <div>
                        <input id="pro" type="checkbox">
                        <label for="pro">Pro</label>
                        <p>$15/mo</p>
                    </div>
                </form>
            </section>

            <section v-else-if="tab == 3">
                <h2>Tab 3</h2>
                <p>This is Tab 3.</p>
            </section>

            <section v-else>
                <h2>Tab 4</h2>
                <p>This is Tab 4.</p>
            </section>
        </Transition>
    </main>

    <footer>
        <div>
            <button v-if="tab > 1" @click="prevTab">Go Back</button>
        </div>
        <button v-if="tab < tabnum" @click="nextTab">Next Step</button>
        <button v-else @click="submit">Confirm</button>
    </footer>
</template>

<style scoped>
main {
    background: gray;
}

header {
    background: red;
    padding: 1rem;
}

section {
    background: white;
    padding: 1rem;
    position: relative;
    top: -3rem;
    left: 1rem;
    right: 1rem;
}

footer {
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.active {
    background: yellow;
}

.slide-right-enter-active,
.slide-right-leave-active,
.slide-left-enter-active,
.slide-left-leave-active {
    transition: transform 300ms ease;
}

.slide-right-enter-from,
.slide-left-leave-to {
    transform: translateX(calc(100% + 5rem));
}

.slide-right-leave-to,
.slide-left-enter-from {
    transform: translateX(calc(-100% - 5rem));
}
</style>

<!-- Global Styles -->
<style>
*,
*::after,
*::before {
    box-sizing: border-box;
}

body {
    margin: 0;
}

label {
    display: block;
}

html,
body,
#app {
    height: 100%;
}

#app {
    display: grid;
    grid-template-rows: 10rem 1fr 4rem;
    overflow-x: hidden;
}
</style>
