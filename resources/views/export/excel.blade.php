<div>{{ $tableName }}</div>
<table>
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
