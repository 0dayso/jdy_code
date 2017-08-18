<lable>投资中</lable>
<table>
    <tr>
        <td>项目信息</td>
        <td>投资时间</td>
        <td>出借金额</td>
        <td>项目进度</td>
    </tr>
    @foreach($list as $record)
        <tr>
            <td>{{ $record['name'] }} {{ $record['profit_percentage'] }} {{ $record['invest_time'] }}</td>
            <td>{{ $record['created_at'] }}</td>
            <td>{{ $record['cash'] }}</td>
            <td>{{ $record['progress'] }}</td>
        </tr>
    @endforeach
</table>
<lable>总页数：{{ $page }} 分页：
    @for ($i = 1; $i <= $page; $i++)
        {{ $i }}
    @endfor
</lable>
