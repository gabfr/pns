<?php

namespace App\Listeners;

use Dingo\Api\Event\ResponseWasMorphed;

/**
 * Auto add pagination links to response
 *
 * @since 1.0.0
 * @uses Dingo\Api\Event\ResponseWasMorphed
 */
class AddPaginationLinksToResponse
{
    public function handle(ResponseWasMorphed $event)
    {
        // Check if has pagination meta
        if (! isset($event->content['meta']['pagination'])) return;

        $links = $event->content['meta']['pagination']['links'];

        // Check if links is not empty and is set
        if(! isset($links['links']) || empty($links['links'])) return;

        $event->response->headers->set(
            'link',
            sprintf('<%s>; rel="next", <%s>; rel="prev"', $links['links']['next'], $links['links']['previous'])
        );
    }
}