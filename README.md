 commment  utiliser  swagger :  
 laravel na pas la dependance  native de swager  cest une dependance a telecharger ; la commmande  composer require "darkaonline/l5-swagger"
  ensuite 
  php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"(le fichier de config : config/l5-swagger.php

le dossier de docs : storage/api-docs

un exemple YAML dans app/Http/swagger (selon la version))
php artisan l5-swagger:generate
pour  la realisation du endpoint en utilisation les  trait un service un resource et une collection de resource  et un  controller
 le trait  est le format de reponse   pour les controller  et resouuce et collection de resource est le format de  donne de la partie de fontend 
  nous avon le service   qui gere et   aplle le represention des donnne  cest le ressource collection (pour  pour chaque resource  a  uneresssource de collection      une collection est un ensemble de  ressource de    estutilser par la collection de ressource  qui sera utilse ensuite par le service  ce dernier utilse   par le controller   cest a dire le controller va utilser le trait et le collection de  ressource)
   _________________ SCOPE _____________________:
   Parfait ! On va voir ce qu’est un scope et comment créer un scope global pour récupérer uniquement les comptes non supprimés.