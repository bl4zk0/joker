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
                <tr v-for="n in 20" :class="n % 5 === 0 ? 'bg-info' : ''">
                    <th scope="row">{{ n % 5 === 0 ? '&Sigma;' : 9 }}</th>
                    <td v-for="p in [0,1,2,3]" v-html="n % 5 === 0 ? showResult(p , n - 1) : showScores(p , n - 1)"></td>
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
