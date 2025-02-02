<template>
    <div class="card-body d-flex justify-content-around flex-wrap">
        <div v-if="games.length === 0" class="alert alert-info">
            {{ lang('No table found') }}
        </div>

        <div v-else class="card card-table mb-3" v-for="game in games" :key="game.id">
            <div class="card-header text-center">
                <strong>{{ game.type === 1 ? lang('Standard') : lang('Only 9') }} | {{ game.penalty }}</strong>
            </div>
            <ul class="list-group list-group-flush">

                <li class="list-group-item" v-for="n in [0,1,2,3]">
                    <a v-if="game.players[n]"
                       :href="'/user/' + game.players[n].user_id"
                       class="u-link link-body-emphasis link-underline-opacity-0"
                       target="_blank">
                        <img :src="game.players[n].avatar_url"
                             class="avatar border rounded-circle"
                             alt="avatar">
                        <span v-text="game.players[n].username"></span>
                    </a>
                    <span v-else>#{{ n+1 }}</span>
                </li>

                <li class="list-group-item">
                    <div class="d-grid">
                        <a :href="path(game.id)" class="btn btn-success" :class="klas(game.players.length, game.kicked_users)">{{ lang('Join') }}</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import translate from '../mixins/translate';
    
    export default {
        props: ['initialGames'],
        mixins: [translate],

        data() {
            return {
                games: this.initialGames
            }
        },

        created() {
            Echo.private('lobby')
                .listen('UpdateLobbyEvent', event => {
                    this.games = event.games;
                });
        },

        methods: {
            path(id) {
                return '/games/' + id;
            },
            klas(len, kicked_users) {
                return (len === 4 || kicked_users.indexOf(App.user.id) >= 0) ? 'disabled' : '';
            }
        }
    }
</script>
