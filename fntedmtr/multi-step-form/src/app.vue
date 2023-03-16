<script>
export default {
    data() {
        return {
            transition: "slide-right",
            tabamout: 4,
            tab: 1
        };
    },

    methods: {
        goto(index) {
            if (index > this.tabamout) {
                index = this.tabamout;
            } else if (index < 1) {
                index = 1;
            }

            if (index == this.tab) return;

            this.transition = index < this.tab ? "slide-left" : "slide-right";
            this.tab = index;
        },

        next() {
            this.goto(this.tab + 1);
        },

        prev() {
            this.goto(this.tab - 1);
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
            </section>

            <section v-else-if="tab == 2">
                <h2>Select your plan</h2>
                <p>You have the option of monthly or yearly billing.</p>
            </section>

            <section v-else-if="tab == 3">
                <h2>Pick add-ons</h2>
                <p>Add-ons help enhance your gaming experience.</p>
            </section>

            <section v-else>
                <h2>Finishing up</h2>
                <p>Double-check everything looks OK before confirming.</p>
            </section>
        </Transition>
    </main>

    <footer>
        <div>
            <button v-if="tab > 1" class="btn-goback" @click="prev">Go Back</button>
        </div>
        <button v-if="tab < tabamout" @click="next">Next Step</button>
        <button v-else class="btn-action" @click="submit">Confirm</button>
    </footer>
</template>

<style scoped>
main {
    background: lightgray;
    position: relative;
}

header {
    background: cornflowerblue;
    color: white;
    padding: 1rem;
}

header ul {
    list-style: none;
    display: flex;
    padding: 0;
    justify-content: center;
}

header ul li {
    border: 1px solid white;
    border-radius: 50%;
    padding: .3rem .6rem;
    transition: all 300ms ease;
}

header ul li:not(:last-child) {
    margin-right: 1rem;
}

section {
    border-radius: 5px;
    background: white;
    padding: 1rem;
    position: absolute;
    top: -3rem;
    left: 1rem;
    right: 1rem;
}

section p {
    color: gray;
}

footer {
    padding: 0 1rem;
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

button {
    padding: 0.5rem 1rem;
    margin: 0;
    border: none;
    background: navy;
    color: white;
    border-radius: 5px
}

.btn-goback {
    color: gray;
    background: transparent;
    padding-left: 0;
    padding-right: 0;
}

.btn-action {
    background: cornflowerblue;
}

.active {
    background: white;
    border-color: white;
    color: black;
}

.slide-right-enter-active,
.slide-right-leave-active,
.slide-left-enter-active,
.slide-left-leave-active {
    transition: transform 300ms ease;
}

.slide-right-enter-from,
.slide-left-leave-to {
    position: absolute;
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
    grid-template-rows: 10rem minmax(25rem, auto) 4rem;
    overflow-x: hidden;
}
</style>
