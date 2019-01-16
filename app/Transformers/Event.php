<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class Event extends TransformerAbstract
{
    public function transform($event)
    {
        return [
            'title' => $event['title'],
            'start' => $event['start'],
            'end' => $event['end'],
            'allDay' => ($event['allDay'] === 'true') ? true : false,
            'color' => $event['color'],
            'textColor' => $event['textColor'],
            'scheme_id' => $event['id'],
            'scheme' => $event['table_name'],
        ];
    }
}