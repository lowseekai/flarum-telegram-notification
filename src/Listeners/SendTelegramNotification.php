<?php

declare(strict_types=1);

namespace Nodeloc\TelegramNotification\Listeners;

use Flarum\Discussion\Discussion;
use Flarum\Discussion\Event\Started;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Queue\Queue;
use Nodeloc\TelegramNotification\Jobs\SendTelegramNotificationJob;

final class SendTelegramNotification
{
    public function __construct(
        private Queue $queue,
        private SettingsRepositoryInterface $settings
    ) {
    }

    private function shouldExclude(Discussion $discussion): bool
    {
        $excludedTags = trim((string) $this->settings->get('telegram.excluded_tags'));

        if ($excludedTags === '') {
            return false;
        }

        $excludedTagIds = array_values(array_filter(
            array_map(
                static fn (string $id): int => (int) $id,
                preg_split('/\s*,\s*/', $excludedTags, -1, PREG_SPLIT_NO_EMPTY) ?: []
            ),
            static fn (int $id): bool => $id > 0
        ));

        if ($excludedTagIds === []) {
            return false;
        }

        $discussion->loadMissing('tags');

        $discussionTagIds = $discussion->tags
            ->pluck('id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        return (bool) array_intersect($excludedTagIds, $discussionTagIds);
    }

    public function handle(Started $event): void
    {
        $discussion = $event->discussion;

        if ($this->shouldExclude($discussion)) {
            return;
        }

        $this->queue->push(new SendTelegramNotificationJob(
            (string) $discussion->title,
            (int) $discussion->id
        ));
    }
}
