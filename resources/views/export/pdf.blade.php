<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ $fileName }}</title>
    <style>
      .table {
          border-collapse: collapse;
      }

      .table, td, th {
          border: 1px solid #e9ecef;
      }

      .table > thead > tr > th {
          background-color: #e9ecef;
        }
    </style>
  </head>
  <body>
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Sr No</th>
            @foreach($tableHead as $i => $head)
                <th>{{ $tableHead[$i]['name'] }}</th>
            @endforeach
        </tr>
    </thead>
      <tbody>
      @foreach($tableBody as $index => $report)
            <tr>
                <td>{{ $index+1 }}</td>
                @foreach($tableHead as $i => $head)
                <td> {{ isset($tableBody[$index][$head['label']]) ? $tableBody[$index][$head['label']] : ""}} </td>

                @endforeach
            </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>
