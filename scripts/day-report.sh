#!/bin/bash
cd /var/www/v2.api.aftaa.ru/
#/usr/bin/docker compose exec php symfony console app:day-report
/home/max/.docker/cli-plugins/docker-compose exec php symfony console app:day-report
