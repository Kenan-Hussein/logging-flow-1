# logging-flow

## Grafana Dashboard
Grafana is an open source solution for running data analytics, pulling up metrics that make sense of the massive amount of data & to monitor our apps with the help of cool customizable dashboards.

Grafana connects with every possible data source, commonly referred to as databases such as Graphite, Prometheus, Influx DB, ElasticSearch, MySQL, PostgreSQL etc.

### Install And Configuration Grafana 
1. First download [Grafana](https://grafana.com/grafana/download?platform=windows) For Windows, (You can download it as zip file)
2. Important: After you’ve downloaded the zip file and before extracting it, make sure to open properties for that file (right-click Properties) and check the `unblock` checkbox and `Ok`.
3. Extract this folder to anywhere you want
4. Go into the `conf` directory and copy `sample.ini` to `custom.ini`. You should edit `custom.ini`, never `defaults.ini`.
5. Edit `custom.ini` and uncomment the `http_port` configuration option and change it to something like `8080` or similar.
6. Start Grafana by executing `grafana-server.exe`, located in the `bin` directory.
7. Default login and password `admin`/ `admin` .
8. To run Grafana open your browser and go to the port you configured above, e.g. `http://localhost:8080/`. 

### The Basics Steps For Using Elasticsearch In Grafana 
To Use Grafana We Have TO Follow These Steps :
1. From Side Menu On Configuration Option We Select `Data Sources`
2. Select The Data Sources We Want ex : `Elasticsearch`
3. Inserted Our Data Into Elasticsearch Window
4. Create New Dashboard
5. Add New Panel
6. Edit This Panel By Clicking On The Title And Select `Edit`
7. By These Steps We Will Have First Simple Graph


### Important information you should know before start
1. Must have an `ELK Stack (elasticsearch, Logstash, Kibana)` up and running on your device .
2. Must create at least simple data from `Kibana` 

![Kibana Simple Data](kibana_simple_data.png "Simple Kibana Data")

3.fetching the `elasticsearch index name` from `/_cat/indices` as following :

![Elasticsearch Index Name](elastic_indices.png "Elasticsearch Index Name")

### Adding the data source
1- In the side menu under the `Configuration` link you should find a link named `Data Sources`.

2- Click the `+ Add data source` button in the top header.

3- Select `Elasticsearch` from the Type dropdown.

4- This window will be display

![Elasticsearch Window](datasources_window.png "Elasticsearch data sources Window")

    Name	: The data source name. This is how you refer to the data source in panels & queries.
    Default	: Default data source means that it will be pre-selected for new panels.
    Url     : The HTTP protocol, IP, and port of your Elasticsearch server.
    Access	: Server (default) = URL needs to be accessible from the Grafana backend/server, Browser = URL needs to be accessible from the browser.

5- add `index name` from `/_cat/indices`, We can ignore the pattern .

6- Time field is for `timestamp`

7- version : is the version you use .

![Elastic Data Source](elastic_data_source.png "Elastic Data Source")


### Creating Grafana Dashboard
1. Create new dashboard, and select `Add Query` .
2. To edit the graph, you need to click the `panel title` and then `Edit`.
3. Delete the fake data source that is using to help us get started, And then adding new query for our source data `ex :Elastic Matrics` .
4. `Lucene query` use to filter our graph .
5. From `Matric` field select one of the options `ex: count` .
6. For `Group by` leave it for now on `data histogram` to `timestamp` .
7. By These Steps We Will Have First Simple Graph
8. for inserting two elasticsearch data source we will have something like below :

![Grafana Dashboard](grafana_elk_dash.png "Grafana ELK Dashboard")


