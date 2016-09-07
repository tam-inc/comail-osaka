<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>大阪コメール</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/marx/2.0.4/marx.min.css">
    <style>
        h2, h3 { margin-top: 1.5em; }
    </style>
</head>
<body>
<main>
    <h1>コメール</h1>

    <h2>最近の米炊き当番様</h2>

    <table>
        <thead>
        <tr>
            <th>date</th>
            <th>name</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $today = Carbon\Carbon::now()->toDateString();
        ?>
        @if (empty($ricers[$today]))
            <tr>
                <td>{{ $today }}</td>
                <td>????????</td>
            </tr>
        @endif

        @foreach ($ricers as $date => $ricer)
            <tr>
                <td>{{ $date }}</td>
                <td>{{ $ricer }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h3>お米シートはこちら</h3>
    <p><a href="http://tinyurl.com/tamrice">http://tinyurl.com/tamrice</a></p>
</main>
</body>
</html>
