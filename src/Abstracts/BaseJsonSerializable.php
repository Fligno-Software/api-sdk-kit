<?php

namespace Fligno\ApiSdkKit\Abstracts;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonSerializable;

/**
 * Class BaseJsonSerializable
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
abstract class BaseJsonSerializable implements JsonSerializable
{
    /**
     * BaseModel constructor.
     * @param Response|Collection|array $data
     * @param string|null $key
     */
    public function __construct(Response|Collection|array $data = [], ?string $key = NULL)
    {
        if($data instanceof Collection) {
            $this->__construct($data->toArray(), $key);
        }
        else if($data instanceof Response) {
            $this->parse($data, $key);
        }

        $this->setFields($data);
    }

    /**
     * @param Response|Collection|array|NULL $data
     * @param string|null $key
     * @return static
     */
    public static function from(Response|Collection|array $data = NULL, ?string $key = NULL): static
    {
        return new static($data, $key);
    }

    /**
     * @param Response $response
     * @param string|null $key
     * @return $this
     */
    public function parse(Response $response, ?string $key = NULL): BaseJsonSerializable
    {
        if($response->ok() && $array = $response->json($key)) {
            $this->setFields($array);
        }

        return $this;
    }

    /**
     * @param array $array
     */
    protected function setFields(array $array): void
    {
        foreach (get_class_vars(self::class) as $key=>$value){
            if(Arr::has($array, $key)) {
                if(method_exists($this, $method = Str::camel('set' . $key))) {
                    $this->$method($array[$key]);
                }
                else {
                    $this->{$key} = $array[$key];
                }
            }
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize(): mixed
    {
        foreach (get_object_vars($this) as $key=>$value) {
            if (empty($value)) {
                unset($this->$key);
            }
        }

        return $this;
    }
}
