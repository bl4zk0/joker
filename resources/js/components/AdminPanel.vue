<template>
    <div class="panel-wrapper">
        <button type="button" class="btn btn-danger" v-show="! showPanel" @click="showPanel = !showPanel">
            <i class="fas fa-user-cog"></i> Admin-panel
        </button>
        <div class="border rounded p-2 bg-white" v-show="showPanel">
            <button type="button" class="btn btn-danger mb-2" @click="start">თამაშის დაწყება</button>
            <button type="button" class="btn btn-dark float-right mb-2" @click="showPanel = !showPanel">
                <i class="fas fa-times"></i>
            </button>
            <button type="button" class="btn btn-warning mb-2" @click="addBot">bot++</button>
            <form @submit.prevent="sendCards">
                <div class="form-group">
                    <label for="position">პოზიცია</label>
                    <select id="position" class="custom-select" v-model="selectedPosition">
                        <option value="0">1</option>
                        <option value="1">2</option>
                        <option value="2">3</option>
                        <option value="3">4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cards">კარტები</label>
                    <select multiple class="form-control" id="cards" v-model="selectedCards">
                        <option value="0" class="text-danger" selected>color_joker</option>
                        <option value="1">black_joker</option>
                        <option value="2" class="text-danger">A &hearts;</option>
                        <option value="3">A &clubs;</option>
                        <option value="4" class="text-danger">A &diams;</option>
                        <option value="5">A &spades;</option>
                    </select>
                </div>
                <button class="btn btn-success">ჩაწყობა</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: ['gameId'],

    data() {
        return {
            id: this.gameId,
            showPanel: false,
            selectedCards: [],
            selectedPosition: 0,
            cards: [
                {strength: 16, suit: 'color_joker'},
                {strength: 16, suit: 'black_joker'},
                {strength: 14, suit: 'hearts'},
                {strength: 14, suit: 'clubs'},
                {strength: 14, suit: 'diamonds'},
                {strength: 14, suit: 'spades'}
            ]
        }
    },

    methods: {
        start() {
            axios.post('/admin/start/games/' + this.id)
                .then(response => {
                    console.log('OK');
                })
                .catch(error => {
                    console.log(error.message);
                });
        },

        addBot() {
            axios.post('/admin/addbot/games/' + this.id)
                .then(response => {
                    console.log('OK');
                })
                .catch(error => {
                    console.log(error.message);
                });
        },

        sendCards() {
            let data = { position: this.selectedPosition, cards: [] };
            for (let idx of this.selectedCards) {
                data.cards.push(this.cards[idx]);
            }

            axios.post('/admin/cards/games/' + this.id, data)
                .then(response => {
                    console.log('OK');
                })
                .catch(error => {
                    console.log(error.message);
                });
        }
    }
}
</script>

<style scoped>
.panel-wrapper {
    position: fixed;
    bottom: 8px;
    left: 8px;
    width: 250px;
    z-index: 1000;
}
</style>
