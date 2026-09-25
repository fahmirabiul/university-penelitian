<?php

namespace App\Traits;

use App\States\Research\ResearchState;
use App\States\Research\DraftState;
use App\States\Research\DeskEvalState;
use App\States\Research\ApprovedState;
use Exception;

/**
 * @property string $status_saat_ini
 */
trait HasResearchState
{
    public function state(): ResearchState
    {
        return match ($this->status_saat_ini) {
            'draft' => new DraftState($this),
            'desk_eval' => new DeskEvalState($this),
            'approved' => new ApprovedState($this),
            default => throw new Exception("State tidak dikenali pada model Penelitian: {$this->status_saat_ini}"),
        };
    }
}
