<script>
import Icon from "@/icons/icon.vue"
import Header from "@/header/header.vue"

export default {
    components: { Icon, Header },

    data() {
        return {
            loading: false,
            data: null,
            error: null,
            audio: new Audio()
        };
    },

    methods: {
        async queryDictionary(word) {
            word = word.trim();
            if (!word.length) return;

            this.loading = true;
            this.error = null
            this.data = null;

            const apiURL = `https://api.dictionaryapi.dev/api/v2/` +
                           `entries/en/${word}`;
            try {
                const res = await fetch(apiURL);
                if (!res.ok) {
                    this.error = await res.json();
                    this.loading = false;
                    return;
                }

                this.data = await res.json();
                this.data = this.data[0];

                // @Note(art): if audio is present then canplaythough
                // event listener stops the page loading state
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
    <Header @query-word="queryDictionary" />

    <main>
        <div v-if="loading">
            <h1>Loading...</h1>
        </div>
        <div v-else-if="error">
            <h1>{{ error.title }}</h1>
            <p>{{ error.message }}</p>
        </div>
        <div v-else-if="data">
            <header>
                <h1>{{ data.word }}</h1>
                <p>{{ data.phonetic }}</p>
                <button v-if="data.hasAudio" @click="audio.play">Play</button>
            </header>
            <section v-for="m in data.meanings" :key="m.partOfSpeech">
                <h2>{{ m.partOfSpeech }}</h2>
                <hr>
                <p>Meaning</p>
                <ul>
                    <li v-for="d in m.definitions" :key="d.definition">{{ d.definition }}</li>
                </ul>
                <p v-if="m.synonyms.length">Synonyms {{ m.synonyms.join(", ") }}</p>
                <p v-if="m.antonyms.length">Antonyms {{ m.antonyms.join(", ") }}</p>
            </section>
            <template v-if="data.sourceUrls.length">
                <hr>
                <p>Source <a :href="data.sourceUrls[0]">{{ data.sourceUrls[0] }}</a></p>
            </template>
        </div>
    </main>
</template>

<style scoped>
main {
    color: var(--color-fg);
    background-color: var(--color-bg);
}
</style>
