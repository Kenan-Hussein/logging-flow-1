# Logging Flow

Call endpoint to send message



## Menu

 1. Symfony project.
 2. Messenger component.
 3. Transport for Kafka.



## Symfony project:

    composer create-project symfony/skeleton 
    composer require annotation
    composer require maker

Make new controller then endpoint that will be called to send message body to Kafka.


## Messenger component:

## Concepts:
**Sender:**
Responsible for serializing and sending messages to  _something_. This something can be a message broker or a third party API for example.


**Receiver:**
Responsible for retrieving, deserializing and forwarding messages to handler(s). This can be a message queue puller or an API endpoint for example.


**Handler:**
Responsible for handling messages using the business logic applicable to the messages. Handlers are called by the  `HandleMessageMiddleware`  middleware.


**Bus:**
The bus is used to dispatch messages. The behavior of the bus is in its ordered middleware stack. The component comes with a set of middleware that you can use.

## How to Use the Messenger?
### Installation:
In applications using [_Symfony Flex_](https://symfony.com/doc/4.2/setup/flex.html), run this command to install messenger before using it:

    composer require messenger
### Message:
Before you can send a message, you must create it first. There is no specific requirement for a message, except it should be serializable and unserializable by a Symfony Serializer instance.
### Using the Messenger Service:
Once enabled, the `message_bus` service can be injected in any service where you need it, like in a controller.
### Registering Handlers:
In order to do something when your message is dispatched, you need to create a message handler. It's a class with an `__invoke` method.
### Transports:
By default, messages are processed as soon as they are dispatched. If you prefer to process messages asynchronously, you must configure a transport. These transports communicate with your application via queuing systems or third parties.
A transport is registered using a "DSN", which is a string that represents the connection credentials and configuration. By default, when you've installed the messenger component, the following configuration should have been created:

> config/packages/messenger.yaml

    framework:
        messenger:
            transports:
                amqp: "%env(MESSENGER_TRANSPORT_DSN)%"

### Dispatching the Message:
You're ready! To dispatch the message (and call the handler), inject the `message_bus` service (via the `MessageBusInterface`), like in a controller:

    $bus->dispatch(new SmsNotification('Look! I created a message!'));
   
    // or use the shortcut
    $this->dispatchMessage(new SmsNotification('Look! I created a message!'));


## Transport for Kafka :
##### (Not included in the files yet, Waiting for other information to be posted)
#### To use a transport that's not supported like Kafka, Amazon SQS and Google Pub/Sub, We going to use Enqueue's transport:
## Usage
1.  Install the transport

```
composer req sroze/messenger-enqueue-transport
```


2. Configure the Enqueue bundle

        .env
     
        ###> enqueue/enqueue-bundle ###
        ENQUEUE_DSN=amqp://guest:guest@localhost:5672/%2f
        ###< enqueue/enqueue-bundle ###
3.  Configure Messenger's transport (that we will name  `amqp`) to use Enqueue's  `default`  transport:

        #config/packages/messenger.yaml
        
         framework:
                 messenger:
                          transports:
                                 amqp: enqueue://default
 4.  Route the messages that have to go through the message queue:

    config/packages/framework.yaml
    framework:
        messenger:
            # ...
    
            routing:
                'App\Message\MyMessage': amqp    
### Configure custom Kafka message
Here is the way to send a message with with some custom options:

    $this->bus->dispatch((new Envelope($message))->with(new TransportConfiguration([
     'topic' => 'test_topic_name',
     'metadata' => [
     'key' => 'foo.bar',
     'partition' => 0,
     'timestamp' => (new \DateTimeImmutable())->getTimestamp(),
     'messageId' => uniqid('kafka_', true),
     ]
    ])))
