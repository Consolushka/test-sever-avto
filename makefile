install:
	cp -n .env.example .env
	docker volume create sever-avto
	docker-compose build
	docker-compose up -d
	docker exec -i sever-avto_app composer install
	docker exec -i sever-avto_app php artisan key:generate
	echo 'create database sever_avto' | docker exec -i sever-avto_db psql -U postgres
	docker exec -i sever-avto_app php artisan migrate

stop:
	docker-compose stop

down:
	docker-compose down

up:
	docker-compose up -d

sh:
	docker exec -it sever-avto_app sh