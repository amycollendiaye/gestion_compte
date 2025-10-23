 commment  utiliser  swagger :  
 laravel na pas la dependance  native de swager  cest une dependance a telecharger ; la commmande  composer require "darkaonline/l5-swagger"
  ensuite 
  php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"(le fichier de config : config/l5-swagger.php

le dossier de docs : storage/api-docs

un exemple YAML dans app/Http/swagger (selon la version))
php artisan l5-swagger:generate

