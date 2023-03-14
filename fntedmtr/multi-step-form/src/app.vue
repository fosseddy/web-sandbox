<script>
export default {
    components: {},

    data() {
        return {
            tabnum: 4,
            tab: 1,
            name: "",
            email: "",
            phone: ""
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
            }
        },

        prevTab() {
            if (this.tab > 1) {
                this.tab -= 1;
            }
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
        <section :class="{'tab-left': tab > 1 }">
            <h2>Personal info</h2>
            <p>Please provide your name, email, address, and phone number.</p>
            <form>
                <div>
                    <label for="name">Name</label>
                    <input id="name" v-model="name">
                </div>
                <div>
                    <label for="email">Email Address</label>
                    <input id="email" v-model="email">
                </div>
                <div>
                    <label for="phone">Phone Number</label>
                    <input id="phone" v-model="phone">
                </div>
            </form>
        </section>
        <section :class="{'tab-right': tab < 2, 'tab-left': tab > 2 }">
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
        <section :class="{'tab-right': tab < 3, 'tab-left': tab > 3 }">
            <h2>Tab 3</h2>
            <p>This is Tab 3.</p>
        </section>
        <section :class="{'tab-right': tab < 4}">
            <h2>Tab 4</h2>
            <p>This is Tab 4.</p>
        </section>
    </main>

    <footer>
        <button @click="prevTab" :class="{hidden: tab == 1}">Go Back</button>
        <button @click="nextTab">Next Step</button>
    </footer>
</template>

<style scoped>
main {
    background: gray;
    position: relative;
}

header {
    background: red;
    padding: 1rem;
}

section {
    background: white;
    padding: 1rem;
    position: absolute;
    top: -3rem;
    left: 1rem;
    right: 1rem;
    transition: transform 350ms ease;
}

footer {
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.hidden {
    visibility: hidden;
}

.active {
    background: yellow;
}

.tab-right {
    transform: translateX(calc(100% + 1rem));
}

.tab-left {
    transform: translateX(calc(-100% - 1rem));
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
    overflow-x: hidden;
}

#app {
    display: grid;
    grid-template-rows: 10rem 1fr 4rem;
}
</style>
