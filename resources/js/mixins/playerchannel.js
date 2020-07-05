export default {
    data() {
        return {
            dealtCards: [],
            setTrump: false,
        }
    },

    created() {
        window.Echo.private('user.' + App.user.id)
            .listen('CardDealEvent', event => {
                console.log(event);
                let cards = event.cards;
                cards.sort( (a, b) => {
                    let aValue = this.cardSortValue(a);
                    let bValue = this.cardSortValue(b);
                    return aValue - bValue;
                });
                this.dealtCards = cards;
                this.setTrump = event.trump;
                this.showCards(this.dealtCards);
            });
    },

    methods: {
        showCards(cards) {
            if (this.game.state === 'void' || this.game.state === 'ready' || cards === null) return;

            // TODO: mokled tu dadiskonektda an daarefresha mag dros state gvakvs mosaxodi

            this.players[this.ppm[0]].cards = cards;
            this.players[this.ppm[1]].cards = Array.from(new Array(cards.length).keys());
            this.players[this.ppm[2]].cards = Array.from(new Array(cards.length).keys());
            this.players[this.ppm[3]].cards = Array.from(new Array(cards.length).keys());

            if (this.setTrump) {
                $('#suits').removeClass('d-none');
            }
        },

        cardSortValue(card) {
            if (card['suit'] === 'hearts') return card['strength'];
            if (card['suit'] === 'clubs') return 20 + card['strength'];
            if (card['suit'] === 'diamonds') return 40 + card['strength'];
            if (card['suit'] === 'spades') return 60 + card['strength'];
            if (card['suit'] === 'black_joker') return 80 + card['strength'];
            if (card['suit'] === 'color_joker') return 100 + card['strength'];
        }
    }
}
