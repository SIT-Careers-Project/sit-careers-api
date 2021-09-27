@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<p>ทั้งนี้ หากมีข้อสงสัยหรือต้องการติดต่อสอบถามข้อมูลเพิ่มเติม สามารถติดต่อได้ที่ คุณรุ่งโรจน์ ขวัญโกมล Tel: 66 2470 9850 E-mail: 
  <a href="mailto:rungroj@sit.kmutt.ac.th">rungroj@sit.kmutt.ac.th</a>
</p>
<hr style="margin-top: 6px; margin-bottom: 3px;"/>
<p style="text-align: center; margin-top: 6px;">© SIT Career Center. All Reserved.</p>
@endcomponent
@endslot
@endcomponent
