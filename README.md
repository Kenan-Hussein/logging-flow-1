# Logging Flow

### Main goal:
Call endpoint to see body message in following technologies:

Elastic search.



## Menu

 1. Symfony project.
 2. Messenger component.



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

