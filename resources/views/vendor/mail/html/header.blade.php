<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img style="width: 160px; height: 70px; margin-right: 20px;" src="https://www.sit.kmutt.ac.th/wp-content/uploads/2018/05/logo-flat-blk.png" class="logo" alt="SIT Logo">
<img style="width: 165px; height: 55px; padding-bottom: 7px;" src="https://carbon-media.accelerator.net/0000000mbPi/3ugeVBjdDhveKjBWAbvwGe;471x132.png" class="logo" alt="SIT Career Center Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
