init: docker-down-clear docker-build docker-up
up: docker-up
down: docker-down
restart: docker-down docker

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans
docker-build:
	#mkdir __docker/mysql
	docker-compose build
