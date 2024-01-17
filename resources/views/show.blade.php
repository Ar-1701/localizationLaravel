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

    <div class="container mb-4">
        <div class="row">
            <div class="col">
                <ul class="nav justify-content-center">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">show By Language</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('add_post') }}">Add Post</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('posts') }}">All Post</a>
                    </li>
                    <li>
                        @include('lang')
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">

                <h1>{{ __('text.heading') }}</h1>
            </div>


        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 style="text-align:center" id="postTitle">{{ __('text.postTitle') }}</h2>
                <div class="row loadData">
                    {{-- @foreach ($post as $row)
                        <div class="column">
                            <div class="card">
                                <img src="{{ asset('public/upload/post/' . $row->post_img) }}" alt="Jane">
                                <div class="container mt-2">
                                    <h2>{{ substr($row->post_title, 0, 50) . '...' }}</h2>
                                    <p class="title">{{ $row->post_cat }}</p>
                                    <p>{{ substr($row->post_desc, 0, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-md-12 text-center mb-2">
                        <button class="btn btn-success w-25 text-center loadBtn" id="loadBtn"
                            data-id='{{ $row->id }}'>Load
                            More</button> --}}
                </div>
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
        $(document).ready(function() {


        });

        function loadData(page) {
            $.ajax({
                url: "{{ url('loadData') }}",
                type: "post",
                data: {
                    page: page,
                    _token: "{{ @csrf_token() }}"
                },
                success: function(data) {
                    // var o = $.parseJSON(data);
                    // $("#loadBtn").attr('data-id', o.pid);
                    if (data) {
                        $(".btnBox").remove();
                        $(".loadData").append(data)
                    } else {
                        $("#loadBtn").prop("disabled", true).html('No Data');
                    }
                }
            });
        }
        loadData();

        function loadBtn() {
            var pid = $("#loadBtn").data('id');
            console.log(pid)
            loadData(pid);
        }
    </script>
</body>

</html>
