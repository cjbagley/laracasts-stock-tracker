<?php

namespace App\Clients;

use App\Models\Retailer;
use Illuminate\Support\Str;

class ClientFactory
{
    /**
     * @throws ClientException
     */
    public function make(Retailer $retailer): Client
    {
        $client = 'App\\Clients\\'.Str::studly($retailer->name);

        if (! class_exists($client)) {
            throw new ClientException('Retailer not found for '.$retailer->name);
        }

        return new $client;
    }
}
