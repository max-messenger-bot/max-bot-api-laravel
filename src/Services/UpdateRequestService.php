<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Services;

use Illuminate\Http\Request;
use MaxMessenger\Bot\Exceptions\MaxApiException;
use MaxMessenger\Bot\MaxBot;
use MaxMessenger\Bot\Models\Responses\Update;
use Psr\Log\LoggerInterface;
use SensitiveParameter;
use SensitiveParameterValue;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use function is_string;
use function sprintf;
use function strlen;

final class UpdateRequestService
{
    /**
     * @var SensitiveParameterValue<string>|null
     */
    private ?SensitiveParameterValue $secret = null;

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function makeUpdateFromString(string $body): Update
    {
        try {
            return MaxBot::makeUpdateFromString($body);
        } catch (MaxApiException $e) {
            $msg = $e->getMessage();
            $this->logWarning(__METHOD__, $msg);

            throw new BadRequestHttpException($msg);
        }
    }

    public function readContentFromRequest(Request $request): string
    {
        $contentType = $request->header('Content-Type');
        $contentLength = $request->header('Content-Length');

        if (!is_string($contentType) || !str_contains($contentType, 'application/json')) {
            $msg = 'Invalid Content-Type';
            $this->logWarning(__METHOD__, $msg);

            throw new BadRequestHttpException($msg);
        }

        if (!is_string($contentLength)) {
            $msg = 'Required Content-Length';
            $this->logWarning(__METHOD__, $msg);

            throw new BadRequestHttpException($msg);
        }

        if ($this->secret !== null && ($request->header('X-Max-Bot-Api-Secret') !== $this->secret->getValue())) {
            $msg = 'Invalid secret';
            $this->logWarning(__METHOD__, $msg);

            throw new AccessDeniedHttpException($msg);
        }

        $body = $request->getContent();

        if (empty($body)) {
            $msg = 'Body is empty';
            $this->logWarning(__METHOD__, $msg);

            throw new BadRequestHttpException($msg);
        }

        if (strlen($body) !== (int)$contentLength) {
            $msg = 'Body size does not match the passed content-length';
            $this->logWarning(__METHOD__, $msg);

            throw new BadRequestHttpException($msg);
        }

        return $body;
    }

    public function setSecret(#[SensitiveParameter] string $secret): void
    {
        $this->secret = new SensitiveParameterValue($secret);
    }

    private function logWarning(string $method, string $message): void
    {
        $this->logger->warning(sprintf('%s(): %s', $method, $message));
    }
}
