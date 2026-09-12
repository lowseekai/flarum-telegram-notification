<?php

declare(strict_types=1);

namespace Nodeloc\TelegramNotification\Jobs;

use Flarum\Foundation\Application;
use Flarum\Settings\SettingsRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Throwable;

final class SendTelegramNotificationJob implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public int $timeout = 15;

    public function __construct(
        private string $discussionTitle,
        private int $discussionId
    ) {
    }

    public function handle(
        SettingsRepositoryInterface $settings,
        Application $app,
        LoggerInterface $logger
    ): void {
        $botToken = trim((string) $settings->get('telegram.bot_token'));
        $channelId = trim((string) $settings->get('telegram.channel_id'));

        if ($botToken === '' || $channelId === '') {
            return;
        }

        $discussionUrl = rtrim($app->url(), '/').'/d/'.$this->discussionId;
        $message = sprintf(
            "📌 %s\n%s",
            $this->escapeHtml($this->discussionTitle),
            $this->escapeHtml($discussionUrl)
        );

        try {
            $response = (new Client([
                'connect_timeout' => 5,
                'timeout' => 10,
                'http_errors' => false,
            ]))->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'json' => [
                    'chat_id' => $channelId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $payload = json_decode((string) $response->getBody(), true);

            if ($statusCode < 200 || $statusCode >= 300 || !is_array($payload) || ($payload['ok'] ?? false) !== true) {
                $logger->warning('Telegram notification was rejected.', [
                    'status' => $statusCode,
                    'description' => is_array($payload) ? ($payload['description'] ?? null) : null,
                ]);
            }
        } catch (Throwable $exception) {
            $logger->error('Telegram notification failed.', [
                'exception' => $exception,
            ]);
        }
    }

    private function escapeHtml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
