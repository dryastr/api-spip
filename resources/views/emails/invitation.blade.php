@component('mail::message')
# Undangan Sebagai Penilai

Halo {{ $namaPenerima }},

Kami ingin mengundang Anda untuk menjadi salah satu penilai dalam kegiatan evaluasi {{ $namaPemdaOpd }}. Peran Anda sebagai penilai sangat berharga bagi kami.

[Link Evaluasi]({{ $linkEvaluasi }})

Terima kasih banyak atas kontribusi Anda. Jika Anda memiliki pertanyaan atau memerlukan informasi tambahan, jangan ragu untuk menghubungi kami.

Salam,  
{{ config('mail.from.name') }}
@endcomponent
