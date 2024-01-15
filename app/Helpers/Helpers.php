<?php

namespace App\Helpers;

use Carbon\Carbon;


class Helpers
{
    public static function statusPurchaseRequest($status)
    {
        $statusLang = [
            'pending' => 'Cotización completa',
            'approved' => 'Cotización aprobada',
            'paid' => 'Cotización pagada',
            'rejected' => 'Cotización rechazada'
        ];

        return $statusLang[$status];
    }

    public static function movementTypes($type)
    {
        $movement = [
            'advance' => 'Anticipo',
            'settlement' => 'Liquidación',
            'payment' => 'Abono a cuenta',
        ];

        return $movement[$type];
    }

    public static function business($business)
    {
        $businessLang = [
            'gasStation' => 'Gasolinera',
            'store' => 'Max Store',
            'restaurant' => 'Restaurante',
            'pension' => 'Pension'
        ];

        return $businessLang[$business];
    }

    public static function formatTimezoneToDate($datetime)
    {
        $dt = Carbon::parse($datetime);
        return $dt->format('d-m-Y');
    }
}
