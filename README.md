# Logging Pipeline 

This Documents Shows How to install the fallowing components:

 2. Kafka: This API is a queuing API

 3. Logstash: This API has some jobs to do with logs 

 4. Elastic Search: Search API, Similar in job to SQL but radically different API.

 5. Kibana: To Interact with Elastic Search 

 6. Grafana: To Draw the data.

 ## Bugs

No Bugs are Known at the moment

 ## Requirements

 1. JDK: I used v12

 2. Node.JS for Kibana

 ## Installing The Materials

 1. Kafka: Install from <https://kafka.apache.org/>, I'm using `kafka_2.12-2.3.0`

 2. Logstash: Install from <https://elasic.co/>  I'm using `logstash-7.2.0`

 3. Elastic Search: Install from <https://elastic.co/>, I'm using `elasticsearch-7.1.1`

 4. Kibana: Install from <https://elastic.co/>, I'm using `kibana-7.1.1-windows-x86_64`

 ### Configurating Zookeeper (Kafka)

Check The Fallowing Lines in `config/zookeeper.properties`

```
clientPort=2181
```

and on `config/server.properties`the fallowing:

 ```

broker.id=0

listeners=PLAINTEXT://:9092

dataDir=Logz

zookeeper.connect=localhost:2181

 ```

 ### Starting Zookeeper 

run the fallowing command, while you are <b>on Kafka files directory </b>:

```
./bin/windows/zookeeper-server-start.bat ./config/zookeeper.properties
```

It should start showing some messages, that's how you know it's running

 ### Starting Kafka Server

run the fallowing command, while you are <b>on Kafka files directory </b>:

```
./bin/windows/kafka-server-start.bat ./config/server.properties
```

again, some messages means that the thing actually work

 ### Configuring Elastic Search

Make sure the fallowing lines are not commented on `./config/elasticsearch.yml`

 ```

cluster.name: Yes-Soft-App

node.name: TEST-NODE-1

 #network.host: 0.0.0.0

http.port: 9200

 ```

 ### Running Elastic Search Engine

run the fallowing command, while you are <b>In Elastic Search Files Directory</b>:

```
./bin/elasticsearch.bat
```

Some messages and you're ok

 ## Logstash Configuration and Start

In your Logstash Directory create the fallowing: 

```
/bin/logstash-kafka.conf
```

and inside this add the fallowing

 ```

input {
kafka {
bootstrap_servers => "localhost:9092"
topics => ["yes_topic"]
}
}
output {
elasticsearch {
hosts => ["localhost:9200"]
index => "yes_topic"
workers => 1
}
}

 ```

Test the config using the command: `./logstash.bat -t -f logstash-kafka.conf`

There will be some warnings BUT you should be able to see the status as `OK`

Then start the `logstash ` using the command: `./logstash.bat -f logstash-app1.conf`

 ### Finally Kibana

Change the fallowing file `config/kibana.yml ` and uncomment the fallowing:

 ```

server.port: 5601

server.host: localhost

elasticsearch.hosts: ["http://localhost:9200"]

 ```

then start Kibana using the command

```
./bin/kibana.bat
```

this will take a while But when it's running it's Finally Over

 ### Testing the Setup 

in `Kafka` Directory Write the Fallowing command in Terminal:

```
./bin/windows/kafka-console-producer.bat --broker-list localhost:9092 --topic yes_topic
```

and write a message

open another tab and write the command:

```
./bin/windows/kafka-console-consumer.bat --bootstrap-server localhost:9092 --topic yes_topic --from-beginning
```

you should be able to see the message here

Now, Open the Kibana Website -- `localhost:5601` -- go to developer tools -- from the sidebar -- and then write the fallowing command

 ```

GET /yes_topic/_search
{
  "query": {
     "match_all": {}
  }
}

 ```

and watch the messages rolling in the second output screen



#### Add Topic 

run the command 

```
./bin/windows/kafka-topics.bat --create --zookeeper localhost:2181 --replication-factor 1 --partitions 1 --topic mohammad
```

