<?php

namespace App;

use App\Events\CardsEvent;
use App\Events\StartGameEvent;
use App\Events\UpdateGameEvent;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = [];
    protected $with = ['creator', 'players'];
    protected $appends = [];
    protected $casts = [
        'cards' => 'array',
        'trump' => 'array',
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

        broadcast(new StartGameEvent($this, $cards));
    }

    public function addCard($card)
    {
        if ($this->jokInCards() && isset($card['action']) && $card['action'] == 'mojokra') {
            $card['strength'] = 17;
        } elseif (isset($card['action']) && $card['action'] == 'nije') {
            $card['strength'] = 1;
        }
        $cards = $this->cards;
        array_push($cards, $card);
        $this->cards = $cards;
        $this->save();
        auth()->user()->player()->update(['card' => $card]);
        $this->refresh();
        if (count($this->cards) === 4) {
            $highestCard = $this->highestCard();
            foreach ($this->players as $player) {
                if ($player->card == $highestCard) {
                    $player->scores()->increment('take');
                    $this->update(['turn' => $player->position]);
                    broadcast(new UpdateGameEvent($this));
                    if (empty($this->players()->pluck('cards')->whereNotNull()->toArray())) {
                        $this->calcScoresAfterHand();
                        $this->checkEnd();
                    }
                }
            }
        } else {
            $this->updateTurn();
        }
    }

    public function highestCard()
    {
        $suit = null;
        $cards = $this->cards;
        if (isset($cards[0]['action'])) {
            if ($this->trumpInCards() && $cards[0]['actionsuit'] != $this->trump['suit']) {
                array_shift($cards);
            } elseif ($cards[0]['action'] == 'caigos' && $this->suitInCards($cards[0]['actionsuit'])) {
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

    public function checkEnd()
    {
        if ($this->quarter == 4 && $this->hand_count == 4) {
            $this->calcScoresAfterQuarter();
            return;
        }
        // pulkebis bolo darigebebi
        if (($this->type == 9 && $this->hand_count == 4) || ($this->quarter == 2 && $this->hand_count == 4) || $this->hand_count == 8) {
            $this->calcScoresAfterQuarter();
            $this->update(['quarter' => $this->quarter + 1, 'hand_count' => 1, 'turn' => 0]);
            broadcast(new UpdateGameEvent($this));
            $this->deal();
            return;
        }

        $this->update([
            'hand_count' => $this->hand_count + 1,
            'turn' => $this->turnPosition(),
            'state' => 'call',
            'trump' => null]);
        broadcast(new UpdateGameEvent($this));
        $this->deal();
    }

    protected function calcScoresAfterHand()
    {
        $all = $this->numCardsToDeal();
        $penalty = $this->penalty;
        $this->players->each(function ($player, $key) use($all, $penalty) {
            $score = $player->scores()->latest()->first();
            if ($score->call === $score->take && $score->call === $all) {
                $score->update(['result' => $score->call * 100]);
            } elseif ($score->call === $score->take) {
                $score->update(['result' => $score->call * 50 + 50]);
            } elseif ($score->call > 0 && $score->take === 0) {
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
                    if ($player->scores()->where('quarter', $this->quarter)->whereColumn('call', 'take')->count() === $this->hand_count) {
                        $result = $player->scores()->where('quarter', $this->quarter)->sum('result') +
                            $player->scores()->where('quarter', $this->quarter)->max('result');
                        $player->scores()->create([
                            'quarter' => $this->quarter,
                            'result' => $result
                        ]);
                    } else {
                        $result = $player->scores()->where('quarter', $this->quarter)->sum('result') -
                            $player->scores()->where('quarter', $this->quarter)->max('result');
                        $player->scores()->create([
                            'quarter' => $this->quarter,
                            'result' => $result
                        ]);
                    }
                }
                break;
            case 2:
                foreach($this->players as $player)
                {
                    if ($player->scores()->where('quarter', $this->quarter)->whereColumn('call', 'take')->count() === $this->hand_count) {
                        $result = $player->scores()->where('quarter', $this->quarter)->sum('result') +
                            $player->scores()->where('quarter', $this->quarter)->max('result');
                        $player->scores()->create([
                            'quarter' => $this->quarter,
                            'result' => $result
                        ]);
                    } else {
                        $result = $player->scores()->where('quarter', $this->quarter)->sum('result');
                        $player->scores()->create([
                            'quarter' => $this->quarter,
                            'result' => $result
                        ]);
                    }
                }
                break;
            default:
                foreach($this->players as $player)
                {
                    $result = $player->scores()->where('quarter', $this->quarter)->sum('result');

                    $player->scores()->create([
                        'quarter' => $this->quarter,
                        'call' => 0,
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
            $this->trump = $card;
            $this->update(['state' => 'call']);
            $this->players->each(function ($player, $pos) {
                broadcast(new CardsEvent($player->id, $player->cards));
            });
        } else {
            $this->update(['state' => 'trump']);
            $this->players->each(function ($player, $pos) {
                if ($this->turn == $player->position) {
                    broadcast(new CardsEvent($player->id, array_slice($player->cards, 0, 3), true));
                } else {
                    broadcast(new CardsEvent($player->id, array_slice($player->cards, 0, 3)));
                }
            });
        }

    }

    protected function turnPosition()
    {
        return $this->hand_count > 4 ? $this->hand_count - 4 : $this->hand_count;
    }

    public function numCardsToDeal()
    {
        if ($this->type == 9 || $this->quarter % 2 == 0) return 9;

        return $this->hand_count;
    }

    protected function numPerfectInScores()
    {
        $count = 0;
        foreach($this->players as $player)
        {
            if ($player->scores()->where('quarter', $this->quarter)->whereColumn('call', 'take')->count() === $this->hand_count) {
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
        if (! $this->cards) return false;
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
        $pos = $pos ?? $this->pos();

        $this->players()->create([
            'user_id' => $user->id,
            'position' => $pos
        ]);
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
        if ($this->trump['strength'] == 16 || ! $this->cards) return false;
        foreach ($this->cards as $card) {
            if(in_array($this->trump['suit'], $card)) return true;
        }

        return false;
    }

    protected function pos()
    {
        $positions = $this->players()->pluck('position')->toArray();

        for ($i = 1; $i < 4; $i++) {
            if (! in_array($i, $positions)) return $i;
        }
    }

    public function updateTurn()
    {
        if ($this->turn == 3) {
            $this->update(['turn' => 0]);

        } else {
            $this->increment('turn');
        }
    }

    public function updateCallCount()
    {
        if ($this->call_count == 3) {
            $this->update(['call_count' => 0, 'state' => 'card']);
        } else {
            $this->increment('call_count');
        }
    }
}
