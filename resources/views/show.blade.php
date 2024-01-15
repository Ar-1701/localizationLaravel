<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Localization</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
        }

        html {
            box-sizing: border-box;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        .column {
            float: left;
            width: 33.3%;
            margin-bottom: 16px;
            padding: 0 8px;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            margin: 8px;
        }

        .about-section {
            padding: 50px;
            text-align: center;
            background-color: #474e5d;
            color: white;
        }

        .container {
            padding: 0 16px;
        }

        .container::after,
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        .title {
            color: grey;
        }

        .button {
            border: none;
            outline: 0;
            display: inline-block;
            padding: 8px;
            color: white;
            background-color: #000;
            text-align: center;
            cursor: pointer;
            width: 100%;
        }

        .button:hover {
            background-color: #555;
        }

        @media screen and (max-width: 650px) {
            .column {
                width: 100%;
                display: block;
            }
        }

        img {
            width: auto;
            height: 200px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Hello, Localization! {{ Session::get('lang') }}</h1>
            </div>
            <div class="col-md-2">
                <a href="{{ url('add_post') }}" class="btn btn-primary">Add Post</a>
            </div>
            <div class="col-md-2">
                <form>
                    @csrf
                    <select class="form-select" id='lang' name="lang" onchange="langs()">
                        @foreach ($lang as $lang)
                            @if (Session::get('lang') == $lang->lang)
                                <option selected value="{{ $lang->lang }}">{{ $lang->lang }}</option>
                            @else
                                <option value="{{ $lang->lang }}">{{ $lang->lang }}</option>
                            @endif
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <h2 style="text-align:center" id="postTitle">{{ __('text.postTitle') }}</h2>
                <div class="row">
                    @foreach ($post as $row)
                        <div class="column">
                            <div class="card">
                                <img src="{{ asset('public/upload/post/' . $row->post_img) }}" alt="Jane">
                                <div class="container mt-2">
                                    <h2>{{ $row->post_title }}</h2>
                                    <p class="title">{{ $row->post_cat }}</p>
                                    <p>{{ $row->post_desc }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script>
        function langs() {
            var lang = $("#lang").val();
            $.ajax({
                url: "{{ url('langs') }}",
                type: "post",
                data: {
                    lang: lang,
                    _token: "{{ @csrf_token() }}"
                },
                success: function(data) {
                    // let obj = JSON.parse(data)    
                    // $("#postTitle").html(data.word);
                    // location.reload();
                    console.log(data)
                }
            });
        }
    </script>
</body>

</html>
