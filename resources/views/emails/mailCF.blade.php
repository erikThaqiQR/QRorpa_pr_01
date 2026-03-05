<style>
    table, th, td {
        border: 1px solid black;
    }
    td{
        text-align: center;
        padding:10px 40px 10px 40px;
    }
    table {
        border-collapse: collapse;
    }
</style>
<table>
    <tbody>
        <tr>
            <td>Name</td>
            <td>{{ $name }}</td>
        </tr>
        <tr>
            <td>Email </td>
            <td>{{ $email }}</td>
        </tr>
        <tr>
            <td>Subject </td>
            <td>{{ $subject }}</td>
        </tr>
        <tr>
            <td>Message </td>
            <td>{{ $msg }}</td>
        </tr>
    </tbody>
</table>

