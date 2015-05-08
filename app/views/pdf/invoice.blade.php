@extends('layouts.pdf')


@section('content')


<table cellspacing="0" class="header">
    <thead>
        <tr>
            <th width="80%">&nbsp;</th>
            <th width="10%">&nbsp;</th>
            <th width="10%">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <img src="{{ltrim($branch->image_url,'/')}}" alt="">
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td align="right" class="text textb">Rēķins Nr:</td>
            <td align="right" class="text textn">2015-12</td>
        </tr>
        <tr>
            <td></td>
            <td align="right" class="text textb">Datums:</td>
            <td align="right" class="text textn">{{$date}}</td>
        </tr>
        <tr class="split-row line pink"><td colspan="10">&nbsp;</td></tr>
    </tbody>
</table>

<table cellspacing="0" class="main-body">
    <thead></thead>
    {{-- ----------------   IZSNIEDZA   ---------------- --}}
    <tbody>
        <tr class="split-row"><td colspan="10">&nbsp;</td></tr>

        <tr>
            <td width="18%" class="text textb">Izniedza</td>
            <td width="34%" class="text textb">{{$branch->name}}</td>

            <td width="4%" class="split-col">&nbsp;</td>

            <td width="18%" class="text textn">Reģ. nr.</td>
            <td width="26%" class="text textn">{{$branch->reg_nr}}</td>
        </tr>
        <tr>
            <td width="" class="text textn">Jur. adrese</td>
            <td width="" class="text textn">{{$branch->legal_street}}</td>

            <td width="15" class="split-col">&nbsp;</td>

            <td width="" class="text textn">Konta nr.</td>
            <td width="" class="text textn">{{$branch->bank_nr}}</td>
        </tr>
        <tr>
            <td width="" class="text textn"></td>
            <td width="" class="text textn">{{$branch->legal_postal}}, {{$branch->legal_city}}, {{$branch->legal_country}}</td>

            <td width="15" class="split-col">&nbsp;</td>

            <td width="" class="text textn">Kods</td>
            <td width="" class="text textn">{{$branch->bank_code}}</td>
        </tr>
        <tr>
            <td width="" class="text textn">Banka</td>
            <td width="" class="text textn">{{$branch->bank}}</td>

            <td width="15" class="split-col">&nbsp;</td>

            <td width="" class="text textn"></td>
            <td width="" class="text textn"></td>
        </tr>

        <tr class="split-row line black"><td colspan="10">&nbsp;</td></tr>
    </tbody>
    
    {{-- ----------------   PIEŅĒMA   ---------------- --}}
    <tbody>
        <tr class="split-row"><td colspan="10">&nbsp;</td></tr>

        <tr>
            <td width="18%" class="text textb">Pieņēma</td>
            <td width="34%" class="text textb">{{{ $estimate->project->client->name }}}</td>

            <td width="4%" class="split-col">&nbsp;</td>

            <td width="18%" class="text textn">Reģ. nr.</td>
            <td width="26%" class="text textn">{{{ $estimate->project->client->reg_nr }}}</td>
        </tr>
        <tr>
            <td width="" class="text textn">Jur. adrese</td>
            <td width="" class="text textn">{{{ $estimate->project->client->legal_street }}}</td>

            <td width="15" class="split-col">&nbsp;</td>

            <td width="" class="text textn">Konta nr.</td>
            <td width="" class="text textn">{{{ $estimate->project->client->bank_nr }}}</td>
        </tr>
        <tr>
            <td width="" class="text textn"></td>
            <td width="" class="text textn">
                {{{ $estimate->project->client->legal_postal }}},
                {{{ $estimate->project->client->legal_city }}},
                {{{ $estimate->project->client->legal_country }}}
            </td>

            <td width="15" class="split-col">&nbsp;</td>

            <td width="" class="text textn">Kods</td>
            <td width="" class="text textn">{{{ $estimate->project->client->bank_code }}}</td>
        </tr>
        <tr>
            <td width="" class="text textn">Banka</td>
            <td width="" class="text textn">{{{ $estimate->project->client->bank }}}</td>

            <td width="15" class="split-col">&nbsp;</td>

            <td width="" class="text textn"></td>
            <td width="" class="text textn"></td>
        </tr>


        @if ( $estimate->project->client->contact_name )
            <tr>
                <td width="" class="text textn"></td>
                <td width="" class="text textn"></td>

                <td width="15" class="split-col">&nbsp;</td>

                <td width="" class="text textn">Kontaktpersona</td>
                <td width="" class="text textn">{{{ $estimate->project->client->contact_name }}}</td>
            </tr>
                @if ( $estimate->project->client->contact_details )
                    <tr>
                        <td width="" class="text textn"></td>
                        <td width="" class="text textn"></td>

                        <td width="15" class="split-col">&nbsp;</td>

                        <td width="" class="text textn">Tālr</td>
                        <td width="" class="text textn">{{{ $estimate->project->client->contact_details }}}</td>
                    </tr>
                @endif
        @endif

        <tr class="split-row line black"><td colspan="10">&nbsp;</td></tr>
    </tbody>

    {{-- ----------------   TERMIŅI   ---------------- --}}
    <tbody>
        <tr class="split-row"><td colspan="10">&nbsp;</td></tr>

        <tr>
            <td width="18%" class="text textn">Apmaksas termiņš</td>
            <td width="34%" class="text textn">10 maksājuma dienas</td>

            <td width="4%" class="split-col">&nbsp;</td>

            <td width="18%" class="text textn">Kontaktpersona</td>
            <td width="26%" class="text textn">{{$branch->contact_name}}</td>
        </tr>
        <tr>
            <td width="" class="text textn">Kavējums</td>
            <td width="" class="text textn">0.25% dienā</td>

            <td width="15" class="split-col">&nbsp;</td>

            <td width="" class="text textn">Tālr.</td>
            <td width="" class="text textn">{{$branch->contact_details}}</td>
        </tr>

        <tr class="split-row line pink"><td colspan="10">&nbsp;</td></tr>
    </tbody>
</table>

{{-- ----------------   DARBU TABULA   ---------------- --}}

<table cellspacing="0" class="darbi">
    <thead class="head">
        <tr>
            <th class="darbi" width="80%">Darba pozīcija</th>
            <th class="summa" width="20%">Summa, EUR</th>
        </tr>
    </thead>
    {{-- ----------------   DARBI   ---------------- --}}
    <tbody class="darbi">
        <tr class="split-row">
            <td class="darbi">&nbsp;</td>
            <td class="summa">&nbsp;</td>
        </tr>
        @foreach ($estimate->bill_entries as $entry)
            <tr class="darba-row">
                <td class="darbi">{{$entry->title}}</td>
                <td class="summa">{{$entry->summ * $totals['hidden_perc'] + $entry->summ}}</td>
            </tr>
        @endforeach
        <tr class="split-row">
            <td class="darbi">&nbsp;</td>
            <td class="summa">&nbsp;</td>
        </tr>
    </tbody>

    {{-- ----------------   KOPĀ   ---------------- --}}
    <tbody class="total">
        @if ( $branch->type == 'standart' )
            <tr class="total-row">
                <td width="80%" class="darbi">Kopā</td>
                <td width="20%" class="summa">{{$totals['summ']}}</td>
            </tr>
            <tr class="total-row">
                <td class="darbi">PVN 21%</td>
                <td class="summa">{{$totals['summ_add']}}</td>
            </tr>
            <tr class="total-row final">
                <td class="darbi">Kopā ar PVN</td>
                <td class="summa">{{$totals['summ_total']}}</td>
            </tr>
        @else
            <tr class="total-row final">
                <td width="80%" class="darbi">Kopā</td>
                <td width="20%" class="summa">{{$totals['summ_total']}}</td>
            </tr>
        @endif
    </tbody>

    {{-- ----------------   SPACER   ---------------- --}}
    <tbody class="spacer">
        <tr class="split-row">
            <td class="darbi">&nbsp;</td>
            <td class="summa">&nbsp;</td>
        </tr>
    </tbody>
</table>

<table cellspacing="0" class="summa-vardiem">
    <tr>
        <td>
            Summa vārdiem: {{{ $total_spellout }}}
        </td>
    </tr>
</table>

<table cellspacing="0" class="paraksts">
    <tr>
        <td>
            {{$branch->chairman}}
        </td>
    </tr>
    <tr>
        <td>
            Valdes priekšsēdētājs
        </td>
    </tr>
</table>


@stop