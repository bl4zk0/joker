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
                let txt = call + ' ' + result;
                if (this.players[p].scores[n].color === 'red') {
                    return `<s class="text-danger">${txt}</s>`;
                } else if (this.players[p].scores[n].color === 'yellow') {
                    return `<span class="text-warning">${txt}</span>`;
                } else {
                    return txt;
                }
            } else {
                return '';
            }
        },

        showResult(p, n) {
            if (this.players[p] && this.players[p].scores[n]) {
                let result = this.players[p].scores[n].result;
                result = (result / 100).toFixed(1);
                return `<strong>${result}</strong>`;
            } else {
                return '';
            }
        }
    },

}
