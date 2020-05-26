<template>
    <div class="row">
        <div class="col-lg-3 mb-3">

            <form action="/games" method="POST">
                <input type="hidden" name="_token" :value="csrf">
                <button class="btn btn-primary">New Game</button>
            </form>

        </div>

        <div class="col-lg-3 mb-3"
             v-for="game in this.games" :key="game.id">
            <div class="card">
                <div class="card-header">{{ game.creator.name + '`s game' }}</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">1: {{ game.players[0] ? game.players[0].user.name : '&nbsp' }}</li>
                    <li class="list-group-item">2: {{ game.players[1] ? game.players[1].user.name : '&nbsp'}}</li>
                    <li class="list-group-item">3: {{ game.players[2] ? game.players[2].user.name : '&nbsp'}}</li>
                    <li class="list-group-item">4: {{ game.players[3] ? game.players[3].user.name : '&nbsp'}}</li>
                    <li class="list-group-item">
                        <a :href="path(game.id)" class="btn btn-primary" :class="kl(game.players.length)">Join</a>
                    </li>
                </ul>
            </div>
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
                .listen('UpdateGamesEvent', event => {
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
