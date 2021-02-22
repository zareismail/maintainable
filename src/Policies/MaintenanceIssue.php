<?php 

namespace Zareismail\Maintainable\Policies;


class MaintenanceIssue extends Policy
{ 
    /**
     * Authorize user to settle up an issue.
     * 
     * @return 
     */
    public function settleUp($user, $issue) 
    {
        return $user->isDeveloper(); 
    }
}
