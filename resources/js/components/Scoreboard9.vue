<template>
    <div id="scoreboard" class="d-none d-md-block px-2">
        <table class="table table-bordered text-white">
            <thead>
            <tr class="bg-primary">
                <th scope="col">#</th>
                <th scope="col">{{ this.players[0] ? this.players[0].user.username : '...'}}</th>
                <th scope="col">{{ this.players[1] ? this.players[1].user.username : '...'}}</th>
                <th scope="col">{{ this.players[2] ? this.players[2].user.username : '...'}}</th>
                <th scope="col">{{ this.players[3] ? this.players[3].user.username : '...'}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">9</th>
                <td v-html="showScores(0, 0)"></td>
                <td v-html="showScores(1, 0)"></td>
                <td v-html="showScores(2, 0)"></td>
                <td v-html="showScores(3, 0)"></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td v-html="showScores(0, 1)"></td>
                <td v-html="showScores(1, 1)"></td>
                <td v-html="showScores(2, 1)"></td>
                <td v-html="showScores(3, 1)"></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td v-html="showScores(0, 2)"></td>
                <td v-html="showScores(1, 2)"></td>
                <td v-html="showScores(2, 2)"></td>
                <td v-html="showScores(3, 2)"></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td v-html="showScores(0, 3)"></td>
                <td v-html="showScores(1, 3)"></td>
                <td v-html="showScores(2, 3)"></td>
                <td v-html="showScores(3, 3)"></td>
            </tr>
            <tr class="bg-info">
                <th scope="row">&Sigma;</th>
                <td v-html="showResult(0, 4)"></td>
                <td v-html="showResult(1, 4)"></td>
                <td v-html="showResult(2, 4)"></td>
                <td v-html="showResult(3, 4)"></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td v-html="showScores(0, 5)"></td>
                <td v-html="showScores(1, 5)"></td>
                <td v-html="showScores(2, 5)"></td>
                <td v-html="showScores(3, 5)"></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="bg-info">
                <th scope="row">&Sigma;</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="bg-info">
                <th scope="row">&Sigma;</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="bg-info">
                <th scope="row">&Sigma;</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import scoreboard from "../mixins/scoreboard";

    export default {
        mixins: [scoreboard]
    }
</script>
