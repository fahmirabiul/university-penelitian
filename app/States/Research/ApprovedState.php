<?php

namespace App\States\Research;

class ApprovedState extends ResearchState
{
    public function statusName(): string
    {
        return 'approved';
    }
    
    // Final state: parent class handles invalid transitions.
}
