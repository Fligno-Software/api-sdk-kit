<?php

namespace Fligno\ApiSdkKit\Abstracts;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class BaseJsonSerializable
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
abstract class BaseJsonSerializable
{
    /**
     * BaseModel constructor.
     * @param Response|Collection|array|null $data
     * @param string|null $key
     */
    public function __construct(Response|Collection|array $data = NULL, ?string $key = NULL)
    {
        if(empty($data) === FALSE) {
            if($data instanceof Response) {
                $this->parse($data, $key);
            }
            elseif (is_array($data)) {
                $this->setFields($data);
            }
        }
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
        foreach (get_object_vars($this) as $key=>$value){
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
}
