<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# authentification
la commande `composer install laravel:breeze` install tout un system d'authentification avec un dashboard pret a l'emploi, il suffit apres de faire un `php artisan breeze:install` pour le configurer avec les routes. et `npm install && npm run dev` pour mettre a jour les differents modules (css, js)

## etude des views de l'authentification
comment le formulaire utilise la $request pour recuperer les données, on voit que pour le champ remember, il utilise du boulean true or false `$this->boolean('remember');`

`return redirect()->intended(routeserviceprovide::home)` permet de rediriger vers le chemin >Home

ne pas oublier de migrer les tables apres toutes ces commandes `php artisan migrate`

## acces aux differentes views
pour se faire, il faut utiliser les middlewares, (creation du controller et view ainsi que path dans le web).
on peut appelé une function de laravel Auth:: dans le public function __constructer
`$this->middleware('auth')` => verifie si la personne qui consulte la view est connecté 
pour faire des exceptions dans les functions (differente views), il y a la method except ->except('bar') => la vue ou l'on veut que l'indentification ne soit pas prise en compte
dans les vues, on peut directement utiliser les functions d'authentification avec `@auth et @endauth`

on peut bien sur utiliser la function auth ex `{{ Auth::user->name}}`

nous pouvons faire l'inverse avec la methode @guest   @endguest

## authorization
cela permet de verifier le role d'un utilisateur admin/user/privilege
il faut simplement rajouter une colonne dans la table user en  boolean 'admin' : true or false

avec php artisan make:migration name_of_table --table=users   bien preciser la table avec --table

il faut creer la table `$table->boolean('admin`)->default(0)

ne pas oublier de mettre default pour la valeur de chaque utilisateur!

definir une gate (authorization) dans le fichier provider Auth a la section boot

`Gate::define('access-admin', function(User $user) {
            return $user->admin === 1;
        });`

        nous comprenons que la methode gate, nome la function access-admin et cherche la method user et qu'il defini user->admin sur 1 (vaut admin)

puis dans le controller nous utilisons la method gate avec denied ou allow
gate::allow('access-admin');

le return peu être abort(403); qui retourn l'erreur si on est pas `!` admin

# mail function
il faut efffectuer la commande `php artisan make:mail name_of_function`

il construit un fichier mail dans app/mails
pour envoyer il faut aller dans un controller et une function
la function de laravel est `Mail::la_function_desire` pour envoyer le mail `->send(new TestMail());`
on genere l'instance creer avec php artisan make:mail sans rien lui passer (cela envoi la view)
on peut specifier dans notre fichier generer avec make:mail un ->object('titre du message')
utiliser mailtrap.io pour faire des test (symfony,laravel,react)

## pass constant
il est possible de passer a la function et la views des constantes comme $user= new User
qui importe la class user, dans ->to(au lieu du mail, faire $user->email) et dans l'instant de mail $user pour passer a la view
dans la function testmail a public, il faut importer dans le construct `(User $user)` et il faut associer une data public `public $date = [];`  en faisant une association dans $this->data = $user; on peut l'importer dans une autre function

## attacher une pc
pour une piece jointe il faut precier dans le testmail ->attach(), il peut attacher les fichiers du public on peut faire (public_path('')) en php pour suivre un chemin de public

pour lies a notre system de fichier, dans storage local il suffit de taper `attachFromStorage` puis le path

## markdown dans mail!!
dans `php artisan make:mail name -m ` on peut preciser l'argument -m pour markdown et la view mails.markdown
une view markdown est creer et nous pouvons joindre des variable, name, url

on peut customizer le text button etc dans la view par exemple 'color' => 'success'


# notification
Pour faire une notification il faut faire `php artisan make:notification name_of_notification`
il suffit dans le controller ou on veut envoyer une notification par exemple registrer, $user->notify(new instance) => que l'on vient de creer make:notification pour passer des informations il suffit dans la nouvelle instance passer des variables
`notify(new TestNotification($user));`
il faut creer une variable public $utilisateur;
dans la function __construct il faut passer l'argument $user
 `$this->utilisateur = $user;`

 si c'est un array [], il faut bien sur appeller la function  `$this->function['name'];`

 pour utiliser une nouvelle view, php artisan make:mail name charger les variables que l'on veut puis retourner dans la notification dans le dossier app et modifier

## notification dans database
lancer la commande `php artisan notifications:table`
puis il faut la migrer php artisan migrate
dans la table via de notre test notifcation, on ajouter ['mail', 'database'] puis dans public function toarray($notifiable)
on peut passer 
[
    'mon_email' => $notifiable->mail,
]
on peut par se biais recuper les differentes notifications qu'a eu l'utilisateur avec un `@foreach ($user->notifications as $notification) {
    echo $notification->type;
}
@endforeach` dans notre cas `foreach (atuh()->user->notifications() as $notification) {{ $notifications->data['title']}}`

il y a plusieurs options a notification comme markasread (deja lu)  {{ $notification->markasread(); }}
