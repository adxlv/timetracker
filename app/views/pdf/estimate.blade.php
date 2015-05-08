@extends('layouts.pdf')


@section('content')


<table cellspacing="0" class="header estimate">
    <thead>
        <tr>
            <th width="40%">&nbsp;</th>
            <th width="10%">&nbsp;</th>
            <th width="50%">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            
            <td></td>
        </tr>
        <tr>
            <td rowspan="6" valign="top">
                <img src="{{ltrim($branch->image_url,'/')}}" alt="">
                {{-- <img src="{{$branch->image_url}}" alt=""> --}}
            </td>
            <td align="right" class="text textn">Klients:</td>
            <td align="left" class="text textb">{{$estimate->project->client->title}}</td>
        </tr>
        <tr>
            <td align="right" class="text textn">Aģentūra:</td>
            <td align="left" class="text textb">{{$branch->title}}</td>
        </tr>
        <tr>
            <td align="right" class="text textn">Adrese:</td>
            <td align="left" class="text textb">{{$branch->legal_city}}, {{$branch->legal_street}}</td>
        </tr>
        <tr>
            <td align="right" class="text textn">Telefons:</td>
            <td align="left" class="text textb">{{$branch->contact_details}}</td>
        </tr>
        <tr>
            <td align="right" class="text textn">Projekts:</td>
            <td align="left" class="text textb">{{$estimate->title}}</td>
        </tr>
        <tr>
            <td align="right" class="text textn">&nbsp;</td>
            <td align="left" class="text textn">{{$date}}</td>
        </tr>
        <tr class="split-row line"><td colspan="10">&nbsp;</td></tr>
    </tbody>
</table>

<table cellspacing="0" class="main-body estimate">
    <thead></thead>
    {{-- ----------------   IZSNIEDZA   ---------------- --}}
    <tbody>
        <tr>
            <td align="left" class="text textb" width="100%">{{$estimate->description}}</td>
        </tr>
    </tbody>
</table>

{{-- ----------------   DARBU TABULA   ---------------- --}}

<table cellspacing="0" class="darbi estimate">
    <thead class="head">
        <tr>
            <th class="darbi" rowspan="2">Aģentūras darbs (EUR/h)</th>
            @foreach ($estimate->roles as $role)
                <th class="jobroles" width="30" >{{$role}}</th>
            @endforeach
            <th class="jobroles" width="30">Kopā</th>
        </tr>
        <tr>
            @foreach ($estimate->involved_roles->salary as $summ)
                <th class="workhour" >{{$summ}}</th>
            @endforeach
            <th class="workhour" >EUR</th>
        </tr>
    </thead>
    {{-- ----------------   DARBI   ---------------- --}}
    <tbody class="darbi">
        <tr class="split-row">
            @foreach ($estimate->involved_roles->id as $id)
                <td class="summa">&nbsp;</td>
            @endforeach
            <td class="summa">&nbsp;</td>
            <td class="summa">&nbsp;</td>
        </tr>

        @foreach ($estimate->entries as $entry)
            <tr class="darba-row {{($entry->is_header)?'group-header':''}}">
                <td class="darbi">{{$entry->title}}</td>
                @foreach ($entry->hours as $h)
                    <td class="hours"> {{($entry->is_header)?'':$h}} </td>
                @endforeach
                <td class="summa">{{($entry->is_header)?'':$entry->total}} </td>
            </tr>
        @endforeach

        <tr class="split-row">
            @foreach ($estimate->involved_roles->id as $id)
                <td class="summa">&nbsp;</td>
            @endforeach
            <td class="summa">&nbsp;</td>
            <td class="summa">&nbsp;</td>
        </tr>
    </tbody>

    {{-- ----------------   KOPĀ   ---------------- --}}
    <tbody class="total">
        @if ( $branch->type == 'standart' )
            <tr class="total-row" >
                <td class="darbi textb" colspan="{{count($estimate->involved_roles->id)+1}}">Kopā:</td>
                <td class="summa">{{$totals['summ']}}</td>
            </tr>
            <tr class="total-row">
                <td class="darbi" colspan="{{count($estimate->involved_roles->id)+1}}">PVN 21%</td>
                <td class="summa">{{$totals['summ_add']}}</td>
            </tr>
            <tr class="total-row final">
                <td class="annotation" colspan="{{count($estimate->involved_roles->id)-1}}">
                    *tāmē nav iekļautas: fontu un bilžu fotografēšana vai iegāde
                </td>
                <td class="darbi" colspan="2">Kopā ar PVN</td>
                <td class="summa">{{$totals['summ_total']}}</td>
            </tr>
        @else
            <tr class="total-row final">
                <td class="annotation" colspan="{{count($estimate->involved_roles->id)-1}}">
                    *tāmē nav iekļautas: fontu un bilžu fotografēšana vai iegāde
                </td>
                <td class="darbi textb" colspan="2">Kopā:</td>
                <td class="summa">{{$totals['summ_total']}}</td>
            </tr>
        @endif
    </tbody>


    {{-- ----------------   SPACER   ---------------- --}}
    <tbody class="spacer">
        <tr class="split-row">
            <td class="darbi">&nbsp;</td>
            <td class="darbi">&nbsp;</td>
            <td class="darbi">&nbsp;</td>
            <td class="darbi">&nbsp;</td>
            <td class="darbi">&nbsp;</td>
            <td class="darbi">&nbsp;</td>
            <td class="darbi">&nbsp;</td>
            <td class="darbi">&nbsp;</td>
        </tr>
    </tbody>
</table>


<table cellspacing="0" class="paraksts estimate">
    <tr>
        <td>
            Aģentūra:
        </td>
    </tr>
    <tr>
        <td>
            {{$branch->contact_name}}
        </td>
    </tr>
    <tr>
        <td>
            {{$branch->title}}
        </td>
    </tr>
</table>


@stop