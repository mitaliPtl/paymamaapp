
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<style>
    h1 {
        color: navy;
        font-family: times;
        font-size: 13pt;
        color:black
    }
    table.first {
    
        color: #003300;
        font-family: helvetica;
        font-size: 8pt;
        background-color: white;
        width: 100%;
        text-align:center;
    }
    td {
        border: none;
    }
    .page-break {
        page-break-after: always;
    }

</style>
    @if(is_array($data))
    @foreach($data as $rec){
    <table class="first" cellpadding="4" cellspacing="6">
        <tr><td width="100"></td><td width="100" align="center"><img src="{{ $path }}"/></td><td width="100"></td></tr><br />
        <tr><td width="100"></td><td width="150" align="left"><h4>{{ $type }} Receipt</h4></td><td width="50" align="left"></td></tr>
        <tr><td width="160" align="left">Aadhar No.: {{ $rec->aadhar_number }}</td><td width="140" align="left">Date: {{ $rec->aeps_date_time }}</td></tr>
        <br /><br />
    </table>
    <table border="1" class="first" cellpadding="4" cellspacing="6">
        <tr>
            <th width="75" align="center">Date</th>
            <th width="75" align="center">Type</th>
            <th width="75" align="center">Amount</th>
            <th width="75" align="center">Naration</th>
        </tr>
        @php $msdate=explode(',',$rec->msdate);$mstratype=explode(',',$rec->mstratype);$msamount=explode(',',$rec->msamount);$msnaration=explode(',',$rec->msnaration); @endphp
        @for($i=0;$i<count($msdate);$i++)
        <tr>
            <td width="75" align="center">{{ $msdate[$i] }}</td>
            <td width="75" align="center">{{ $mstratype[$i] }}</td>
            <td width="75" align="center">{{ $msamount[$i] }}</td>
            <td width="75" align="center">{{ $msnaration[$i] }}</td>
        </tr>
        @endfor
    </table>
    <div class="page-break"></div>
    <br /><br /><br /><br /><br /><br /><br /><br /><br />
    <table class="first" cellpadding="4" cellspacing="6">
        <tr><td width="50"></td><td width="100" align="left">Bank Name:</td><td width="150" align="left">{{ $rec->bankName }}</td></tr>
        <tr><td width="50"></td><td width="100" align="left">Bank UTR No.:</td><td width="150" align="left">{{ $rec->utr }}</td></tr>
        <tr><td width="50"></td><td width="100" align="left">Outlet Name:</td><td width="150" align="left">{{ $rec->cus_name }}</td></tr>
        <tr><td width="50"></td><td width="100" align="left">Outlet Mobile:</td><td width="150" align="left">{{ $rec->cus_mobile }}</td></tr>
        <br />
    </table>
    <br /><br /><br />
    <h5 align="center">Powered By: ICICI Bank</h5><br /><br />
    @endforeach
    @endif
?>                    
