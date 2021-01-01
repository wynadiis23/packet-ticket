<br/>
dear {{ $customer_detail['name'] }}
<br/>
thank you for ordering with us
<br/>
your order detail is as below
<br/><br/>
---------- Packet Detail----------
<br/>
@foreach ($packet_detail as $packet)
Packet Name : {{ $packet->name }}<br/>
Keterangan : {{ $packet->ket }}<br/>
Tanggal Datang : {{ $packet->tgl_dtg }}<br/>
@endforeach
<br/>
Thank you<br/>
our contact detail as of below<br/>
kathmandu nepal<br/>
ph:0123222<br/>
email : example@example.com<br/>