<template>
    <div class="mb-1" v-show="state != '0'">
        <div v-for="(card, index ) in this.cards"
             class="p-card"
             :style="'position: absolute; left:' + (50 + index * 25) + 'px' "
             :key="index"
             :class="card.suit + card.strength"
             :data-suit="card.suit"
             :data-strength="card.strength" @click="sendCard" :id="index">

        </div>
        <div id="suits" style="position: absolute; display: none; left: 80px; bottom: 180px;">
            <button type="button" class="btn btn-outline-danger" data-suit="hearts" @click="jokSuit">გული</button>
            <button type="button" class="btn btn-outline-dark" data-suit="clubs" @click="jokSuit">ჯვარი</button>
            <button type="button" class="btn btn-outline-danger" data-suit="diamonds" @click="jokSuit">აგური</button>
            <button type="button" class="btn btn-outline-dark" data-suit="spades" @click="jokSuit">ყვავი</button>
            <button type="button" class="btn btn-light" @click="hideS">x</button>
        </div>
        <div id="jok" style="position: absolute; display: none; left: 80px; bottom: 140px;">
            <button type="button" class="btn btn-primary" data-action="magali" @click="jok">მაღალი</button>
            <button type="button" class="btn btn-info" data-action="caigos" @click="jok">წაიღოს</button>
            <button type="button" class="btn btn-primary" data-action="mojokra" @click="jok">მოჯოკრა</button>
            <button type="button" class="btn btn-info" data-action="nije" @click="jok">ნიჟე</button>
            <button type="button" class="btn btn-light" @click="hideJ">x</button>
        </div>
        <div id="setTrump" style="position: absolute; display: none; left: 80px; bottom: 140px;">
            <button type="button" class="btn btn-outline-danger" data-strength="14" data-suit="hearts" @click="trump">გული</button>
            <button type="button" class="btn btn-outline-dark" data-strength="14" data-suit="clubs" @click="trump">ჯვარი</button>
            <button type="button" class="btn btn-outline-danger" data-strength="14" data-suit="diamonds" @click="trump">აგური</button>
            <button type="button" class="btn btn-outline-dark" data-strength="14" data-suit="spades" @click="trump">ყვავი</button>
            <button type="button" class="btn btn-outline-secondary" data-strength="16" data-suit="black_joker" @click="trump">ბეზი</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['playerId', 'state', 'gameId', 'turn', 'pos'],

        data() {
            return {
                cards: [],
                card: {},
                cardId: 0
            }
        },

        created() {
            window.Echo.private('player.' + this.playerId)
                .listen('CardsEvent', event => {
                    let cards = event.cards;
                    cards.sort( (a, b) => {
                        let aValue = this.cardValue(a);
                        let bValue = this.cardValue(b);
                        return aValue - bValue;
                    });
                    this.cards = cards;
                    if (event.trump) {
                        document.getElementById('setTrump').style.display = 'block';
                    }
                });
        },

        methods: {
            sendCard(event) {
                if (this.turn !== this.pos)  {
                    console.log("wrong turn");
                    return;
                }
                let str = event.target.getAttribute('data-strength');
                let s = event.target.getAttribute('data-suit');
                this.card = {
                    card: {
                        strength: str,
                        suit: s,
                    }
                };
                if (/joker$/.test(event.target.getAttribute('data-suit'))) {
                    document.getElementById('jok').style.display = 'block';
                    this.cardId = event.target.id;
                } else {
                    axios.post('/card/games/' + this.gameId, this.card);
                    this.cards.splice(event.target.id, 1);
                }

            },

            jok(event) {
                let action = event.target.getAttribute('data-action');
                this.card.action = action;
                if (action === 'magali' || action === 'caigos') {
                    document.getElementById('suits').style.display = 'block';
                } else {
                    axios.post('/card/games/' + this.gameId, this.card);
                    this.cards.splice(this.cardId, 1);
                }
                this.hideJ();
            },

            jokSuit(event) {
                this.card.actionsuit = event.target.getAttribute('data-suit');
                axios.post('/card/games/' + this.gameId, this.card);
                this.cards.splice(this.cardId, 1);
                this.hideS();
            },

            trump(event) {
                let str = event.target.getAttribute('data-strength');
                let s = event.target.getAttribute('data-suit');
                this.card = {
                    strength: str,
                    suit: s
                }

                axios.post('/trump/games/' + this.gameId, this.card);
                document.getElementById('setTrump').style.display = 'none';
            },

            hideS()
            {
                document.getElementById('suits').style.display = 'none';
            },

            hideJ()
            {
                document.getElementById('jok').style.display = 'none';
            },

            cardValue(card) {
                if (card['suit'] === 'hearts') return card['strength'];
                if (card['suit'] === 'clubs') return 20 + card['strength'];
                if (card['suit'] === 'diamonds') return 40 + card['strength'];
                if (card['suit'] === 'spades') return 60 + card['strength'];
                if (card['suit'] === 'black_joker') return 80 + card['strength'];
                if (card['suit'] === 'color_joker') return 100 + card['strength'];
            }
        }
    }
</script>

<style>

</style>
