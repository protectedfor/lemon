<?php

return [
    'types'               => [
        'service' => 'Услуга',
        'product' => 'Товар'
    ],
    'transaction_types'   => [
        'cash'     => 'Наличные',
        'cashless' => 'Безналичные',
    ],
    'units'               => [
        'pcs' => 'шт.',
        'kg'  => 'кг.',
        'ltr' => 'литр',
    ],
    'currencies'          => [
        'som' => 'Сом',
        'usd' => 'Доллар',
    ],
    'statuses'            => [
        'unpaid'         => 'Не оплачен',
        'partially_paid' => 'Частично оплачен',
        'paid'           => 'Оплачен',
    ],
    'tax_report_types'    => [
        'income_tax'   => 'Налог на прибыль',
        'sales_tax'    => 'Налог с продаж',
        'incoming_tax' => 'Подоходный налог',
    ],
    'tax_report_statuses' => [
        'not_sent' => 'Не отправлен',
        'sent'     => 'Отправлен',
        'received' => 'Принят',
    ],
];
