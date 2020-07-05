export default {
    data() {
        return {
            cardsCount: 0,
            messages: []
        }
    },

    created() {
        Echo.private('game.' + this.game.id)
            .listen('UpdateGameEvent', event => {
                this.game = event.game;
            })
            .listen('PlayerCallEvent', event => {
                console.log(event);
                let p = this.ppm.indexOf(event.position);
                let content = event.score.call === 0 ? '-' : event.score.call;
                $('#player' + p).attr('data-content', content).popover('show');
                setTimeout(() => {
                    $('#player' + p).popover('hide');
                }, 3000);
                this.game.players[event.position].scores.push(event.score);
                this.game.except = event.except;
                this.game.turn = event.turn;
                this.game.state = event.state;
            })
            .listen('CardPlayEvent', event => {
                this.cardsCount++;
                let p = this.ppm.indexOf(event.position);

                $('#player' + p + 'card')
                    .css('z-index', this.cardsCount)
                    .removeClass()
                    .addClass(event.card.suit + event.card.strength);

                if (this.cardsCount === 4) {
                    setTimeout(() => {
                        for (let i = 0; i < 4; i++) {
                            $('#player' + i + 'card').removeClass();
                        }
                        this.cardsCount = 0;
                    }, 1000);
                }

                this.game.turn = this.game.turn === 3 ? 0 : this.game.turn + 1;
            })
            .listen('StartGameEvent', event => {
                clearInterval(this.timerFn);
                this.game.state = 'void';
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
                            this.showCards(this.dealtCards);
                        }, 1500);
                    }
                }

                let ace_ing = setInterval(ace, 1000);

            })
            .listen('GetReadyEvent', event => {
                this.game.state = 'ready';
                $('#ready-check').removeClass('d-none');
                $('#ready th').eq(event.position).addClass('bg-success');
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
            .listen('UpdateReadyEvent', event => {
                let color  = event.ready === '1' ? 'bg-success' : 'bg-danger';

                $('#ready th').eq(event.position).addClass(color);
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
