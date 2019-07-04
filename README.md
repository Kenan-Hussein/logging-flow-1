
# Logging Flow

Call endpoint to send message, log or json to out put, log file or Kafka.
Receive message from Kafka in terminal. 



## Menu

 1. Symfony endpoint.
 2. Messenger component.
 3. Create log channel with special format
 4. Send message to Kafka
 5. Receive message from Kafka


## 1. Symfony endpoint:

    composer create-project symfony/skeleton logging-flow
    composer require annotation
    composer require maker

Make new controller:

    php bin/console make:controller LoggingController

 Then endpoint that will be called:

     /**  
     * @Route("/logging", name="logging")  
     */
    public function postLog()  
    {  
	    //message text  
        $message = "yah it is working";  
      
        return $this->jsonMessage($message);  
    }  
      
    public function jsonMessage(string $message)  
    {  
	    return new JsonResponse(  
		    [  'message' => $message,  
	        ],  
	        JsonResponse::HTTP_OK 
      );  
    }

## 2. Messenger component:

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


## 3. Create log channel with special format :
Store log messages in special log file. 
*To do that we will create channel*

We need Monolog:

    composer require symfony/monolog-bundle

Or

    composer require monolog
In packages folder create new file let us call it **monolog.yaml**    
Add following :

    monolog:  
        channels: ["elk"]
This will create the channel        

Now we want to make the log message has special format for example let us use format suit Logstash:
In **config\services.yaml** add following :

    service.elkformater:  
        class: Monolog\Formatter\LogstashFormatter  
        arguments:  
            - 'appName'  
		    - ~  
            - ~  
            - ~  
            - 1
Now in **config\packages\dev\monolog.yaml** add under *main* the following:

    elk:  
        type: stream  
        path: "%kernel.logs_dir%/elk.log"  
        formatter: service.elkformater  
        level: info  
        channels: ["elk"]
Notice that path have the path and name of log file. For this example log file will create in **var\log\elk.log**        
### Testing
We need server for testing

    composer require server

Run the server by typing:

    php bin/console server:run
Add this to your controller:

    /**  
     * @var LoggerInterface  
     */
    private $logger;  
      
    public function __construct(LoggerInterface $logger)  
    {  
      $this->logger = $logger;  
    }  
      
    /**  
     * @Route("/logging", name="logging")  
     */
    public function postLog()  
    {  
      //message text  
      $message = "yah it is working";  
      
      $this->elkLogger($message);        
    }
    
    public function elkLogger($message)  
    {    
      $this->logger->info($message);  
    }

Copy the endpoint  link to post man or your browser.

## 4. Send message to Kafka
### Requirements

-   Minimum PHP version: 7.1
-   Kafka version greater than 0.8
-   The consumer module needs kafka broker version greater than 0.9.0

### Composer Install
Add this to composer.json file :
```
{
	"require": {
		"nmred/kafka-php": "0.2.*"
	}
}
```
Then run:

    composer update
    
The following method contain the configuration for Kafka also send message to Kafka:

    public function sendToKafka()  
    {  
      $config = ProducerConfig::getInstance();  
      $config->setMetadataRefreshIntervalMs(10000);  
      $config->setMetadataBrokerList('127.0.0.1:9092');  
      $config->setBrokerVersion('1.0.0');  
      $config->setRequiredAck(1);  
      $config->setIsAsyn(false);  
      $config->setProduceInterval(500);  
      
      $producer = new Producer(function () {  
	      return [  
		      [  'topic' => 'yes',  
			     'value' => 'Yes-Soft......message',  
			     'key' => '',  
		      ],  
	      ];  
      });  
      
      $producer->success(function ($result): void {  
	      var_dump($result);  
      });  
      
      $producer->error(function ($errorCode): void {  
	     var_dump($errorCode);  
      });  
      
      $producer->send(true);  
    }  
   ### Testing
   

  1. Run **ZooKeeper** server and **Kafka** server
   2. Make new topic matching the topic name in your method
   3. Run **kafka console consumer** with same topic name
   4. Run `php bin/console server:run`
	5. Call the method in your controller endpoint
	6. Call the end point using *postman* or *browser*
	7. You will see the message in  **kafka console consumer**

## 5. Receive message from Kafka
Create new project

### Composer Install
Add this to composer.json file :
```
{
	"require": {
		"nmred/kafka-php": "0.2.*"
	}
}
```
Then run:

    composer update

Create new php file call it Consumer and add following:

    <?php
    
    use Kafka\Consumer;
    use Kafka\ConsumerConfig;
    
    require './vendor/autoload.php';
    
    $config = ConsumerConfig::getInstance();
    $config->setMetadataRefreshIntervalMs(10000);
    $config->setMetadataBrokerList('127.0.0.1:9092');
    $config->setGroupId('yes');
    $config->setBrokerVersion('1.0.0');
    $config->setTopics(['yes']);
    $config->setOffsetReset('earliest');
    $consumer = new Consumer();
    
    $consumer->start(function($topic, $part, $message) {
        var_dump($message);
    });

   ## Testing
   1. Run **ZooKeeper** server and **Kafka** server
   2. Make new topic matching the topic name in your method
   3. Run **kafka console producer** with same topic name
   4. Run `php bin/console Consumer.php`
	5. In new terminal tap run `php bin/console server:run`
	6. Send message using **kafka console producer**
	7. You will see the message in the second terminal tap