<?php

namespace App\States\Research;

class DraftState extends ResearchState
{
    public function statusName(): string
    {
        return 'draft';
    }

    public function submit(): void
    {
        $this->transitionTo(new DeskEvalState($this->penelitian));
    }
}
