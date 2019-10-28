<?php
/**
 * EventController
 * @package site-event
 * @version 0.0.1
 */

namespace SiteEvent\Controller;

use SiteEvent\Meta\Event as Meta;
use Event\Model\Event;
use LibFormatter\Library\Formatter;

class EventController extends \Site\Controller
{
    public function indexAction(){
        list($page, $rpp) = $this->req->getPager();

        $events = Event::get([], $rpp, $page, ['id'=>false]);
        if($events)
            $events = Formatter::formatMany('event', $events, ['user']);
        
        $params = [
            'events' => $events,
            'meta'   => Meta::index($events, $page)
        ];

        $this->res->render('event/index', $params);
        $this->res->setCache(86400);
        $this->res->send();
    }

    public function singleAction(){
        $identity = $this->req->param->slug;

        $event = Event::getOne(['slug'=>$identity]);
        if(!$event)
            return $this->show404();

        $fopt = ['user'];

        if(module_exists('event-venue'))
            $fopt[] = 'organizer';
        if(module_exists('event-profile'))
            $fopt[] = 'performers';

        $event = Formatter::format('event', $event, $fopt);

        $params = [
            'event' => $event,
            'meta'  => Meta::single($event)
        ];

        $this->res->render('event/single', $params);
        $this->res->setCache(86400);
        $this->res->send();
    }
}