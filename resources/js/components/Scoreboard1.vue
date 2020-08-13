<template>
    <div id="scoreboard" class="d-none d-md-block px-2">
        <table class="table table-bordered text-white">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">#</th>
                    <th scope="col">{{ this.players[0] ? this.players[0].username : '...'}}</th>
                    <th scope="col">{{ this.players[1] ? this.players[1].username : '...'}}</th>
                    <th scope="col">{{ this.players[2] ? this.players[2].username : '...'}}</th>
                    <th scope="col">{{ this.players[3] ? this.players[3].username : '...'}}</th>
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
                    <th scope="row">5</th>
                    <td v-text="showScores(0, 4)"></td>
                    <td v-text="showScores(1, 4)"></td>
                    <td v-text="showScores(2, 4)"></td>
                    <td v-text="showScores(3, 4)"></td>
                </tr>
                <tr>
                    <th scope="row">6</th>
                    <td v-text="showScores(0, 5)"></td>
                    <td v-text="showScores(1, 5)"></td>
                    <td v-text="showScores(2, 5)"></td>
                    <td v-text="showScores(3, 5)"></td>
                </tr>
                <tr>
                    <th scope="row">7</th>
                    <td v-text="showScores(0, 6)"></td>
                    <td v-text="showScores(1, 6)"></td>
                    <td v-text="showScores(2, 6)"></td>
                    <td v-text="showScores(3, 6)"></td>
                </tr>
                <tr>
                    <th scope="row">8</th>
                    <td v-text="showScores(0, 7)"></td>
                    <td v-text="showScores(1, 7)"></td>
                    <td v-text="showScores(2, 7)"></td>
                    <td v-text="showScores(3, 7)"></td>
                </tr>
                <tr class="bg-info">
                    <th scope="row">&Sigma;</th>
                    <td v-text="showResult(0, 8)"></td>
                    <td v-text="showResult(1, 8)"></td>
                    <td v-text="showResult(2, 8)"></td>
                    <td v-text="showResult(3, 8)"></td>
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
                    <th scope="row">8</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">7</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">6</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">5</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">4</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
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
