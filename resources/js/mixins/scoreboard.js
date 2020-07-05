export default {
    props: ['initialPlayers'],

    computed: {
        players() {
            return this.initialPlayers
        }
    },

    methods: {
        showScores(p, n) {
            if (this.players[p] && this.players[p].scores[n]) {
                let call = this.players[p].scores[n].call === 0 ? '-' : this.players[p].scores[n].call;
                let result = this.players[p].scores[n].result ? this.players[p].scores[n].result : '';
                result = result < 0 ? 'I--I' : result;
                return call + ' ' + result;
            } else {
                return '';
            }
        },

        showResult(p, n) {
            if (this.players[p] && this.players[p].scores[n]) {
                let result = this.players[p].scores[n].result;
                result = (result / 100).toFixed(1);
                return result;
            } else {
                return '';
            }
        }
    },

}
