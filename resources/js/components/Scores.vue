<template>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{ this.players[0] ? this.players[0].user.name : '...'}}</th>
                <th scope="col">{{ this.players[1] ? this.players[1].user.name : '...'}}</th>
                <th scope="col" v-text="this.players[2] ? this.players[2].user.name : '...'"></th>
                <th scope="col" v-text="this.players[3] ? this.players[3].user.name : '...'"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td v-text="showScores(0, 0)"></td>
                <td v-text="showScores(1, 0)"></td>
                <td v-text="showScores(2, 0)"></td>
                <td v-text="showScores(3, 0)"></td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td v-text="showScores(0, 1)"></td>
                <td v-text="showScores(1, 1)"></td>
                <td v-text="showScores(2, 1)"></td>
                <td v-text="showScores(3, 1)"></td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td v-text="showScores(0, 2)"></td>
                <td v-text="showScores(1, 2)"></td>
                <td v-text="showScores(2, 2)"></td>
                <td v-text="showScores(3, 2)"></td>
            </tr>
            <tr>
                <th scope="row">4</th>
                <td v-text="showScores(0, 3)"></td>
                <td v-text="showScores(1, 3)"></td>
                <td v-text="showScores(2, 3)"></td>
                <td v-text="showScores(3, 3)"></td>
            </tr>
            <tr>
                <th scope="row">&Sigma;</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
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
            }
        },

        mounted() {
            //console.log(this.players);
        }
    }
</script>
