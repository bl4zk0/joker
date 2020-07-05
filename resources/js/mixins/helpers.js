export default {
    data() {
        return {
            windowWidth: window.innerWidth,
            ppm: [],
            timer: 10
        }
    },

    computed: {
        showStart() {
            return App.user.id === this.game.user_id && this.game.state === 'start' && this.game.players.length === 4;
        },
    },

    created() {
        this.playerPositionsMap();
        this.showCards(this.initialCards);
    },

    mounted() {
        window.onresize = () => {
            this.windowWidth = window.innerWidth
        };
        $('body').addClass('bg-success');
    },

    // es metodi acentrebs kartebs negativ marginebit viewportis mixedvit
    methods: {
        getMargin(n) {
            let smallMargins = [
                {even: -47, odd: -34.5},
                {even: -27.5, odd: -22.5},
                {even: -21.5, odd: -16.5},
            ];

            let bigMargins = [
                {even: -47, odd: -34.5},
                {even: -59.5, odd: -47},
                {even: -47, odd: -34.5},
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
        }
    }
}
