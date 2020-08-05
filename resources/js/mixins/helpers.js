export default {
    data() {
        return {
            windowWidth: window.innerWidth,
            ppm: [],
            timer: 10,
            nextTurn: 4
        }
    },

    computed: {
        showStart() {
            return App.user.id === this.game.user_id && this.game.state === 'start' && this.game.players.length === 4;
        },

        passwordProtected() {
            return this.game.state === 'start' && this.game.password !== null && this.game.players.length < 4;
        }
    },

    created() {
        this.playerPositionsMap();
        this.showCards(this.initialCards, true);
    },

    mounted() {
        window.onresize = () => {
            this.windowWidth = window.innerWidth
        };
        $('body').addClass('bg-success');
        if (this.game.state === 'trump' && this.turn) {
            $('#suits').removeClass('d-none');
        }
    },

    // es metodi acentrebs kartebs negativ marginebit viewportis mixedvit
    methods: {
        getMargin(n) {
            let smallMargins = [
                {even: -47, odd: -34.5},
                {even: -27.5, odd: -22.5},
                {even: -21.5, odd: -16.5},
                {even: -27.5, odd: -22.5},
            ];

            let bigMargins = [
                {even: -47, odd: -34.5},
                {even: -59.5, odd: -47},
                {even: -47, odd: -34.5},
                {even: -59.5, odd: -47},
            ];

            let count = this.players[this.ppm[n]].cards.length;

            if (count % 2 === 0) {
                let base = this.windowWidth >= 576 ? bigMargins[n].even : smallMargins[n].even;
                return base - (count - 2) / 2 * this.marginStep(n);
            } else {
                let base = this.windowWidth >= 576 ? bigMargins[n].odd : smallMargins[n].odd;
                return base - (count - 1) / 2 * this.marginStep(n);
            }
        },

        marginStep(n) {
            return n === 0 || this.windowWidth >= 576 ? 25 : 10;
        },

        playerPositionsMap() {
            let position;
            for (let i = 0; i < 4; i++) {
                if (App.user.id === this.game.players[i].user_id) {
                    position = i;
                    break;
                }
            }

            switch(position) {
                case 1:
                    this.ppm = [1, 2, 3, 0];
                    break;
                case 2:
                    this.ppm = [2, 3, 0, 1];
                    break;
                case 3:
                    this.ppm = [3, 0, 1, 2];
                    break;
                default:
                    this.ppm = [0, 1, 2, 3];
                    break;
            }
        },

        getUsername(p) {
            return this.game.players[this.ppm[p]] ? this.game.players[this.ppm[p]].user.username : '...';
        },

        playedCard(n) {
            let player = this.game.players[this.ppm[n]];
            if (player && player.card != null) {
                if (player.card['suit'].indexOf('joker') !== -1) {
                    return player.card['suit'] + '16';
                } else {
                    return player.card['suit'] + player.card['strength'];
                }
            }
        },

        cardsZIndex(n) {
            let player = this.game.players[this.ppm[n]];
            if (player && player.card != null) {
                for (let idx in this.game.cards) {
                    if (this.game.cards[idx]['suit'] == player.card['suit'] &&
                        this.game.cards[idx]['strength'] == player.card['strength']) {
                        return idx;
                    }
                }
            }
        },

        active(n) {
            let state = ['trump', 'call', 'card'];
            if (state.indexOf(this.game.state) >= 0) {
                return this.ppm[n] === this.game.turn ? 'border-warning' : '';
            } else {
                return '';
            }
        },

        playedCardAction(n) {
            let player = this.game.players[this.ppm[n]];
            if (player && player.card != null && player.card['action'] !== undefined) {
                return true;
            }
        },

        suitColor(suit) {
            if (suit === 'hearts' || suit === 'diamonds') {
                return 'text-danger';
            } else {
                return '';
            }
        },

        canKickUser(n) {
            if (App.user.id === this.game.user_id) {
                if (this.game.state === 'start' && this.game.players[this.ppm[n]] !== undefined) {
                    return true;
                }
            }

            return false;
        },

        kick(n) {
            axios.post('/kick/games/' + this.game.id, {position: this.ppm[n]})
                .then(response => {
                    this.game.players = response.data;
                    this.playerPositionsMap();
                });
        },

        goToLobby() {
            window.location = '/lobby';
        },

        showChat() {
            if (this.windowWidth < 768) {
                $('#play-table').addClass('d-none');
                $('#chat-wrapper').removeClass('d-none');
                $('.close-w').removeClass('d-none');
            } else {
                $('#scoreboard').removeClass('d-md-block');
                $('#chat-wrapper').removeClass('d-none');
                $('#btn-scoreboard').removeClass('d-md-none');
                $('#btn-chat').addClass('d-none');
            }
        },

        showScoreboard() {
            if (this.windowWidth < 768) {
                $('#play-table').addClass('d-none');
                $('#scoreboard').removeClass('d-none');
                $('.close-w').removeClass('d-none');
            } else {
                $('#chat-wrapper').addClass('d-none');
                $('#scoreboard').addClass('d-md-block');
                $('#btn-scoreboard').addClass('d-md-none');
                $('#btn-chat').removeClass('d-none');
            }
        },

        closeW() {
            $('#play-table').removeClass('d-none');
            $('#chat-wrapper').addClass('d-none');
            $('#scoreboard').addClass('d-none');
            $('.close-w').addClass('d-none');
        },

        copyLink() {
            let link = document.getElementById("table-link");
            link.select();
            link.setSelectionRange(0, 99999);
            document.execCommand("copy");
        }
    }
}
