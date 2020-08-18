<template>
    <div class="card-body d-flex justify-content-around flex-wrap">
        <div v-if="games.length === 0" class="alert alert-info">
            მაგიდები არ მოიძებნა.
        </div>

        <div v-else class="card card-table mb-3" v-for="game in games" :key="game.id">
            <div class="card-header text-center">
                <strong>{{ game.type === 1 ? 'სტანდარტული' : '9-იანები' }} | {{ game.penalty }}</strong>
            </div>
            <ul class="list-group list-group-flush">

                <li class="list-group-item" v-for="n in [0,1,2,3]">
                    <a v-if="game.players[n]"
                       :href="'/user/' + game.players[n].user_id"
                       class="text-dark u-link"
                       target="_blank">
                        <img :src="game.players[n].avatar_url"
                             class="avatar border rounded-circle"
                             alt="avatar">
                        <span v-text="game.players[n].username"></span>
                    </a>
                    <span v-else>...</span>
                </li>

                <li class="list-group-item">
                    <a :href="path(game.id)" class="btn btn-block btn-success" :class="klas(game.players.length)">შესვლა</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['initialGames'],

        data() {
            return {
                games: this.initialGames
            }
        },

        created() {
            Echo.private('lobby')
                .listen('UpdateLobbyEvent', event => {
                    console.log('UpdateLobbyEvent', event);
                    this.games = event.games;
                });
        },

        methods: {
            path(id) {
                return '/games/' + id;
            },
            klas(n) {
                return n === 4 ? 'disabled' : '';
            }
        }
    }
</script>
