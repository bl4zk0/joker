<template>
    <div class="row">
        <div class="col-lg-3 mb-3">
        <div class="card"><div class="card-header">ახალი მაგიდა</div>
            <div class="card-body">
                <form action="/games" method="POST">
                    <input type="hidden" name="_token" :value="csrf">
                    <div class="form-group">
                        <label for="rank">რანკი</label>
                        <select id="rank" class="form-control" name="rank">
                            <option value="0">ბრინჯაო</option>
                            <option value="1">ვერცხლი</option>
                            <option value="2">ოქრო</option>
                            <option value="3">პლატინა</option>
                            <option value="4">მასტერი</option>
                            <option value="5">გრანდმასტერი</option>
                            <option value="6">ჯოკერი</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">ტიპი</label>
                        <select id="type" class="form-control" name="type">
                            <option value="1" selected>სტანდარტული</option>
                            <option value="9">9-იანები</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="penalty">ხიშტი</label>
                        <select id="penalty" class="form-control" name="penalty">
                            <option value="-200" selected>-200</option>
                            <option value="-300">-300</option>
                            <option value="-400">-400</option>
                            <option value="-500">-500</option>
                            <option value="-900">-900</option>
                            <option value="-1000">-1000</option>
                        </select>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="pwd" name="password">
                        <label class="form-check-label" for="pwd">პაროლიანი მაგიდა</label>
                    </div>
                    <button class="btn btn-primary btn-block">მაგიდის შექმნა</button>
                </form>
            </div>
        </div>
        </div>

        <div class="col-lg-3 mb-3"
             v-for="game in this.games" :key="game.id">
            <div class="card">
                <div class="card-header">{{ game.creator.username + '`s game' }}</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">1: {{ game.players[0] ? game.players[0].user.username : '&nbsp' }}</li>
                    <li class="list-group-item">2: {{ game.players[1] ? game.players[1].user.username : '&nbsp'}}</li>
                    <li class="list-group-item">3: {{ game.players[2] ? game.players[2].user.username : '&nbsp'}}</li>
                    <li class="list-group-item">4: {{ game.players[3] ? game.players[3].user.username : '&nbsp'}}</li>
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
