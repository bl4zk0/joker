<template>
    <div id="chat-wrapper" class="d-none d-xl-block px-2">
        <div id="chat">
            <div id="messages-wrapper">
                <div id="messages">
                    <p v-for="msg in this.messages">
                        <strong v-text="msg.username + ': '"></strong>
                        <span v-text="msg.message"></span>
                    </p>
                </div>
            </div>
            <div id="chat-input-wrapper">
                <div id="emojis" class="border border-white bg-white rounded pl-2 py-2" v-show="dEmojis">
                    <span v-for="emoji in emojis" v-text="emoji" @click="smile"></span>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" v-model="message" @keypress="sendMsgWithEnter">
                    <div class="input-group-prepend">
                        <button class="btn btn-light"
                                type="button" @click="toggleEmojis"><i class="far fa-smile"></i></button>
                        <button class="btn btn-primary"
                                type="button"
                                @click="sendMessage"><i class="far fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['messages', 'gameId'],

        data() {
            return {
                message: '',
                dEmojis: false,
                emojis: [
                    '😁','😂','🤣','😅','😆','😉','😉','😊','😋','😎','😍','😘','🥰','🙂','🤗','🤩','🤔','🤨','😐','😶','🙄',
                    '😏','😣','😥','😮','🤐','😯','😪','🥱','😴','😌','😛','😜','😝','😓','😔','😕','🙃','☹','🙁','😞','😢',
                    '😭','😦','😨','😬','😰','😱','🥶','🥵','🤪','🤬','🤢','🤮','😇','🥳','🤭','🤓','😈','👸','🤴','🤷‍',
                    '🤷‍♂️','🤦‍♀️','🤦‍♂️','🤝','🙏','👏','✌','👌','👎','👍','❄','🎃','🍓','🍒','☕','🍩','⏰','💣','🖱','🌷','🌼',
                    '💔','💘','❤','💚','💛'
                ]
            }
        },

        methods: {
            sendMsgWithEnter(event) {
                if (event.key === "Enter") {
                    this.sendMessage();
                }
            },

            sendMessage() {
                if (this.message) {
                    let message = {
                        username: App.user.username,
                        message: this.message
                    };

                    this.messages.push(message);
                    Echo.private('game.' + this.gameId)
                        .whisper('message', message);
                    this.message = '';
                    this.dEmojis = false;
                    this.$nextTick(() => {
                        let el = document.getElementById('messages');
                        el.scrollTo(0, el.scrollHeight);
                    });
                }
            },

            smile(event) {
                this.message = this.message + event.target.innerText;
            },

            toggleEmojis() {
                this.dEmojis = ! this.dEmojis;
            }
        }

    }
</script>
