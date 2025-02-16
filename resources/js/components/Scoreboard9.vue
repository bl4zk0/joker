<template>
    <div id="scoreboard" class="d-none d-md-block px-2">
        <table class="table table-bordered">
            <thead>
                <tr class="table-info">
                    <th scope="col">#</th>
                    <th scope="col" v-for="p in [0,1,2,3]" v-text="players[p] ? players[p].username : '#'+(p+1)"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(n,idx) in [0,1,2,3,4,0,1,2,3,4,0,1,2,3,4,0,1,2,3,4]" 
                    :class="n === 4 ? 'table-info' : ''">
                    <th scope="row">{{ n === 4 ? '&Sigma;' : 9 }}</th>
                    <td v-for="p in [0,1,2,3]" 
                        v-html="n === 4 ? showResult(p , Math.ceil((idx + 1) / 5), n) : showScores(p , Math.ceil((idx + 1) / 5), n )"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="table-info">
                    <th scope="row">$</th>
                    <td colspan="4" class="fw-bold">{{ this.penalty }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    import scoreboard from "../mixins/scoreboard";

    export default {
        mixins: [scoreboard]
    }
</script>
