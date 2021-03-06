<?php

declare(strict_types=1);

namespace App\Models;

use App\Model\Park;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ride extends Model
{
    /** @var bool */
    private $showRelationship = false;

    public function getId() : string
    {
        return (string) $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getParkId() : string
    {
        return (string) $this->park_id;
    }

    public function park() : BelongsTo
    {
        return $this->belongsTo(Park::class, 'park_id', 'id');
    }

    public function detail() : HasOne
    {
        return $this->hasOne(RideDetail::class, 'ride_id', 'id');
    }

    public function setShowRelationship(bool $showRelationships) : void
    {
        $this->showRelationship = $showRelationships;
    }

    public function getShowRelationship() : bool
    {
        return $this->showRelationship;
    }

    public function setParkId(int $parkId) : void
    {
        $this->park_id = $parkId;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }
}
