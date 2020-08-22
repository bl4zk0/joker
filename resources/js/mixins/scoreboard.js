export default {
    props: ['initialPlayers', 'initialScores', 'penalty'],

    computed: {
        players() {
            return this.initialPlayers
        },

        scores() {
            return this.initialScores
        }
    },

    methods: {
        showScores(p, q, n) {
            if (this.scores[p] && this.scores[p].data[`q_${q}`][n]) {
                let call = Number(this.scores[p].data[`q_${q}`][n].call) === 0 ? '-' : this.scores[p].data[`q_${q}`][n].call;
                let result = this.scores[p].data[`q_${q}`][n].result ? this.scores[p].data[`q_${q}`][n].result : '';
                result = result < 0 ? 'I--I' : result;
                let txt = `${call} ${result}`;
                if (this.scores[p].data[`q_${q}`][n].c === 'r') {
                    return `<s class="text-danger">${txt}</s>`;
                } else if (this.scores[p].data[`q_${q}`][n].c === 'y') {
                    return `<span class="text-warning">${txt}</span>`;
                } else {
                    return txt;
                }
            } else {
                return '';
            }
        },

        showResult(p, q, n) {
            if (this.scores[p] && this.scores[p].data[`q_${q}`][n]) {
                let result = this.scores[p].data[`q_${q}`][n].result;
                result = (result / 100).toFixed(1);
                return `<strong>${result}</strong>`;
            } else {
                return '';
            }
        }
    },

}
