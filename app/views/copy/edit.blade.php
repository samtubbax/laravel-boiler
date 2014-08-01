<!DOCTYPE html>
<html>
<head>
    <title>Copy editing</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Editing Copy</h1>

    <div class="well">
        <h2>Regels:</h2>
        <ul>
            <li>Enkel aanpassen wat er na de => staat en tussen de ‘ ‘ .</li>
            <li>Alle woorden waar een : voor staat, niet veranderen, dit zijn parameters bvb: :name</li>
            <li>Woorden met een ‘s moeten een achterwaartse slash bevatten bvb: "\'s Avonds" of "Mac \'n Cheese"</li>
            <li>Als jullie op de site stukken copy willen aanpassen die jullie niet vinden in deze lijst, neem er dan een screenshot van</li>
        </ul>
    </div>
    <form role="form" action="" method="post" class="form-horizontal">
        @foreach($namespaces as $namespace)
            <h3>{{ ucfirst($namespace['name']) }}</h3>
            @foreach($namespace['translations'] as $translation)
            <div class="form-group">
                {{ Form::label($translation['key'], $translation['name'], array('class' => 'control-label col-sm-2')) }}
                <div class="col-sm-10">
                    {{ Form::text($translation['key'], $translation['value'], array('class' => 'form-control')) }}
                </div>
            </div>
            @endforeach
        @endforeach

        <p>
            <button type="submit" class="btn btn-primary">Save</button>
        </p>
    </form>
</div>
<script src="/js/lib.js"></script>
<script src="/js/Chart.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>