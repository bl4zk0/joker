<template>
    <div class="d-flex justify-content-center">
        <component :is="scoreboard" :initial-players="game.players"></component>

        <div id="play-table">
            <div class="btn-table" v-show="game.state === 'start'">
                <a href="/lobby" class="btn btn-outline-light"><i class="fas fa-arrow-circle-left"></i></a>
            </div>
            <div id="btn-chat" class="btn-table d-xl-none">
                <button type="button" class="btn btn-outline-light" @click="showChat">
                    <i class="fas fa-comment"></i>
                </button>
            </div>
            <div id="btn-scoreboard" class="btn-table d-md-none">
                <button type="button" class="btn btn-outline-light" @click="showScoreboard">
                    <i class="fas fa-table"></i>
                </button>
            </div>
            <div id="trump-wrapper" v-show="game.trump !== null">
                <div><strong>კოზირი</strong></div>
                <div id="trump" :class="this.game.trump ? game.trump.suit + game.trump.strength : ''"></div>
            </div>

            <!-- played cards -->
            <div id="player0card"
                 :class="playedCard(0)"
                 :style="'z-index: ' + cardsZIndex(0)">
                <div v-if="playedCardAction(0)"
                     class="card-action">
                    <div>
                        <span v-text="actions[game.players[this.ppm[0]].card['action']]"></span>
                        <span v-text="actionsuits[game.players[this.ppm[0]].card['actionsuit']]"
                              style="font-size: 24px"
                              :class="suitColor(game.players[this.ppm[0]].card['actionsuit'])"></span>
                    </div>
                </div>
            </div>
            <div id="player1card"
                 :class="playedCard(1)"
                 :style="'z-index: ' + cardsZIndex(1)">
                <div v-if="playedCardAction(1)"
                     class="card-action">
                    <div>
                        <span v-text="actions[game.players[this.ppm[1]].card['action']]"></span>
                        <span v-text="actionsuits[game.players[this.ppm[1]].card['actionsuit']]"
                              style="font-size: 24px"
                              :class="suitColor(game.players[this.ppm[1]].card['actionsuit'])"></span>
                    </div>
                </div>
            </div>
            <div id="player2card"
                 :class="playedCard(2)"
                 :style="'z-index: ' + cardsZIndex(2)">
                <div v-if="playedCardAction(2)"
                     class="card-action">
                    <div>
                        <span v-text="actions[game.players[this.ppm[2]].card['action']]"></span>
                        <span v-text="actionsuits[game.players[this.ppm[2]].card['actionsuit']]"
                              style="font-size: 24px"
                              :class="suitColor(game.players[this.ppm[2]].card['actionsuit'])"></span>
                    </div>
                </div>
            </div>
            <div id="player3card"
                 :class="playedCard(3)"
                 :style="'z-index: ' + cardsZIndex(3)">
                <div v-if="playedCardAction(3)"
                     class="card-action">
                    <div>
                        <span v-text="actions[game.players[this.ppm[3]].card['action']]"></span>
                        <span v-text="actionsuits[game.players[this.ppm[3]].card['actionsuit']]"
                              style="font-size: 24px"
                              :class="suitColor(game.players[this.ppm[3]].card['actionsuit'])"></span>
                    </div>
                </div>
            </div>

            <!-- player0 cards -->
            <div v-for="(card, index) in players[ppm[0]].cards"
                 :class="'p-card p0-card ' + card.suit + card.strength"
                 :style="'margin-left: ' + (getMargin(0) + index * 25) + 'px'"
                 :id="index"
                 :key="index"
                 :data-suit="card.suit"
                 :data-strength="card.strength" @click="actionCard"></div>

            <!-- player1 cards -->
            <div v-for="(index) in players[ppm[1]].cards"
                 class="p-card p1-card card_back card_back_size"
                 :style="'margin-top: ' + (getMargin(1) + (index * marginStep())) + 'px'"></div>

            <!-- player2 cards -->
            <div v-for="(index) in players[ppm[2]].cards"
                 class="p-card p2-card card_back card_back_size"
                 :style="'margin-left: ' + (getMargin(2) + (index * marginStep())) + 'px'"></div>

            <!-- player3 cards -->
            <div v-for="(index) in players[ppm[3]].cards"
                 class="p-card p3-card card_back card_back_size"
                 :style="'margin-top: ' + (getMargin(1) + (index * marginStep())) + 'px'"></div>
            <!-- players cards -->

            <!-- taken cards-->
            <div class="p0-tc taken-card card_back"
                 v-show="game.state === 'card'"
                 v-for="(v, index) in players[ppm[0]].takenCards"
                 :style="'margin-left: ' + (35 + index * 10) + 'px'"></div>

            <div class="p1-tc taken-card card_back"
                 v-show="game.state === 'card'"
                 v-for="(v, index) in players[ppm[1]].takenCards"
                 :style="'margin-top: ' + (35 + index * 10) + 'px'"></div>

            <div class="p2-tc taken-card card_back"
                 v-show="game.state === 'card'"
                 v-for="(v, index) in players[ppm[2]].takenCards"
                 :style="'margin-right: ' + (35 + index * 10) + 'px'"></div>

            <div class="p3-tc taken-card card_back"
                 v-show="game.state === 'card'"
                 v-for="(v, index) in players[ppm[3]].takenCards"
                 :style="'margin-bottom: ' + (35 + index * 10) + 'px'"></div>

            <!-- players -->
            <div id="player0">
                <div class="avatar border rounded-circle" :class="active(0)">

                </div>
                <div class="u-name" v-text="getUsername(0)"></div>
            </div>
            <div id="player1" data-container="body" data-toggle="popover" data-placement="top" data-trigger="manual">
                <div class="kick" title="გაგდება" v-show="canKickUser(1)">
                    <i class="fas fa-times" @click="kick(1)"></i>
                </div>
                <div class="avatar border rounded-circle" :class="active(1)">

                </div>
                <div class="u-name" v-text="getUsername(1)"></div>
            </div>
            <div id="player2" data-container="body" data-toggle="popover" data-placement="right" data-trigger="manual">
                <div class="kick" title="გაგდება" v-show="canKickUser(2)">
                    <i class="fas fa-times" @click="kick(2)"></i>
                </div>
                <div class="avatar border rounded-circle" :class="active(2)">

                </div>
                <div class="u-name" v-text="getUsername(2)"></div>
            </div>
            <div id="player3" data-container="body" data-toggle="popover" data-placement="top" data-trigger="manual">
                <div class="kick" title="გაგდება" v-show="canKickUser(3)">
                    <i class="fas fa-times" @click="kick(3)"></i>
                </div>
                <div class="avatar border rounded-circle" :class="active(3)">

                </div>
                <div class="u-name" v-text="getUsername(3)"></div>
            </div>

            <div id="start-btn" class="shadow" v-show="showStart">
                <button class="btn btn-danger btn-block"
                        type="button"
                        @click="start">
                    <small><strong>დაწყება</strong></small>
                </button>
            </div>

            <div id="callboard"
                 class="bg-white border rounded shadow pt-1 pl-1"
                 v-show="game.state === 'call' && turn">

                <button class="btn btn-light mb-1 mr-1"
                        v-for="idx in callboard"
                        v-text="idx === 0 ? '-' : idx"
                        :data-value="idx"
                        :disabled="game.except === idx"
                        @click="call"></button>
            </div>

            <div id="ready" class="bg-white border rounded p-1 text-center" v-show="this.game.state === 'ready'">
                <p id="ready-waiting" class="d-none ">დაელოდეთ მოთამაშეების თანხმობას</p>
                <div id="ready-check" class="d-none mb-3">
                    <p>მზად ხარ თამაშის დასაწყებად?</p>
                    <div id="d-none ready-buttons">
                        <button class="btn btn-success" data-ready="1" @click="actionReady">კი</button>
                        <button class="btn btn-danger" data-ready="0" @click="actionReady">არა</button>
                    </div>
                </div>
                <p v-text="timer"></p>
                <table class="table table-bordered mt-3 mb-0" style="max-width: 310px;">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th scope="col">{{ this.game.players[0] ? this.game.players[0].user.username : '...'}}</th>
                            <th scope="col">{{ this.game.players[1] ? this.game.players[1].user.username : '...'}}</th>
                            <th scope="col">{{ this.game.players[2] ? this.game.players[2].user.username : '...'}}</th>
                            <th scope="col">{{ this.game.players[3] ? this.game.players[3].user.username : '...'}}</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div id="suits" class="d-none border bg-white rounded pt-1 px-1">
                <button class="btn btn-light text-danger mb-1" data-suit="hearts" @click="actionSuit">&hearts;</button>
                <button class="btn btn-light text-dark mb-1" data-suit="clubs" @click="actionSuit">&clubs;</button>
                <button class="btn btn-light text-danger mb-1" data-suit="diamonds" @click="actionSuit">&diams;</button>
                <button class="btn btn-light text-dark mb-1" data-suit="spades" @click="actionSuit">&spades;</button>
                <button class="btn btn-light text-dark btn-block mb-1" data-suit="bez" @click="actionSuit"
                        v-show="game.state === 'trump'">ბეზი</button>
            </div>

            <div id="jokhigh" class="d-none border bg-white rounded shadow p-1">
                <button class="btn btn-light" data-action="magali" @click="actionJoker">მაღალი</button>
                <button class="btn btn-danger" data-action="caigos" @click="actionJoker">წაიღოს</button>
            </div>

            <div id="jokjoker" class="d-none border bg-white rounded shadow p-1" style="width: 202px;">
                <button class="btn btn-light" data-action="mojokra" @click="actionJoker">მოჯოკრა</button>
                <button class="btn btn-danger" data-action="kvevidan" @click="actionJoker">ქვევიდან</button>
            </div>
        </div>
        <div class="modal fade" id="kicked" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-danger">
                    <div class="modal-body text-white">
                        <i class="fas fa-exclamation-triangle"></i> თქვენ გამოგაგდეს მაგიდიდან!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal" @click="goToLobby">OK!</button>
                    </div>
                </div>
            </div>
        </div>
        <chat :messages="messages" :game-id="this.game.id"></chat>
        <div class="close-w d-none">
            <button type="button" class="btn btn-outline-light" @click="closeW">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script>
    import scoreboard1 from './Scoreboard1';
    import scoreboard9 from './Scoreboard9';
    import chat from './Chat';
    import helpers from '../mixins/helpers';
    import gamechannel from '../mixins/gamechannel';
    import playerchannel from '../mixins/playerchannel';

    export default {
        components: { scoreboard1, scoreboard9, chat },
        mixins: [helpers, gamechannel, playerchannel],
        props: ['initialGame', 'initialCards'],

        data() {
            return {
                game: this.initialGame,
                cardState: true,
                players: [
                    {cards: [], takenCards: []},
                    {cards: [], takenCards: []},
                    {cards: [], takenCards: []},
                    {cards: [], takenCards: []},
                ],
                card: {},
                actions: {"magali": "მაღალი", "caigos": "წაიღოს", "mojokra": "მოჯოკრა", "kvevidan": "ქვევიდან"},
                actionsuits: {"hearts": "♥", "clubs": "♣", "diamonds": "♦", "spades": "♠"}
            }
        },

        computed: {
            scoreboard() {
                return 'scoreboard' + this.game.type;
            },

            callboard() {
                let max;

                if (this.game.quarter % 2 === 0 || this.game.type === 9) {
                    max = 10;
                } else {
                    max = this.game.hand_count + 1;
                }

                return Array.from(new Array(max).keys());
            },

            turn() {
                return this.game.turn === this.game.players[this.ppm[0]].position;
            }

        },

        methods: {
            start(event) {
                // hide button
                this.game.state = 'void';

                // post start
                axios.post('/start/games/' + this.game.id)
                    .then(response => {
                        this.game.state = 'ready';
                        $('#ready-waiting').removeClass('d-none');
                        $('#ready th').eq(this.ppm[0]).addClass('bg-success');
                        this.timerFn = setInterval(() => {
                            if (this.timer === 0) {
                                this.game.state = 'void';
                                clearInterval(this.timerFn);
                                this.timer = 10;
                                $('#ready th').removeClass();
                            }
                            this.timer--;
                        }, 1000);
                    })
                    .catch(error => {
                        location.reload();
                    });
            },

            actionReady(event) {
                let ready = event.target.getAttribute('data-ready');
                let color  = ready === '1' ? 'bg-success' : 'bg-danger';

                $('#ready-waiting').removeClass('d-none');
                $('#ready-check').addClass('d-none');
                $('#ready th').eq(this.ppm[0]).addClass(color);

                axios.post('/ready/games/' + this.game.id, {ready});
            },

            actionCard(event) {
                // es aris state roca karts daacher sxva kartebze rom vegar daachiro da itamasho sanam es karti ar gaigzavneba
                if (! this.cardState) return;
                this.cardState = false;
                let strength = event.target.getAttribute('data-strength');
                let suit = event.target.getAttribute('data-suit');
                let card = { strength, suit };

                if (! this.turn || this.game.state !== 'card') {
                    console.log('wrong turn or state');
                    this.cardState = true;
                    return;
                }

                this.cardId = event.target.id;
                this.card.card = card;

                if (strength === '16') {
                    if (this.game.cards.length === 0) {
                        $('#jokhigh').removeClass('d-none');
                    } else {
                        $('#jokjoker').removeClass('d-none');
                    }
                } else {
                    if (! this.canPlay(card)) {
                        console.log('you can not play this card');
                        return;
                    }
                    this.afterActionCard(card);
                }
            },

            actionJoker(event) {
                let action = event.target.getAttribute('data-action');
                let card = {
                    strength: this.card.card.strength,
                    suit: this.card.card.suit,
                    action
                }
                this.card.action = action;
                if (action === 'magali' || action === 'caigos') {
                    $('#jokhigh').addClass('d-none');
                    $('#suits').removeClass('d-none');
                } else {
                    $('#jokjoker').addClass('d-none');
                    if (! this.canPlay(card)) {
                        console.log('you can not play this card');
                        return;
                    }
                    this.afterActionCard(card);
                }
            },

            actionSuit(event) {
                let suit = event.target.getAttribute('data-suit');
                if (this.game.state === 'trump') {
                    axios.post('/trump/games/' + this.game.id, {trump: suit});
                    this.setTrump = false;
                } else if (this.game.state === 'card') {
                    let card = {
                        strength: this.card.card.strength,
                        suit: this.card.card.suit,
                        action: this.card.action,
                        actionsuit: suit
                    }
                    this.card.actionsuit = suit;
                    if (! this.canPlay(card)) {
                        console.log('you can not play this card');
                        return;
                    }
                    this.afterActionCard(card);
                }

                $('#suits').addClass('d-none');
            },

            sendCard() {
                this.players[this.ppm[0]].cards.splice(this.cardId, 1);
                //this.game.turn = this.game.turn === 3 ? 0 : this.game.turn + 1;

                axios.post('/card/games/' + this.game.id, this.card)
                    .then(response => {
                        //this.nextTurn = response.data;
                        this.card = {};
                        this.cardId = null;
                        this.cardState = true;
                    })
                    .catch(error => {
                        location.reload();
                    });
            },

            call(event) {

                if (! this.turn || this.game.state !== 'call') {
                    console.log('wrong turn or state');
                    return;
                }

                this.game.turn = this.game.turn === 3 ? 0 : this.game.turn + 1;

                let call = {
                    call: event.target.getAttribute('data-value')
                }

                axios.post('/call/games/' + this.game.id, call)
                    // .then(response => {
                    //     //this.game.players[this.ppm[0]].scores.push(response.data.score);
                    //     //this.game.state = response.data.state;
                    //     //this.game.turn = response.data.turn;
                    // })
                    .catch(error => {
                        location.reload();
                    });
            },

            afterActionCard(card) {
                this.game.cards.push(card);
                this.game.players[this.ppm[0]].card = card;
                this.sendCard();
                this.hideCards(this.checkTake());
            }
        }
    }
</script>
