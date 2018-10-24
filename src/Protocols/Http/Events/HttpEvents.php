<?php

namespace AdamQuaile\HypermediaApiClient\Protocols\Http\Events;

class HttpEvents
{
    const PREPARE_REQUEST   = 'hypermedia.http.prepare_request';
    const PROCESS_RESPONSE  = 'hypermedia.http.process_response';
    const FINALISE_RESOURCE = 'hypermedia.http.finalise_resource';
}