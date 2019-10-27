<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber {

    public function updateJwtData(JWTCreatedEvent $event){
        //Récupérer le user pour ses infos
        $user = $event->getUser();
        
        //Enrichir les datas
        $data = $event->getData();
        $data["firstName"] = $user->getFirstName();
        $data["lastName"] = $user->getLastName();

        $event->setData($data);
    }
    
}