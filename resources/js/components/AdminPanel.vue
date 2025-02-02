<template>
    <div class="position-fixed bottom-0 mb-1" style="left: 65px;">
        <button type="button" class="btn btn-success" v-show="! showPanel" @click="showPanel = !showPanel">
            <i class="fas fa-user-cog"></i> Admin panel
        </button>
        <div class="border bg-dark rounded p-2" v-show="showPanel">
            <button type="button" class="btn btn-secondary mb-2" @click="showPanel = !showPanel">
                <i class="fas fa-times"></i>
            </button>
            <button type="button" class="btn btn-warning mb-2" @click="addBot">bot++</button>
            <button type="button" class="btn btn-danger mb-2" @click="start">{{ lang('Start Game') }}</button>
            <form @submit.prevent="sendCards">
                <div class="mb-2">
                    <label for="position" class="form-label">{{ lang('Position') }}</label>
                    <select id="position" class="form-select" v-model="selectedPosition">
                        <option value="0">1</option>
                        <option value="1">2</option>
                        <option value="2">3</option>
                        <option value="3">4</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="cards" class="form-label">{{ lang('Cards') }}</label>
                    <select multiple class="form-control" id="cards" v-model="selectedCards">
                        <option value="0" class="text-danger" selected>color_joker</option>
                        <option value="1">black_joker</option>
                        <option value="2" class="text-danger">A &hearts;</option>
                        <option value="3">A &clubs;</option>
                        <option value="4" class="text-danger">A &diams;</option>
                        <option value="5">A &spades;</option>
                    </select>
                </div>
                <button class="btn btn-success">{{ lang('Cheat') }}</button>
            </form>
        </div>
    </div>
</template>

<script>
import translate from '../mixins/translate';

export default {
    props: ['gameId'],
    mixins: [translate],

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
