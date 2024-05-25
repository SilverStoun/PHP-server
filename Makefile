#!/usr/bin/make

DOCKER_CONTAINER_NAME ?= php-server-container
DOCKER_IMAGE_NAME ?= php-server-image
CONTAINER_PORT ?= 3000

.PHONY : help install build up down clean

.DEFAULT_GOAL := help

help:
	@echo "\n \
	*********************\n \
	*** Make commands ***\n \
	*********************\n\n \
	help	- Show this help\n \
	install	- Install dependencies\n \
	build	- Build image\n \
	up		- Run container\n \
	down	- Stop container\n \
	clean	- Remove image\n \
	"

install:
	@docker run --rm --interactive --tty --volume $PWD:/app composer install

build:
	@docker build -t ${DOCKER_IMAGE_NAME} .

up:
	@docker run -p ${CONTAINER_PORT}:80 --rm --name ${DOCKER_CONTAINER_NAME} ${DOCKER_IMAGE_NAME}

down:
	@docker stop ${DOCKER_CONTAINER_NAME}

clean:
	@docker rmi ${DOCKER_IMAGE_NAME}
