<?php

namespace Fligno\ApiSdkKit\Interfaces;

use Fligno\StarterKit\Abstracts\BaseJsonSerializable;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

/**
 * Interface CanGetNewApiKeysInterface
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
interface CanGetNewApiKeysInterface
{
    /**
     * @param  BaseJsonSerializable|Collection|array|null $data
     * @return Model|BaseJsonSerializable|PromiseInterface|Response|Collection|array
     */
    public function getNewApiKeys(
        BaseJsonSerializable|Collection|array $data = null
    ): Model|BaseJsonSerializable|PromiseInterface|Response|Collection|array;
}
