#!/bin/bash
cd /var/www/stroyka-d
php artisan schedule:run >> /dev/null 2>&1
