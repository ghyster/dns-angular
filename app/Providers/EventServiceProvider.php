<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event as Event;
use Aacotroneo\Saml2\Events\Saml2LoginEvent as Saml2LoginEvent;
use App\User as User;
use Illuminate\Support\Facades\Auth as Auth;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'event.name' => [
			'EventListener',
		],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);
		
		Event::listen('Aacotroneo\Saml2\Events\Saml2LoginEvent', function (Saml2LoginEvent $event) {
			
			
            $user = $event->getSaml2User();
            /*$userData = [
                'id' => $user->getUserId(),
                'attributes' => $user->getAttributes(),
                'assertion' => $user->getRawSamlAssertion()
            ];*/
             $laravelUser = User::where("username","=",$user->getUserId())->get()->first();
	    	if($laravelUser!=null){
	    		Auth::login($laravelUser);
	    	}else{
	    		
	    	}
             
             //if it does not exist create it and go on  or show an error message
             
        });
	}

}
