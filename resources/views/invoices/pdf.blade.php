@extends('layouts.pdf')
@section('title')
    Счет на оплату №{{ $invoice->id }} от {{ $invoice->human_created_at }}
@endsection
@section('styles')
    <style>
        @page {
            margin: 0;
        }

        .logo {
            margin-top: 30px;
            margin-left: 40px;
        }

        .blue-line {
            background-color: #010937;
            width: 100%;
            color: white;
            margin-top: 40px;
            height: 44px;
            padding-left: 40px;
            padding-top: 15px;
        }

        .invoice-table {
            width: 90%;
            margin-top: 50px;
            margin-left: 40px;
            border: none;
            border-collapse: collapse;
        }

        .margin-left {
            margin-left: 40px;
        }

        .title-h4 {
            font-size: 15px;
            margin-bottom: 0;
            margin-top: 0;
        }

        .executor {
            padding-top: 40px;
        }

        .col-left {
            border-right: 1px solid rgba(1, 9, 55, 0.25);
            padding-right: 20px;
            width: 60%;
        }

        .col-right {
            padding-left: 40px;
        }

        .col-right .title-text {
            font-size: 15px;
        }

        .text-block {
            font-size: 10px;
        }

        .numbers {
            font-size: 20px;
            font-weight: bold;
        }

        .services-table {
            width: 90%;
            margin-top: 50px;
            margin-left: 40px;
            font-size: 12px;
            border-collapse: collapse;
        }

        .services-table thead th {
            text-align: left;
        }

        .services-table tr td, .services-table tr th {
            border-top: 1px solid rgba(1, 9, 55, 0.25);
            border-bottom: 1px solid rgba(1, 9, 55, 0.25);
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .services-table .id {
            width: 2%;
        }

        .services-table .service {
            width: 15%;
        }

        .services-table .pieces {
            width: 5%;
        }

        .services-table .unit {
            width: 5%;
        }

        .services-table .amount {
            width: 5%;
        }

        .services-table .total {
            width: 5%;
        }

    </style>
@endsection
@section('content')
    <img class="logo" src="{{ asset('img/pdf_logo.png') }}" width="150" alt="">
    <div class="blue-line">Счет на оплату №{{ $invoice->id }} от {{ $invoice->human_created_at }}</div>
    <table class="invoice-table">
        <tr>
            <td class="col-left"><h4 class="title-h4">Исполнитель</h4></td>
            <td class="col-right title-text">Всего оказано услуг</td>
        </tr>
        <tr>
            <td class="col-left text-block">{{ implode(', ', $executor_requisites) }}</td>
            <td class="col-right numbers">{{ $invoice->items->count() }}</td>
        </tr>
        <tr>
            <td class="col-left executor"><h4 class="title-h4">Заказчик</h4></td>
            <td class="col-right title-text executor">На сумму</td>
        </tr>
        <tr>
            <td class="col-left text-block">{{ implode(', ', $customer_requisites) }}</td>
            <td class="col-right">
                <span class="numbers">{{ number_format($invoice->total, 0, '', ' ') }} {{ $invoice->human_currency }}</span><br>
                <span class="title-text">{{ $invoice->human_total }} {{ $invoice->human_currency }}</span>
            </td>
        </tr>
    </table>
    <table class="services-table">
        <thead>
        <tr>
            <th>№</th>
            <th>Услуга</th>
            <th>Кол-во</th>
            <th>Ед.</th>
            <th>Цена</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $index => $item)
            <tr>
                <td class="id">{{ ++$index }}</td>
                <td class="service">{{ $item->title }}</td>
                <td class="pieces">{{ $item->quantity }}</td>
                <td class="unit">{{ trans('invoiceOptions.units.' . $item->unit) }}</td>
                <td class="amount">{{ $item->price }}</td>
                <td class="total">{{ $item->price * $item->quantity }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4"></td>
            <td>Итого:</td>
            <td>{{ $invoice->total }}</td>
        </tr>
        </tbody>
    </table>
    <br>
    <div class="margin-left">
        <span><b>Директор:</b> {{ $invoice->organization->director_name }}</span><br>
        <span><b>Бухгалтер:</b> Не предусмотрен</span>
    </div>


@endsection
