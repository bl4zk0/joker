<template>
    <div class="container-fluid" style="font-size: 10px">
        <div class="row">
            <div class="col-md-2">
                <form @submit.prevent="call">
                    <div class="form-group">
                        <select class="form-control" name="call">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select>
                    </div>
                    <button class="btn btn-primary">send</button>
                </form>
            </div>
            <div class="col-md-7 bg-success rounded">
                <div id="trump" style="position: absolute; right: 10px; top: 10px;" v-show="this.game.trump">
                    <img :src="this.game.trump ? '/storage/cards/' + this.game.trump.suit + this.game.trump.strength + '.png': ''" style="height: 70px">
                </div>
                <div class="d-flex justify-content-center">
                    <div id="player2" class="my-3">
                        <div class="avatar border border-warning rounded-circle mx-auto mb-1"></div>
                        <div class="text-center">{{ this.game.players[this.players[2]] ? this.game.players[this.players[2]].user.name : 'player3'}}</div>
                    </div>
                </div>
                <div class="d-flex">
                    <div id="player1" class="align-self-center">
                        <div class="avatar border border-warning rounded-circle mb-1"></div>
                        <div class="mb-3 text-center">{{ this.game.players[this.players[1]] ? this.game.players[this.players[1]].user.name : 'player2'}}</div>
                    </div>

                    <div class="text-center flex-grow-1 mx-5"
                         style="height: 350px">
                        <div class="t-cards">
                            <div id="p0-card">
                                <img src="" style="height: 100px">
                            </div>
                            <div id="p1-card">
                                <img src="" style="height: 100px">
                            </div>
                            <div id="p2-card">
                                <img src="" style="height: 100px">
                            </div>
                            <div id="p3-card">
                                <img src="" style="height: 100px">
                            </div>
                        </div>
                    </div>

                    <div id="player3" class="align-self-center">
                        <div class="avatar border border-warning rounded-circle mb-1"></div>
                        <div class="mb-3 text-center">{{ this.game.players[this.players[3]] ? this.game.players[this.players[3]].user.name : 'player4'}}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div id="player0" class="my-3">

                        <cards :player-id="this.game.players[this.players[0]].id"
                               :state="this.game.state"
                               :game-id="this.game.id"
                               :turn="this.game.turn"
                               :pos="this.game.players[this.players[0]].position"></cards>

                        <div class="avatar border border-warning rounded-circle mb-1"></div>
                        <div class="text-center">{{ this.game.players[this.players[0]] ? this.game.players[this.players[0]].user.name : 'player1'}}</div>
                    </div>
                </div>
                <div style="position: absolute; right: 5px; bottom: 5px">

                    <button type="button" class="btn btn-warning" @click="start">Start</button>

                </div>
            </div>
            <div class="col-md-3">
                <scores :initial-players="this.game.players"></scores>
            </div>
        </div>

    </div>
</template>

<script>
    import scores from './Scores';
    import cards from './Cards';

    export default {
        components: { scores, cards },

        props: ['initialGame'],

        data() {
            return {
                game: this.initialGame,
                players: {},
                cardCount: 0
            }
        },

        created() {
            this.authUserIndex();
            window.Echo.private('game.' + this.game.id)
                .listen('UpdateGameEvent', event => {
                    this.game = event.game;
                })
                .listen('PlayersEvent', event => {
                    this.game.players = event.players;
                })
                .listen('CardPlayEvent', event => {
                    //console.log(event);
                    this.cardCount++;
                    let zindex = this.cardCount;
                    let card = event.card;
                    let p = Number(this.getKeyByValue(this.players, event.position));
                    let el = document.querySelector('#p' + p + '-card');
                    el.style.zIndex = zindex;
                    el.firstChild.setAttribute('src', '/storage/cards/' + card.suit + card.strength + '.png');
                    if (this.cardCount === 4) {
                        setTimeout(() => {
                            let els = document.querySelector('.t-cards').children;
                            for (let i = 0; i < 4; i++) {
                                els[i].firstChild.setAttribute('src', '');
                            }
                            this.cardCount = 0;
                        }, 1000);
                    }
                    this.game.turn = this.game.turn === 3 ? 0 : this.game.turn + 1;
                })
                .listen('StartGameEvent', event => {
                    let cards = event.cards;
                    let i = 0;
                    let p = 0;
                    p = Number(this.getKeyByValue(this.players, p));

                    let atuzva = () => {
                        let el = document.querySelector('#p' + p + '-card');
                        el.style.zIndex = i;
                        el.firstChild.setAttribute('src', '/storage/cards/' + cards[i].suit + cards[i].strength + '.png');
                        p = p === 3 ? 0 : p + 1;
                        i++;
                        if (i === cards.length) {
                            clearInterval(tuz);
                            setTimeout(() => {
                                let els = document.querySelector('.t-cards').children;
                                for (let i = 0; i < 4; i++) {
                                        els[i].firstChild.setAttribute('src', '');
                                    }
                                this.game = event.game;
                                this.authUserIndex();

                           }, 1000);
                        }
                    }

                    let tuz = setInterval(atuzva, 1000);

                });
        },

        methods: {
            call() {
                if (this.game.turn !== this.game.players[this.players[0]].position) return;
                let el = document.querySelector('select[name="call"]');
                let data = {
                    call: el.options[el.options.selectedIndex].value
                }

                axios.post('/call/games/' + this.game.id, data).then(response => {
                    //console.log(response.data);
                    //to be developed
                });
            },

            start() {
                axios.post('/start/games/' + this.game.id);
            },

            authUserIndex() {
                let index;
                for (let i = 0; i < 4; i++) {
                    if (App.user.id === this.game.players[i].user_id) {
                        index = i;
                        break;
                    }
                }

                switch(index) {
                    case 1:
                        this.players = { 0: 1, 1: 2, 2: 3, 3: 0 };
                        break;
                    case 2:
                        this.players = { 0: 2, 1: 3, 2: 0, 3: 1 };
                        break;
                    case 3:
                        this.players = { 0: 3, 1: 0, 2: 1, 3: 2 };
                        break;
                    default:
                        this.players = { 0: 0, 1: 1, 2: 2, 3: 3 };
                        break;
                }
            },

            getKeyByValue(object, value) {
                return Object.keys(object).find(key => object[key] === value);
            }
        },

        mounted() {
            //console.log(this.game);
        }
    }
</script>

<style>
    .avatar {
        width: 70px;
        height: 70px;
    }
    .t-cards > div {
        position: absolute;
    }
    #p0-card {
        left: 320px;
        top: 240px;
    }
    #p1-card {
        left: 280px;
        top: 200px;
    }
    #p2-card {
        left: 320px;
        top: 150px;
    }
    #p3-card {
        left: 360px;
        top: 200px;
    }
</style>
