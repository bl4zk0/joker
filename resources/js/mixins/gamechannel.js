export default {
    data() {
        return {
            messages: []
        }
    },

    created() {
        Echo.private('game.' + this.game.id)
            .listen('UpdateGameEvent', event => {
                console.log('UpdateGameEvent');
                this.game = event.game;
                this.nextTurn = event.game.turn;
            })
            .listen('PlayerCallEvent', event => {
                console.log('PlayerCallEvent');
                let p = this.ppm.indexOf(event.position);
                let content = event.score.call === 0 ? '-' : event.score.call;

                if (p !== 0) {
                    $('#player' + p).attr('data-content', content).popover('show');
                    setTimeout(() => {
                        $('#player' + p).popover('hide');
                    }, 3000);
                }
                this.callSum += event.score.call;
                this.game.players[event.position].scores.push(event.score);
                this.game.except = event.except;
                this.game.turn = event.turn;
                this.game.state = event.state;
                this.playState = true;
            })
            .listen('CardPlayEvent', event => {
                console.log('CardPlayEvent');
                this.game.cards.push(event.card);

                //es check aris tu karti itamasha botma da movida serveridan timeout is shemdeg
                if (event.position === this.ppm[0]) {
                    let id = 0;
                    let cards = this.players[event.position].cards;
                    for (let idx in cards) {
                        if (Number(event.card.strength) > 14 || Number(event.card.strength) == 1) {
                            if (event.card.suit == cards[idx].suit) {
                                id = idx;
                            }
                        } else {
                            if (event.card.suit == cards[idx].suit && event.card.strength == cards[idx].strength) {
                                id = idx;
                            }
                        }
                    }

                    this.players[event.position].cards.splice(id, 1);
                } else {
                    this.players[event.position].cards.pop();
                }

                this.game.players[event.position].card = event.card;

                this.lastCardsStorage[this.ppm.indexOf(event.position)] = Object.create(event.card);
                this.lastCardsStorage[this.ppm.indexOf(event.position)].z = this.game.cards.length;

                this.hideCards(event.take);
            })
            .listen('StartGameEvent', event => {
                $('#ready').addClass('d-none');
                clearInterval(this.timerFn);

                let cards = event.cards;
                let i = 0;
                let p = 0;
                p = this.ppm.indexOf(p);

                let ace = () => {
                    $('#player' + p + 'card')
                        .css('z-index', i)
                        .removeClass()
                        .addClass(cards[i].suit + cards[i].strength);
                    p = p === 3 ? 0 : p + 1;
                    i++;
                    if (i === cards.length) {
                        clearInterval(ace_ing);
                        setTimeout(() => {
                            for (let i = 0; i < 4; i++) {
                                $('#player' + i + 'card').removeClass();
                            }
                            this.game = event.game;
                            this.playerPositionsMap();
                            this.showCards(this.dealtCards, false);
                        }, 1500);
                    }
                }

                let ace_ing = setInterval(ace, 1000);

            })
            .listen('GetReadyEvent', event => {
                this.game.state = 'ready';
                $('#ready-check').removeClass('d-none');
                $('#ready th').eq(event.position).addClass('bg-success');
                $('#ready').removeClass('d-none');
                this.timerFn = setInterval(() => {
                    if (this.timer === 0) {
                        $('#ready').addClass('d-none');
                        clearInterval(this.timerFn);
                        this.timer = 10;
                        $('#ready th').removeClass();
                    }
                    this.timer--;
                }, 1000);
            })
            .listen('UpdateReadyEvent', event => {
                let color  = event.ready === '1' ? 'bg-success' : 'bg-danger';

                $('#ready th').eq(event.position).addClass(color);
            })
            .listen('KickUserEvent', event => {
                if (App.user.id === this.game.players[event.position].user_id) {
                    $('#kicked').modal({show: true});
                    Echo.leaveChannel('game.' + this.game.id);
                    Echo.leaveChannel('user.' + App.user.id);
                    return;
                }

                this.game.players = event.players;
                this.playerPositionsMap();
            })
            .listen('GameOverEvent', event => {
                this.playState = false;
                this.game = event.game;
                for (let i = 0; i < 4; i++) {
                    $(`#place-${i} img`).attr('src', this.game.players[event.scores[i].position].avatar_url);
                    $(`#place-${i} .u-name a`).attr('href', `/user/${this.game.players[event.scores[i].position].user_id}`)
                        .text(this.game.players[event.scores[i].position].username);
                }
                $('#game-over').removeClass('d-none');
            })
            .listenForWhisper('message', message => {
                this.messages.push(message);
                this.$nextTick(() => {
                    let el = document.getElementById('messages');
                    el.scrollTo(0, el.scrollHeight);
                });
            });
    },
}
