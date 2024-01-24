<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/custome-form-style.css') }}" />
    <style>
        .sortable-clicked {
            border: 2px solid #685dd8;
        }

        .sortable-hover {
            animation: hoverEffect 3s;
        }

        @keyframes hoverEffect {
            from {
                box-shadow: 0 0 10px #685dd8;
            }

            to {
                box-shadow: none;
            }
        }

        .rendered-form>div {
            border: 1px  solid darkgray;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        label:not([for^="checkbox"]) {
            display: block;
            padding-bottom: .5rem;
        }

        input[type="checkbox"]+label {
            display: inline-block;
            padding-left: .5rem;
        }

        input:not([type="checkbox"]) {
            display: block;
            padding: .8rem .5rem;
            margin: .4rem 0;
            width: 300px;
            border: 1px solid rgb(65, 65, 65);
        }

        li::marker {
            color: rgb(77, 20, 86);
        }

        * {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            unicode-bidi: embed;
        }

    </style>
</head>

<body style="border: 1px solid rgb(77, 20, 86)">
    <div style="text-align: center; font-size: 24px; padding:2rem 0; border:1px solid darkgray; width:fit-content">
        {{ $form->name }}
    </div>

    <div style="padding:3rem">
        {!! $content !!}
    </div>
</body>

</html>
