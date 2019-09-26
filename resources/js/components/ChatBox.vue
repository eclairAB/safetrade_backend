<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <p v-for="message in messages">{{ message }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-8 text-right">
                <input v-model="message" placeholder="Message" />
                <button
                    class="btn btn-primary btn-sm"
                    @click="postMessage"
                    :disabled="!contentExists"
                >
                    submit
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: "",
            messages: []
        };
    },
    computed: {
        contentExists() {
            return this.message.length > 0;
        }
    },
    methods: {
        postMessage() {
            axios
                .post("/messages", { message: this.message })
                .then(({ data }) => {
                    this.messages.push(data);
                    this.message = "";
                });
        }
    },
    created() {
        axios.get("/messages").then(({ data }) => {
            this.messages = data;
        });
        // Registered client on public channel to listen to MessageSent event
        Echo.channel("safetrade_database_public").listen(
            "MessageSent",
            ({ message }) => {
                this.messages.push(message);
            }
        );
    }
};
</script>

<style lang="scss" scoped>
.card-body {
    height: 200px;
    overflow-y: auto;
}
</style>
