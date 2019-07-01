# Kafka Tutorials



## Installing Kafka 

See the Documentation i provided in logging Pipeline branch, this should be merged with it, BUT we'll see



## Kafka CLI

Check if Kafka and Zookeeper are running u can start the CLI Commands.



### Kafka Topics

start the fallowing command

```
./bin/windows/kafka-topics.bat
```

what this shows is all the commands that Kafka can do to the topics but it's so long it's tiresome.

So, what we will do is start the fallowing command

#### Create Topics from ZooKeeper

```
./bin/windows/kafka-topics.bat --zookeeper 127.0.0.1:2181 --topic yes_topic --create
```

Now this will show documentation, which means that something is wrong, the first line indicates the the partitions number is missing :| so how are we gonna fix it? well the first tip of the day goes like this

##### ALWAYS, use partition number when creating a topic 

```
./bin/windows/kafka-topics.bat --zookeeper 127.0.0.1:2181 --topic yes_topic --create --partitions 3
```

again Press enter and voila, another error ;P it's some thing about replication factor, that takes us to the second tip

##### Use Replication Factor of 3, this is the gold standard in the industry 

So, fixing that comes the code:

```
./bin/windows/kafka-topics.bat --zookeeper 127.0.0.1:2181 --topic yes_topic --create --partitions 3 --replication-factor 3
```

again, another error, this is not good, the message is `Replication factor: 3 larger than available brokers: 1` well, this is due to the limited setup we used earlier, to fix it just change the `replication-factor` to `1` at this time, but we'll go back to this another time.

##### Final 'Working' Code to create a topic

```
./bin/windows/kafka-topics.bat --zookeeper 127.0.0.1:2181 --topic yes_topic --create --partitions 3 --replication-factor 1
```

and the topic is created by message that contains the confirmation 



### Topics List

the command is really straight forward:

```
./bin/windows/kafka-topics.bat --zookeeper 127.0.0.1:2181 --list
```

and here we found the topic at hand, you're welcome.



### More about topic

this will show more details about it 

```
./bin/windows/kafka-topics.bat --zookeeper 127.0.0.1:2181 --topic yes_topic --describe
```

Here we see more details, not gonna talk a lot about those in this document, this is something to be described later in a video.

### Delete Topic

 ```
./bin/windows/kafka-topics.bat --zookeeper 127.0.0.1:2181 --topic yes_topic --delete
 ```



## Kafka Producers 

So, this one produce the message from the data source and que it in Kafka, We'll use this producer to send data to the Topic `yes_topic` just remember to <b>yes_topic should be created by this point</b> so if u deleted it just recreate it OK? 



### Send Messages 

```
./bin/windows/kafka-console-producer.bat --broker-list 127.0.0.1:9092 --topic yes_topic
```

<b>Remember that we connect to *Kafka* meaning we provide the *port for Kafka NOT Zookeeper* meaning `127.0.0.1:9092`</b>



### Changing Properties acks

for maximum data security with no data loss, we would change the acks properties of the article, the main "Flags" we have are

* acks=0: No guarantee that the data is received  in the broker <b>Very Risky</b>
* acks=1: the broker send confirmation from the main broker, <b>this is the default</b>
* acks=all: the data is received and recorded throughout the cluster meaning the leader-broker and all it's replicas <b>Very Secure :+1:</b>

So, How can we change it?

```
./bin/windows/kafka-console-producer.bat --broker-list 127.0.0.1:9092 --topic yes_topic --producer-property acks=all
```

#### Publishing to a topic that doesn't exist

if we tried the fallowing:

```
./bin/windows/kafka-console-producer.bat --broker-list 127.0.0.1:9092 --topic mohammad_topic --producer-property acks=all
```

we get a warning BUT Kafka will create a topic in this name although it will take sometime for the leader election to happen. so don't warry about the warning signal, just it's a good practice not to do that for several reasons:

* Low replication factor
* low number of partition

we could change this defaults however in the `server.properties` and get on with our lives ;)

## Consumer for Topics

this command uses 2 arguments to run:

1. Bootstrap server: the same as kafka basically 
2. topic: the name of the topic we are consuming from 

the command goes like this 

```
./bin/windows/kafka-console-consumer.bat --bootstrap-server 127.0.0.1:9092 --topic yes_topic
```

And voila, <b>Nothing Happens :D </b> How beautiful is that, it's because this line shows the messages from the <b>Time of execution onward</b> we can produce right now from the last section and you'll see the new messages. 

#### See the whole topic

```
./bin/windows/kafka-console-consumer.bat --bootstrap-server 127.0.0.1:9092 --topic yes_topic --from-beginning 
```

But it's a little strange isn't it, the order is a bit odd, that's because the que is ordered by partition, and since we created with 3 of them the order will be deferent.



### Consumer Group Mode

first we add the consumer to a group 

```
./bin/windows/kafka-console-consumer.bat --bootstrap-server 127.0.0.1:9092 --topic yes_topic --group the_cult
```

the consumer here is started and is a group member of `the_cult`

now to show the good plus here we execute the fallowing again

```
./bin/windows/kafka-console-consumer.bat --bootstrap-server 127.0.0.1:9092 --topic yes_topic --group the_cult
```

now if we produce enough messages from the command 

```
./bin/windows/kafka-console-producer.bat --broker-list 127.0.0.1:9092 --topic yes_topic
```

we'll see that the messages go to one consumer only, that's because they are a consumer of the same group. helpful? yes! we'll see why later, but now it's good enough to say that each consumer somewhat *subscribes* to a partition. But I will provide some details later.



#### Interesting thing to see

If we tried the same command but with different group name and we wan't the whole que we can write:

```
./bin/windows/kafka-console-consumer.bat --bootstrap-server 127.0.0.1:9092 --topic yes_topic --group the_new_cult
```

we see nothing!! but why? well, the first que was *bookmarked* as dealt with and this command shows the new messages from the moment of bookmarking forward. and we can see that because when finishing the consumer that it actually telling us so.



### Kafka Consumer Group Commands 

we can see a list of all the consumer groups we have created by typing the fallowing command:

```
./bin/windows/kafka-consumer-groups --list
```

