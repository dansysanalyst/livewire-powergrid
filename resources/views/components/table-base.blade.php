@props([
    'theme' => null
])
<div>
    <table class="{{$theme->tableClass}}" style="{{$theme->tableStyle}}">
        <thead class="{{$theme->theadClass}}" style="{{$theme->theadStyle}}">
                {{ $header }}
        </thead>
        <tbody class="{{$theme->tbodyClass}}" style="{{$theme->tbodyStyle}}">
                {{ $rows }}
        </tbody>
    </table>
</div>