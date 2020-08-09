<?php

namespace App;

use App\Events\CardDealEvent;
use App\Events\CardPlayEvent;
use App\Events\StartGameEvent;
use App\Events\UpdateGameEvent;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /*
     * TODO: check broadcasted game properties use model resources??
     * throttle join game request
     */
    protected $guarded = [];
    protected $with = ['creator', 'players'];
    protected $hidden = ['password'];
    protected $appends = ['except'];
    protected $casts = [
        'cards' => 'array',
        'kicked_users' => 'array',
        'trump' => 'array',
        'ready' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function($game) {
            $game->addPlayer($game->creator, 0);
        });
    }

    public function start()
    {
        $cards = $this->setPositions();

        $this->deal();

        $this->refresh();

        broadcast(new StartGameEvent($this, $cards));
    }

    /**
     * tu chamosul kartebshi jokeria da chamosuli kartma mojokra
     * kartis strength gavzardot 17-ze rom cagebisas gamoitvalos
     * tu nije gaaketa strength shevamcirot 1-ze rom ar caigos
     * danarchen shemtxvevashi davamatot karti rogorc aris
     * @param $card
     */
    public function addCard($card, $player)
    {
        if ($this->jokInCards() && isset($card['action']) && $card['action'] == 'mojokra') {
            $card['strength'] = 17;
        } elseif (isset($card['action']) && $card['action'] == 'kvevidan') {
            $card['strength'] = 1;
        }

        $cards = $this->cards;
        array_push($cards, $card);
        $this->cards = $cards;
        $this->save();

        $player->update(['card' => $card]);

        $this->refresh();

        return $card;
    }

    /**
     * tu 4 ive motamashe chamovida eseigi unda gavigot vin caigo
     * shevadarot motamasheebis bolo chamosuli kartebi yvelaze magal karts
     * vinc caigebs bazashi davafiksirot da chamosvlis poziciashi davamatot
     *
     * tu yvela karti chamosuli shevamocmot bazashi motamasheebis kartebi unda iyos null
     * mashin gamovtvalot scores da shevamocmot tu morcha pulka an tamashi
     *
     * sxva shemtxvevashi ubralod chamomsvlelis pozicia ganvaaxlot
     */
    public function checkTake()
    {
        if (count($this->cards) == 4) {
            $highestCard = $this->highestCard();
            $position = -1;
            foreach ($this->players as $player) {
                if ($player->card == $highestCard) {
                    $player->scores()->increment('take');
                    $this->update(['turn' => $player->position]);
                    $position = $player->position;
//                    broadcast (new CardPlayEvent(
//                        $this->id,
//                        auth()->user()->player->position,
//                        $card,
//                        $player->position))->toOthers();
                }
                $player->update(['card' => null]);
            }

            return $position;

//            if (empty($this->players()->pluck('cards')->whereNotNull()->toArray())) {
//                $this->calcScoresAfterHand();
//                // check end of the game;
//                // tu tamashi damtavrda true gvaqvs return-shi da unda davamtavrot tamashi...
//                $this->checkEnd();
//            }
        } else {
            $this->updateTurn();
            return false;
            //broadcast (new CardPlayEvent($this->id, auth()->user()->player->position, $card))->toOthers();
        }
    }

    public function checkEndOfTheHand()
    {
        if (empty($this->players()->pluck('cards')->whereNotNull()->toArray())) {
            $this->calcScoresAfterHand();
            // check end of the game;
            // tu tamashi damtavrda true gvaqvs return-shi da unda davamtavrot tamashi...
            return $this->checkEnd();
        }
    }

    /**
     * tu piveli karti jokeria anu acxada magali an caigos am shemtxvevashi
     * tu kartebshi koziria da magali koziri ar ucxadebia jokeri amovigot kartebidan radgan ver caigebs jokeri
     * shemdeg tu caigos acxada da es cveti aris kartebshi amovigot jokeri kartebidan
     *
     * tu bezia araa da koziria kartebshi mashin vfiltravt kozirze tu ara mashin pirveli kartis cvetze
     * valagebt kartebs sididis mixedvit da pirveli kartia yvelaze magali romelmac caigo
     * @return mixed
     */
    public function highestCard()
    {
        $suit = null;
        $cards = $this->cards;
        if (isset($cards[0]['action'])) {
            if ($this->trumpInCards() && $cards[0]['actionsuit'] != $this->trump['suit']) {
                array_shift($cards);
            } else if ($cards[0]['action'] == 'caigos' && $this->suitInCards($cards[0]['actionsuit'])) {
                array_shift($cards);
            }
        }

        if ($this->trump['strength'] != 16 && $this->trumpInCards()) {
            $suit = $this->trump['suit'];
        } else {
            $suit = $cards[0]['suit'];
        }

        $cards = array_filter($cards, function($card) use ($suit){
            return $card['suit'] == $suit || $card['strength'] == 16 || $card['strength'] == 17;
        });

        $cards = collect($cards)->sortByDesc('strength');
        $this->update(['cards' => []]);

        return $cards->values()->all()[0];
    }

    /**
     * tu bolo pulkaa da bolo darigeba gamovtvalot da davamtavrot tamashi
     *
     * tu 9-ianebia da bolo darigebaa an tu meore pulkaa da bolo darigebaa an tu 8 darigebaa eseigi pulkis boloa
     * gamovtvalot da gavagrdzelot tamashi
     *
     * sxva shemtxvevashi shemdegi darigeba davarigot
     * @return bool
     */
    public function checkEnd()
    {
        if ($this->quarter == 4 && $this->hand_count == 4) {
            $this->calcScoresAfterQuarter();
            return true;
        }
        // pulkebis bolo darigebebi
        if (($this->type == 9 && $this->hand_count == 4) || ($this->quarter == 2 && $this->hand_count == 4) || $this->hand_count == 8) {
            $this->calcScoresAfterQuarter();
            $this->update(['quarter' => $this->quarter + 1, 'hand_count' => 1, 'turn' => 0]);
            $this->deal();
            $this->refresh();
            broadcast(new UpdateGameEvent($this));
            return false;
        }

        $this->update([
            'hand_count' => $this->hand_count + 1,
            'turn' => $this->turnPosition(),
            'state' => 'deal',
            'trump' => null
        ]);

        $this->deal();
        $this->refresh();
        broadcast(new UpdateGameEvent($this));
        return false;
    }

    public function finishGame()
    {
        $scores = $this->scores()->latest()->limit(4)->get()->sortByDesc('result');
        $scores->each(function ($s) {
            $s->append('position');
        });

        $this->players->each(function ($player) {
            $player->increment('games_played');
        });

        $this->update(['state' => 'finished', 'turn' => 4]);
        $this->refresh();

        broadcast(new \App\Events\GameOverEvent($this, $scores));
    }

    public function calcScoresAfterHand()
    {
        $all = $this->numCardsToDeal();
        $penalty = $this->penalty;

        $this->players->each(function ($player) use($all, $penalty) {
            $score = $player->scores()->latest()->first();

            if ($score->call == $score->take && $score->call == $all) {
                $score->update(['result' => $score->call * 100]);
            } elseif ($score->call == $score->take) {
                $score->update(['result' => $score->call * 50 + 50]);
            } elseif ($score->take == 0) {
                $score->update(['result' => $penalty]);
            } else {
                $score->update(['result' => $score->take * 10]);
            }
        });
    }

    public function calcScoresAfterQuarter()
    {
        $perfectNum = $this->numPerfectInScores();

        switch ($perfectNum) {
            case 1:
                foreach($this->players as $player)
                {
                    $sum = $player->scores()->where('quarter', $this->quarter)->sum('result');
                    $max = $player->scores()->where('quarter', $this->quarter)->max('result');
                    if ($player->scores()->where('quarter', $this->quarter)->whereColumn('call', 'take')->count() == $this->hand_count) {
                        $result = $sum + $max;
                        $player
                            ->scores()
                            ->where([['quarter', $this->quarter], ['result', $max]])
                            ->first()
                            ->update(['color' => 'yellow']);
                    } else {
                        $result = $sum - $max;
                        $player
                            ->scores()
                            ->where([['quarter', $this->quarter], ['result', $max]])
                            ->first()
                            ->update(['color' => 'red']);
                    }

                    $player->scores()->create([
                        'quarter' => $this->quarter,
                        'result' => $result
                    ]);
                }
                break;
            case 2:
                foreach($this->players as $player)
                {
                    $sum = $player->scores()->where('quarter', $this->quarter)->sum('result');
                    $max = $player->scores()->where('quarter', $this->quarter)->max('result');
                    if ($player->scores()->where('quarter', $this->quarter)->whereColumn('call', 'take')->count() == $this->hand_count) {
                        $result = $sum + $max;
                        $player
                            ->scores()
                            ->where([['quarter', $this->quarter], ['result', $max]])
                            ->first()
                            ->update(['color' => 'yellow']);
                    } else {
                        $result = $sum;
                    }

                    $player->scores()->create([
                        'quarter' => $this->quarter,
                        'result' => $result
                    ]);
                }
                break;
            default:
                foreach($this->players as $player)
                {
                    $result = $player->scores()->where('quarter', $this->quarter)->sum('result');

                    $player->scores()->create([
                        'quarter' => $this->quarter,
                        'result' => $result
                    ]);
                }
                break;
        }
    }

    public function deal()
    {
        $deck = new Deck;
        $num = $this->numCardsToDeal();
        $max = $num * 4;
        $cards = [[], [], [], []];

        for ($i = 0, $j = 0; $i < $max; $i++) {
            array_push($cards[$j], $deck->cards[$i]);
            $j = $j == 3 ? 0 : $j + 1;
        }

        switch ($this->turn) {
            case 0:
                $positions = [0, 1, 2, 3];
                break;
            case 1:
                $positions = [3, 0, 1, 2];
                break;
            case 2:
                $positions = [2, 3, 0, 1];
                break;
            default:
                $positions = [1, 2, 3, 0];
                break;
        }

        $this->players->each(function ($player, $pos) use ($cards, $positions) {
            $player->update(['cards' => $cards[$positions[$pos]]]);
        });

        $this->setTrump($num, $deck->cards[$max] ?? null);
    }

    private function setTrump($num, $card)
    {
        if ($num != 9) {
            $this->update(['state' => 'call', 'trump' => $card]);
            $this->players->each(function ($player) {
                broadcast(new CardDealEvent($player->user->id, $player->cards));
            });
        } else {
            $this->update(['state' => 'trump']);
            $this->players->each(function ($player) {
                if ($this->turn == $player->position) {
                    broadcast(new CardDealEvent($player->user->id, array_slice($player->cards, 0, 3), true));
                } else {
                    broadcast(new CardDealEvent($player->user->id, array_slice($player->cards, 0, 3)));
                }
            });
        }
    }

    protected function turnPosition()
    {
        return $this->hand_count >= 4 ? $this->hand_count - 4 : $this->hand_count;
    }

    public function numCardsToDeal()
    {
        if ($this->type == 9 || $this->quarter % 2 == 0) return 9;

        return $this->hand_count;
    }

    public function numPerfectInScores()
    {
        $count = 0;
        foreach($this->players as $player)
        {
            if ($player->scores()->where('quarter', $this->quarter)->whereColumn('call', 'take')->count() == $this->hand_count) {
                $count++;
            }
        }

        return $count;
    }

    public function jokInCards()
    {
        if (! $this->cards) return false;
        foreach ($this->cards as $card) {
            if ($card['strength'] == 16) {
                return true;
            }
        }

        return false;
    }

    public function suitInCards($suit)
    {
        foreach ($this->cards as $card) {
            if ($card['suit'] == $suit) {
                return true;
            }
        }

        return false;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class)->orderBy('position', 'asc');
    }

    public function addPlayer(User $user, $pos = null)
    {
        $pos = $pos === 0 ? $pos : $this->players()->count();

        $user->player->update(['game_id' => $this->id, 'position' => $pos]);
    }

    public function path()
    {
        return '/games/' . $this->id;
    }

    //atuzva
    public function setPositions()
    {
        $deck = (new Deck)->lastPlayer();
        $lastPlayer = $deck['pos'];

        switch ($lastPlayer) {
            case 1:
                $positions = [3, 0, 1, 2];
                break;
            case 2:
                $positions = [2, 3, 0, 1];
                break;
            case 3:
                $positions = [1, 2, 3, 0];
                break;
            default:
                $positions = [0, 1, 2, 3];
                break;
        }

        for ($i = 0; $i < 4; $i++) {
            $this->players[$i]->setPosition($positions[$i]);
        }

        return $deck['cards'];
    }

    protected function trumpInCards()
    {
        if ($this->trump['strength'] == 16) return false;
        foreach ($this->cards as $card) {
            if($card['suit'] == $this->trump['suit']) return true;
        }

        return false;
    }

    public function determinePosition()
    {
        $positions = $this->players()->pluck('position')->toArray();

        for ($position = 1; $position < 4; $position++) {
            if (! in_array($position, $positions)) return $position;
        }
    }

    public function updateTurn()
    {
        $turn = $this->turn == 3 ? 0 : $this->turn + 1;

        $this->update(['turn' => $turn]);
    }

    public function updateCallCount()
    {
        if ($this->call_count == 3) {
            $this->update(['call_count' => 0, 'state' => 'card']);
        } else {
            $this->increment('call_count');
        }
    }

    public function scores()
    {
        return $this->hasManyThrough(Score::class, Player::class);
    }

    public function exceptCall()
    {
        if ($this->call_count != 3) return -1;

        $sum = $this->scores()->latest()->limit(3)->get()->sum('call');
        $max = $this->numCardsToDeal();

        return $max - $sum;
    }

    public function getExceptAttribute()
    {
        return $this->exceptCall();
    }

    public function reposition()
    {
        $this->players->each(function ($player, $key) {
            $player->setPosition($key);
        });
    }

    public function addReadyPlayer($id, $ready)
    {
        $gameready = $this->ready;

        if ($ready == 1) {
            $gameready['count']++;
        }

        array_push($gameready['players'], $id);

        $this->ready = $gameready;
        $this->save();
    }

    public function kick($pos) {
        $player = $this->players[$pos];
        $player->update(['game_id' => null, 'position' => null]);
        $kicked = $this->kicked_users;
        array_push($kicked, $player->id);
        $this->update(['kicked_users' => $kicked]);
        $this->refresh();
        $this->updatePlayersPosition();
    }

    public function updatePlayersPosition() {
        $this->players->each(function ($player, $key) {
            $player->setPosition($key);
        });
    }
}
