##
lsof -ti:8000 | xargs kill -9
ommment  utiliser  swagger :  
 laravel na pas la dependance  native de swager  cest une dependance a telecharger ; la commmande  composer require "darkaonline/l5-swagger"
  ensuite 
  php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"(le fichier de config : config/l5-swagger.php
##
le dossier de docs : storage/api-docs

un exemple YAML dans app/Http/swagger (selon la version))
php artisan l5-swagger:generate
pour  la realisation du endpoint en utilisation les  trait un service un resource et une collection de resource  et un  controller
 le trait  est le format de reponse   pour les controller  et resouuce et collection de resource est le format de  donne de la partie de fontend 
  nous avon le service   qui gere et   aplle le represention des donnne  cest le ressource collection (pour  pour chaque resource  a  uneresssource de collection      une collection est un ensemble de  ressource de    estutilser par la collection de ressource  qui sera utilse ensuite par le service  ce dernier utilse   par le controller   cest a dire le controller va utilser le trait et le collection de  ressource)
   _________________ SCOPE _____________________:
   Parfait ! On va voir ce qu’est un scope et comment créer un scope global pour récupérer uniquement les comptes non supprimés.
   pour mon deployement dur render  jai utilser docker file 
    jai d'abord :sudo docker build -t gestioncomptes .
 ensuite creer le tag:sudo docker tag gestioncomptes amycolle/amycollendiaye-gest-comptes
       ->docker login
        et  push de imahe sur docker hub :sudo docker push  amycolle/amycollendiaye-gest-comptes

 pour faire la mise a  jouse  de version php en laravel il faut composer update


 ____________
___Comment utiliser auth et passport
  il faut dabord installler la dependance:composer require laravel/passport
  puis faire la migrtaion:php artisan migrate
php artisan passport:install
  configuration de  auth.php dans le dossier  config  si cest un api je  les parametres de confih

   la gestion des permission et authentifications 
   use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;

protected $middlewareAliases = [
    'role' => RoleMiddleware::class,
    'permission' => PermissionMiddleware::class,
    'role_or_permission' => RoleOrPermissionMiddleware::class,
];

$admin = Role::create(['name' => 'admin']);
$client = Role::create(['name' => 'client']);

Permission::create(['name' => 'creer_comptes']);
Permission::create(['name' => 'voir_comptes']);
Permission::create(['name' => 'supprimer_comptes']);

// Donner toutes les permissions à l’admin
$admin->givePermissionTo(Permission::all());

// Donner seulement la permission de voir au client
$client->givePermissionTo('voir_comptes');

$user = App\Models\User::find(b1d64533-0a9a-325e-bd4e-879c0b1d1156
); 


$admin->givePermissionTo(['voir_comptes', 'creer_comptes', 'supprimer_comptes']);
$client->givePermissionTo(['voir_comptes']);
$user->assignRole('admin');

// ou
$user->assignRole('client');

use App\Models\Compte;

public function index()
{
    $user = Auth::user();

    if ($user->hasRole('admin') && $user->can('voir tous les comptes')) {
        $comptes = Compte::where('statut', 'actif')->get();
    } elseif ($user->hasRole('client') && $user->can('voir mes comptes')) {
        $comptes = Compte::where('user_id', $user->id)
                         ->where('statut', 'actif')
                         ->get();
    } else {
        return response()->json(['message' => 'Accès refusé'], 403);
    }

    return response()->json($comptes);
}

/// lister les   admins crees sur bash  
php artisan tinker --execute="App\Models\Admin::with('user')->get()->each(function(\$admin) { echo \$admin->matricule . ' - ' . \$admin->user->nom . ' ' . \$admin->user->prenom . ' (' . \$admin->user->email . ')' . PHP_EOL; })"
