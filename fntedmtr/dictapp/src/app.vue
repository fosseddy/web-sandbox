<script>
export default {
    data() {
        return {
            word: "",
            loading: false,
            data: null,
            error: null,
            audio: new Audio()
        };
    },

    methods: {
        async queryDictionary() {
            this.word = this.word.trim();
            if (!this.word.length) return;

            // @Note(art): if audio is present then canplaythough
            // event listener stops the page loading state
            this.loading = true;
            this.error = null
            this.data = null;

            const apiURL = `https://api.dictionaryapi.dev/api/v2/` +
                           `entries/en/${this.word}`;
            try {
                const res = await fetch(apiURL);
                if (!res.ok) {
                    this.error = await res.json();
                    return;
                }

                this.data = await res.json();
                this.data = this.data[0];

                const p = this.data.phonetics.find(it => it.audio?.length > 0);
                if (p) {
                    this.data.hasAudio = true;
                    this.audio.src = p.audio;
                    this.audio.load();
                } else {
                    this.loading = false;
                }
            } catch {
                console.error(err);
                this.error = {
                    title: "Something Went Wrong",
                    message: "Sorry pal, something went wrong, try checking your internet connection."
                }
                this.loading = false;
            }
        },

        stopLoading() {
            this.loading = false;
        }
    },

    created() {
        this.audio.addEventListener("canplaythrough", this.stopLoading);
    },

    unmounted() {
        this.audio.removeEventListener("canplaythrough", this.stopLoading);
    }
};
</script>

<template>
    <div>
        <header>
            <div>Logo</div>

            <select>
                <option>Serif</option>
                <option>Mono</option>
                <option>Sans-Serif</option>
            </select>
            <input type="checkbox">
            <div>light</div>

            <form @submit.prevent="queryDictionary()">
                <input v-model="word">
            </form>
        </header>

        <main>
            <div v-if="loading">
                <h1>Loading...</h1>
            </div>
            <div v-else-if="error">
                <h1>{{ error.title }}</h1>
                <p>{{ error.message }}</p>
            </div>
            <header v-else-if="data">
                <h1>{{ data.word }}</h1>
                <p>{{ data.phonetic }}</p>
                <button v-if="data.hasAudio" @click="audio.play()" >Play</button>
            </header>
        </main>
    </div>
</template>

<style scoped>
</style>
