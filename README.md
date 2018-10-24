# Hypermedia API Client

An experimental hypermedia API client designed to fulfil the HATEOAS part of REST.

Only HTTP(s) is supported in a useful state right now, as it's by far the most used mechanism for APIs currently, though 
since HTTP is not a requirement for RESTful APIs, the interface presented by this library will aim to be 
protocol agnostic.

By understanding common content-types and standards where available (and allowing flexible configuration otherwise)
we are able to provide an expressive syntax to navigating and interacting with APIs. 

## Features

- [ ] Iteration over collections, even when paginated
- [ ] Support for following hypermedia links
- [ ] Support for performing actions through hypermedia controls

## Support for various formats and standards

- [ ] JSON HyperSchema
- [ ] Collection+JSON
- [ ] JSON-LD
- [ ] HAL
- [ ] HAL Forms
- [ ] JSON:API
- [ ] OpenAPI / Swagger

## How it works

To begin interacting with an API, you need an `ApiClient`. Initialise one with an array of supported protocols. An 
example, using HTTP and Guzzle looks like this:

```php
use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\EventDispatcher;
use AdamQuaile\HypermediaApiClient\Protocols\Http\HttpProtocol;

$apiClient = new ApiClient(
    [
        new HttpProtocol(
            \Http\Adapter\Guzzle6\Client::createWithConfig([
                'timeout' => 5
            ]),
            new \Http\Message\MessageFactory\GuzzleMessageFactory()
        )
    ],
    new EventDispatcher()
);
```

The built-in HTTP protocol class uses HttPlug for it's HTTP abstraction, can support many libraries such as cURL and Guzzle. 
You can also provide test adapters for testing your apps or integrations.

Now that's set up, you can load a resource:

```php
$images = $apiClient->loadFromUri('https://api.digitalocean.com/v2/images');
```

From here you can interact with the resource, get data from the response, iterate over lists and follow and act upon
hypermedia links defined in the API. 

## Taking advantage of REST

Just like your web browser navigates the internet by starting with a URL and following links or filling in forms, 
equipped with the knowledge of HTTP, HTML, JavaScript, etc.. that's what we're aiming for with our API client. 

You'll need to teach your client how to understand how the APIs you're interacting with work. Perhaps it's using 
JSON:API, maybe it's using HAL or JSON HyperSchema. This is where extensions come in. 

As the hypermedia client loads a resource and delegates that loading to the protocol, they emit various events through 
the event dispatcher. Extensions listen to these events and provide the ability to parse various content types and understand
various hypermedia description formats.

## Using REST-ish APIs

While hypermedia controls are a requirement for REST, and help to decouple an API and its integrations, not all APIs are
RESTful. Whether they claim to be or not, some simply aren't. This does not make them inherently bad APIs, but you will
need to do a bit of extra work in order to use them with this hypermedia based client. 

