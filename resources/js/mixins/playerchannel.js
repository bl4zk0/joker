export default {
    data() {
        return {
            dealtCards: [],
            setTrump: false,
            lastCards: [],
            lastCardsStorage: []
        }
    },

    created() {
        Echo.private('user.' + App.user.id)
            .listen('CardDealEvent', event => {
                console.log('CardDealEvent');
                this.dealtCards = event.cards;
                this.setTrump = event.trump;

                setTimeout(()=> {
                    this.showCards(this.dealtCards, false);
                    this.lastCards = [];
                }, 1000);
            });
    },

    methods: {
        showCards(cards, initial) {
            if (this.game.state === 'void' || this.game.state === 'ready' || this.game.state === 'start') return;

            cards = cards === null ? [] : cards;
            cards.sort( (a, b) => {
                let aValue = this.cardSortValue(a);
                let bValue = this.cardSortValue(b);
                return aValue - bValue;
            });
            let player = this.players[this.ppm[0]];
            player.cards = cards;
            if (this.game.state === 'card') {
                let scoresL = this.game.players[this.ppm[0]].scores.length - 1;
                let take = this.game.players[this.ppm[0]].scores[scoresL].take;
                player.takenCards = Array.from(new Array(take).keys());

            } else {
                player.takenCards = [];
            }

            for (let i = 1; i < 4; i++) {
                let cardsL;
                let scoresLL;
                if (initial) {
                    cardsL = this.game.players[this.ppm[i]].cards_count;
                    cardsL = this.game.state === 'trump' ? 3 : cardsL;
                    if (this.game.state === 'card') {
                        scoresLL = this.game.players[this.ppm[i]].scores.length - 1;
                        let takee = this.game.players[this.ppm[i]].scores[scoresLL].take;
                        this.players[this.ppm[i]].takenCards = Array.from(new Array(takee).keys());
                    }
                } else {
                    cardsL = cards.length;
                    this.players[this.ppm[i]].takenCards = [];
                }
                this.players[this.ppm[i]].cards = Array.from(new Array(cardsL).keys());
            }

            if (this.setTrump) {
                $('#suits').removeClass('d-none');
            }

            this.dealtCards = [];
        },

        cardSortValue(card) {
            if (card['suit'] === 'hearts') return card['strength'];
            if (card['suit'] === 'clubs') return 20 + card['strength'];
            if (card['suit'] === 'diamonds') return 40 + card['strength'];
            if (card['suit'] === 'spades') return 60 + card['strength'];
            if (card['suit'] === 'black_joker') return 80 + card['strength'];
            if (card['suit'] === 'color_joker') return 100 + card['strength'];
        },

        canPlay(card) {
            if (this.game.cards.length == 0) {
                if (card['strength'] != 16 || (card['action'] == 'magali' || card['action'] == 'caigos')) {
                    return true;
                } else {
                    return false;
                }
            } else {
                let suit = this.game.cards[0]['actionsuit'] ? this.game.cards[0]['actionsuit'] : this.game.cards[0]['suit'];
                let trump = this.game.trump['strength'] == 16 ? 'bez' : this.game.trump['suit'];

                if (this.game.cards[0]['action'] == 'magali') {
                    if (this.isHighestSuitInCards(card, suit)) return true;
                } else if (card['suit'] == suit) return true;

                if (card['action'] == 'mojokra' || card['action'] == 'kvevidan') return true;

                if (! this.suitInCards(suit) && (card['suit'] == trump)) return true;

                if (! this.suitInCards(suit) && ! this.suitInCards(trump)) return true;
            }

            this.cardState = true;
            return false;
        },

        highestCard() {
            let suit = null;
            let cards = this.game.cards;
            let trump = this.game.trump;

            if (cards[0].hasOwnProperty('action')) {
                if (this.suitInGameCards(trump['suit']) && cards[0]['actionsuit'] != trump['suit']) {
                    cards.shift();
                } else if (cards[0]['action'] == 'caigos' && this.suitInGameCards(cards[0]['actionsuit'])) {
                    cards.shift();
                }
            }

            if (trump['strength'] != 16 && this.suitInGameCards(trump['suit'])) {
                suit = trump['suit'];
            } else {
                suit = cards[0]['suit'];
            }

            cards = cards.filter(function(c) {
                return c['suit'] == suit || c['strength'] == 16 || c['strength'] == 17;
            });

            cards.sort(function (a,b) {
                return b['strength'] - a['strength'];
            });

            return cards[0];
        },

        isHighestSuitInCards(card, suit) {
            let cards = this.players[this.ppm[0]].cards.filter(function(c) {
                return c.suit == suit;
            });

            if (cards.length == 0) return false;

            cards.sort(function (a, b) {
                return b['strength'] - a['strength'];
            });

            let highestCard = cards[0];

            if (highestCard['strength'] == card['strength'] && highestCard['suit'] == card['suit']) return true;

            return false;
        },

        suitInCards(suit) {
            for (let card of this.players[this.ppm[0]].cards) {
                if (card['suit'] == suit) return true;
            }

            return false;
        },

        suitInGameCards(suit) {
            if (suit['strength'] == 16) return false;
            for (let card of this.game.cards) {
                if (card['suit'] == suit) return true;
            }

            return false;
        },

        hideCards(take) {
            if (take !== false) {
                this.nextTurn = take;
                setTimeout(() => {
                    this.lastCards = this.lastCardsStorage;
                    this.lastCardsStorage = [];
                    for (let player of this.game.players) {
                        player.card = null;
                    }
                    this.game.turn = Number(this.nextTurn);
                    this.players[take].takenCards.push(1);
                    this.game.cards = [];
                    this.playState = true;
                }, 900);
            } else {
                this.game.turn = this.game.turn === 3 ? 0 : this.game.turn + 1;
                this.playState = true;
            }
        },

        checkTake() {
            if (this.game.cards.length === 4) {
                if (this.game.cards[3]['action'] === 'mojokra') {
                    this.game.cards[3]['strength'] = 17;
                }

                if (this.game.cards[3]['action'] === 'kvevidan') {
                    this.game.cards[3]['strength'] = 1;
                }

                let highestCard = this.highestCard();
                for (let idx in this.game.players) {
                    if (highestCard['suit'] == this.game.players[idx].card['suit'] &&
                        highestCard['strength'] == this.game.players[idx].card['strength']) {
                        return idx;
                    }
                }
            }

            return false;
        }
    }
}
