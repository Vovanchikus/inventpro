<?php namespace Samvol\Inventory\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryEntityChanged implements ShouldBroadcastNow
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public int $organizationId,
        public string $entity,
        public string $action,
        public int $entityId,
        public ?string $updatedAt = null
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('org.' . $this->organizationId . '.inventory'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'inventory.entity.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'entity' => $this->entity,
            'action' => $this->action,
            'id' => $this->entityId,
            'updated_at' => $this->updatedAt,
        ];
    }
}
