<?php
/**
 * Event
 * @package site-event
 * @version 0.0.1
 */

namespace SiteEvent\Meta;

class Event{
    static function index(array $events, int $page){
        $result = [
            'head' => [],
            'foot' => []
        ];

        $home_url = \Mim::$app->router->to('siteHome');
        $curr_url = \Mim::$app->router->to('siteEventIndex');

        $meta = (object)[
            'title'         => 'Events',
            'description'   => 'Collection of events collected by month',
            'schema'        => 'WebPage',
            'keyword'       => ''
        ];

        $result['head'] = [
            'description'       => $meta->description,
            'schema.org'        => [],
            'type'              => 'website',
            'title'             => $meta->title,
            'url'               => $curr_url,
            'metas'             => []
        ];

        // schema breadcrumbList
        $result['head']['schema.org'][] = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $home_url,
                        'name' => \Mim::$app->config->name
                    ]
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'item' => [
                        '@id' => $home_url . '#events',
                        'name' => $meta->title
                    ]
                ]
            ]
        ];

        return $result;
    }

    static function single(object $page): array{
        $result = [
            'head' => [],
            'foot' => []
        ];

        $home_url = \Mim::$app->router->to('siteHome');

        // reset meta
        if(!is_object($page->meta))
            $page->meta = (object)[];

        $def_meta = [
            'title'         => $page->title,
            'description'   => $page->content->chars(160),
            'schema'        => 'Event',
            'keyword'       => ''
        ];

        foreach($def_meta as $key => $value){
            if(!isset($page->meta->$key) || !$page->meta->$key)
                $page->meta->$key = $value;
        }

        $result['head'] = [
            'description'       => $page->meta->description,
            'published_time'    => $page->created,
            'schema.org'        => [],
            'type'              => 'article',
            'title'             => $page->meta->title,
            'updated_time'      => $page->updated,
            'url'               => $page->page,
            'metas'             => []
        ];

        // schema breadcrumbList
        $event_index = \Mim::$app->router->to('siteEventIndex');
        $result['head']['schema.org'][] = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $home_url,
                        'name' => \Mim::$app->config->name
                    ]
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'item' => [
                        '@id' => $event_index,
                        'name' => 'Events'
                    ]
                ]
            ]
        ];

        // schema logo
        $meta_image = null;
        if($page->cover->url && $page->cover->url->target){
            $meta_image = [
                '@context'   => 'http://schema.org',
                '@type'      => 'ImageObject',
                'contentUrl' => $page->cover->url,
                'url'        => $page->cover->url
            ];
        }

        $sameAs = [];
        if($page->socials){
            foreach($page->socials as $url)
                $sameAs[] = $url;
        }

        // schema page
        $schema = [
            '@context'      => 'http://schema.org',
            '@type'         => $page->meta->schema,
            'name'          => $page->meta->title,
            'description'   => $page->meta->description,
            'url'           => $page->page,
            'offers'        => [],
            'startDate'     => $page->time_start->format('r'),
            'endDate'       => $page->time_end->format('r')
        ];

        if($meta_image)
            $schema['image'] = $meta_image;

        // performers
        if(isset($page->performers)){
            $schema['performer'] = [];
            foreach($page->performers as $perf){
                $schema['performer'][] = [
                    '@type'  => 'Person',
                    'name'   => $perf->fullname
                ];
            }
        }

        $schema['location'] = [
            '@type'   => 'Place',
            'name'    => $page->title,
            'address' => $page->address
        ];
        if(isset($page->organizer)){
            $schema['location'] = [
                '@type'   => 'Place',
                'name'    => $page->title,
                'address' => $page->address
            ];
        }

        if($meta_image)
            $schema['image'] = $meta_image;

        if($sameAs)
            $schema['sameAs'] = $sameAs;

        if($page->ages->value)
            $schema['typicalAgeRange'] = $page->ages;

        $result['head']['schema.org'][] = $schema;

        return $result;
    }
}
