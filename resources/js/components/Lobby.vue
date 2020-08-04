<template>
    <div class="card-body d-flex justify-content-around flex-wrap">
        <div class="card card-table mb-3" v-for="game in this.games" :key="game.id">
            <div class="card-header">{{ game.creator.username + '`s game' }}</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">1: {{ game.players[0] ? game.players[0].user.username : '&nbsp' }}</li>
                <li class="list-group-item">2: {{ game.players[1] ? game.players[1].user.username : '&nbsp'}}</li>
                <li class="list-group-item">3: {{ game.players[2] ? game.players[2].user.username : '&nbsp'}}</li>
                <li class="list-group-item">4: {{ game.players[3] ? game.players[3].user.username : '&nbsp'}}</li>
                <li class="list-group-item">
                    <a :href="path(game.id)" class="btn btn-light" :class="kl(game.players.length)">Join</a>
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
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                games: this.initialGames
            }
        },

        created() {
            window.Echo.private('lobby')
                .listen('UpdateLobbyEvent', event => {
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
