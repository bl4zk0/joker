<template>
    <div id="chat-wrapper" class="d-none d-xl-block px-2 border">
        <div id="chat">
            <div id="messages-wrapper">
                <div id="messages">
                    <p v-for="msg in this.messages">
                        <span v-if="msg.notification">
                            <strong class="text-system">[{{ lang('Notification') }}]:</strong>
                            {{ lang('Player') }} <strong v-text="msg.username"></strong> {{ msg.message }}
                        </span>
                        <span v-else>
                            <strong v-text="`${msg.username}: `" :class="username_color(msg.username)"></strong>
                            <span v-text="msg.message"></span>
                        </span>
                    </p>
                </div>
            </div>
            <div id="chat-input-wrapper">
                <Picker id="emojis" :data="emojiIndex"
                    @select="addEmoji" v-show="showEmojis"
                    :i18n="i18n_config" :perLine="8" :showPreview="false" :native="true" />

                <div class="input-group">
                    <input id="msgInput" type="text" class="form-control" v-model="message" @keypress="keypress_action">
                    <button class="btn btn-light"
                        type="button" @click="toggleEmojis"><i class="fa-regular fa-face-laugh"></i></button>
                    <button class="btn btn-primary"
                        type="button"
                        @click="sendMessage"><i class="far fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import data from "emoji-mart-vue-fast/data/apple.json";
    import "emoji-mart-vue-fast/css/emoji-mart.css";
    import { Picker, EmojiIndex } from "emoji-mart-vue-fast/src";
    import translate from '../mixins/translate';

    let emojiIndex = new EmojiIndex(data);
    export default {
        components: { Picker },
        props: ['messages', 'gameId'],
        mixins: [translate],

        data() {
            return {
                emojiIndex: emojiIndex,
                message: '',
                showEmojis: false,
                i18n_config: {}
            }
        },

        created() {
            let i18n = {
                search: this.lang('Search'),
                notfound: this.lang('No Emoji Found'),
                categories: {
                    search: this.lang('Search Results'),
                    recent: this.lang('Frequently Used'),
                    smileys: this.lang('Smileys & Emoticon'),
                    people: this.lang('People & Body'),
                    nature: this.lang('Animals & Nature'),
                    foods: this.lang('Food & Drink'),
                    activity: this.lang('Activity'),
                    places: this.lang('Travel & Places'),
                    objects: this.lang('Objects'),
                    symbols: this.lang('Symbols'),
                    flags: this.lang('Flags')
                }
            }

            this.i18n_config = i18n;
        },

        methods: {
            keypress_action(event) {
                if (event.key === "Enter") {
                    this.sendMessage();
                }
                if (this.showEmojis) {
                    this.showEmojis = !this.showEmojis;
                }
            },

            sendMessage() {
                if (this.message === 'clear') {
                    this.$emit('clear-chat');
                    this.message = '';
                    return;
                }

                if (this.message) {
                    let message = {
                        username: App.user.username,
                        message: this.message
                    };

                    this.messages.push(message);

                    axios.post('/message/games/' + this.gameId, {message: this.message})
                        .catch(error => {
                            console.log(error.message);
                        });

                    this.message = '';
                    this.showEmojis = false;
                    this.$nextTick(() => {
                        let el = document.getElementById('messages');
                        el.scrollTo(0, el.scrollHeight);
                    });
                }
            },

            addEmoji(emoji) {
                this.message = this.message + emoji.native;
                $('#msgInput').trigger('focus');
            },

            toggleEmojis() {
                this.showEmojis = ! this.showEmojis;
            },

            username_color(username) {
                if (username === '[system]') {
                    return 'text-system';
                } else {
                    return 'text-username';
                }

            }
        }

    }
</script>
