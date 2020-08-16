<template>
    <div class="card-body d-flex justify-content-around flex-wrap">
        <div v-show="this.games.length === 0" class="alert alert-info">
            მაგიდები არ მოიძებნა.
        </div>

        <div class="card card-table mb-3" v-for="game in this.games" :key="game.id">
            <div class="card-header">
                <strong>{{ game.type === 1 ? 'სტანდარტული' : '9-იანები' }} | {{ game.penalty }}</strong>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a :href="game.players[0] ? '/user/' + game.players[0].user_id : ''"
                       class="text-dark u-link" target="_blank">
                        <img :src="game.players[0] ? game.players[0].avatar_url : ''" v-show="game.players[0]"
                             class="avatar border rounded-circle"
                             alt="avatar">
                        <span>{{ game.players[0] ? game.players[0].username : '...' }}</span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a :href="game.players[1] ? '/user/' + game.players[1].user_id : ''"
                       class="text-dark u-link" target="_blank">
                        <img :src="game.players[1] ? game.players[1].avatar_url : ''" v-show="game.players[1]"
                             class="avatar border rounded-circle"
                             alt="avatar">
                        <span>{{ game.players[1] ? game.players[1].username : '...' }}</span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a :href="game.players[2] ? '/user/' + game.players[2].user_id : ''"
                       class="text-dark u-link" target="_blank">
                        <img :src="game.players[2] ? game.players[2].avatar_url : ''" v-show="game.players[2]"
                             class="avatar border rounded-circle"
                             alt="avatar">
                        <span>{{ game.players[2] ? game.players[2].username : '...' }}</span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a :href="game.players[3] ? '/user/' + game.players[3].user_id : ''"
                       class="text-dark u-link" target="_blank">
                        <img :src="game.players[3] ? game.players[3].avatar_url : ''" v-show="game.players[3]"
                             class="avatar border rounded-circle"
                             alt="avatar">
                        <span>{{ game.players[3] ? game.players[3].username : '...' }}</span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a :href="path(game.id)" class="btn btn-block btn-success" :class="kl(game.players.length)">შესვლა</a>
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
                    console.log('UpdateLobbyEvent');
                    this.games = event.games;
                });
        },

        methods: {
            path(id) {
                return '/games/' + id;
            },
            kl(n) {
                return n === 4 ? 'disabled' : '';
            }
        }
    }
</script>
