<?php

namespace App\States\Research;

class DeskEvalState extends ResearchState
{
    public function statusName(): string
    {
        return 'desk_eval';
    }

    public function approve(): void
    {
        $this->transitionTo(new ApprovedState($this->penelitian));
    }

    public function reject(): void
    {
        $this->transitionTo(new DraftState($this->penelitian));
    }
}
