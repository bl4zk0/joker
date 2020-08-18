<template>
    <div id="scoreboard" class="d-none d-md-block px-2">
        <table class="table table-bordered text-white">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">#</th>
                    <th scope="col" v-for="p in [0,1,2,3]" v-text="players[p] ? players[p].username : '...'"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="n in [0,1,2,3,4,5,6,7]">
                    <th scope="row">{{ n + 1 }}</th>
                    <td v-text="showScores(0, n)"></td>
                    <td v-text="showScores(1, n)"></td>
                    <td v-text="showScores(2, n)"></td>
                    <td v-text="showScores(3, n)"></td>
                </tr>
                <tr class="bg-info">
                    <th scope="row">&Sigma;</th>
                    <td v-text="showResult(0, 8)"></td>
                    <td v-text="showResult(1, 8)"></td>
                    <td v-text="showResult(2, 8)"></td>
                    <td v-text="showResult(3, 8)"></td>
                </tr>
                <tr v-for="n in [9,10,11,12]">
                    <th scope="row">9</th>
                    <td v-text="showScores(0, n)"></td>
                    <td v-text="showScores(1, n)"></td>
                    <td v-text="showScores(2, n)"></td>
                    <td v-text="showScores(3, n)"></td>
                </tr>
                <tr class="bg-info">
                    <th scope="row">&Sigma;</th>
                    <td v-text="showResult(0, 13)"></td>
                    <td v-text="showResult(1, 13)"></td>
                    <td v-text="showResult(2, 13)"></td>
                    <td v-text="showResult(3, 13)"></td>
                </tr>
                <tr v-for="n in [14,15,16,17,18,19,20,21]">
                    <th scope="row">{{ 22 - n }}</th>
                    <td v-text="showScores(0, n)"></td>
                    <td v-text="showScores(1, n)"></td>
                    <td v-text="showScores(2, n)"></td>
                    <td v-text="showScores(3, n)"></td>
                </tr>
                <tr class="bg-info">
                    <th scope="row">&Sigma;</th>
                    <td v-text="showResult(0, 22)"></td>
                    <td v-text="showResult(1, 22)"></td>
                    <td v-text="showResult(2, 22)"></td>
                    <td v-text="showResult(3, 22)"></td>
                </tr>
                <tr v-for="n in [23,24,25,26]">
                    <th scope="row">9</th>
                    <td v-text="showScores(0, n)"></td>
                    <td v-text="showScores(1, n)"></td>
                    <td v-text="showScores(2, n)"></td>
                    <td v-text="showScores(3, n)"></td>
                </tr>
                <tr class="bg-info">
                    <th scope="row">&Sigma;</th>
                    <td v-text="showResult(0, 27)"></td>
                    <td v-text="showResult(1, 27)"></td>
                    <td v-text="showResult(2, 27)"></td>
                    <td v-text="showResult(3, 27)"></td>
                </tr>
            </tbody>
        </table>
        <div><span class="badge badge-danger">I--I {{ this.penalty }}</span></div>
    </div>
</template>

<script>
    import scoreboard from "../mixins/scoreboard";

    export default {
        mixins: [scoreboard]
    }
</script>
