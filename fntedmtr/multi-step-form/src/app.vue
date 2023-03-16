<script>
export default {
    data() {
        return {
            transition: "slide-right",
            tabamout: 4,
            tab: 1,
            aside: ["YOUR INFO", "SELECT PLAN", "ADD-ONS", "SUMMARY"]
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
    <div class="container" :style="{border:'1px solid black'}">
        <div class="nav" :style="{border:'1px solid red'}">
            <ul>
                <li v-for="(it, it_index) in aside" :key="it_index" class="box">
                    <div :class="{'active': it_index + 1 == tab}">{{it_index + 1}}</div>
                    <div>
                        <p>STEP {{it_index + 1}}</p>
                        <p>{{it}}</p>
                    </div>
                </li>
            </ul>
        </div>

        <div :style="{border:'1px solid green'}">
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
        </div>

        <div class="footer" :style="{border:'1px solid blue'}">
            <div>
                <button v-if="tab > 1" class="btn-goback" @click="prev">Go Back</button>
            </div>
            <button v-if="tab < tabamout" @click="next">Next Step</button>
            <button v-else class="btn-action" @click="submit">Confirm</button>
        </div>
    </div>
</template>

<style scoped>
.container {
    display: grid;
    grid-template-columns: minmax(15rem, 1fr) minmax(20rem, 2fr);
    grid-template-rows: 1fr 4rem;
    background: white;
    border-radius: 5px;
    padding: 1rem;
    column-gap: 2rem;
    min-height: 30rem;
}

.nav {
    grid-row: 1/3;
    padding: .5rem 1.5rem;
    background: cornflowerblue;
    color: white;
    border-radius: 5px;
}

.box {
    display: flex;
    align-items: center;
}

.box > :first-child {
    margin-right: 1rem;
    border: 1px solid white;
    border-radius: 50%;
    padding: .3rem .6rem;
    transition: all 300ms ease;
}

.box > :first-child.active {
    background: white;
    color: black;
}

.box > :nth-child(2) p {
    margin: 0;
    font-weight: bold;
}

.box > :nth-child(2) p:first-child {
    color: lightgray;
    font-weight: normal;
    font-size: .9rem;
}

.nav ul li:not(:last-child) {
    margin-bottom: 1rem;
}

button {
    cursor: pointer;
    padding: 0.5rem 1rem;
    margin: 0;
    border: none;
    background: navy;
    color: white;
    border-radius: 5px
}

button:hover {
    opacity: .8;
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

.footer {
    padding: 0 1rem;
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

section {
    padding: 1rem;
}

section p {
    color: gray;
}

/*
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
}*/
</style>

<!-- Global Styles -->
<style>
*,
*::after,
*::before {
    box-sizing: border-box;
}

ul {
    list-style: none;
    padding: 0;
}

html,
body,
#app {
    height: 100%;
}

body {
    display: grid;
    place-content: center;
    background: lightgray;
}
</style>
