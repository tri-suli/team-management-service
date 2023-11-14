<?php

namespace App\Http\Resources;

use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

class StorePlayerResource extends JsonResource
{
    public static $wrap = false;

    public function __construct(Player $player, Collection $skills)
    {
        parent::__construct($this->buildResource($player, $skills));
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return parent::toArray($request);
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode(Response::HTTP_CREATED);

        parent::withResponse($request, $response);
    }

    private function buildResource(Player $player, Collection $skills): array
    {
        return [
            'id' => $player->id,
            'name' => $player->name,
            'position' => $player->position,
            'playerSkills' => $skills->transform(fn (PlayerSkill $playerSkill) => [
                ...$playerSkill->only('id', 'skill', 'value'),
                'playerId' => $playerSkill->player_id
            ])->toArray()
        ];
    }
}
