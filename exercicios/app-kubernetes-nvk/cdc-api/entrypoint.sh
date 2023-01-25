#!/bin/bash

# Run scheduler
while [ true ]
do
  php /app/artisan schedule:run
  sleep 60
done

