# symfony-rest-ddd-sample-2019


1. You can see all REST endpoints in the documentation ./documentation/api/_doc.html
2. You should have two tools on your PC:
- Docker
- Docker-compose
3. To run the application go to ./server and install vendor libraries
(something like "docker run --rm -it --volume $(pwd):/app prooph/composer:7.2 -vvv --ignore-platform-reqs install")
- then "docker-compose up"
- Now you can work with the application through the port 80.
4. To run API tests:
- go inside the container (something like "docker exec -it bets-server_php_1 bash")
- run ./bin/run-api-tests.bash