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