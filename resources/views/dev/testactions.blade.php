<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TEST BACK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    @livewireStyles

  </head>
  <body>



    <div class="container">
        <div class="row mt-4">

            <div class="col-12">
               <div class="list-group list-group-checkable d-grid gap-2 border-0 w-auto">
                 <input class="list-group-item-check pe-none" type="radio" name="listGroupCheckableRadios" id="listGroupCheckableRadios1" value="" checked="">
                 <label class="list-group-item rounded-3 py-3" for="listGroupCheckableRadios1">
                  FILES
                   <span class="d-block small opacity-50">avatars, passport, diploms</span>
                 </label>

                 <input class="list-group-item-check pe-none" type="radio" name="listGroupCheckableRadios" id="listGroupCheckableRadios2" value="">
                 <label class="list-group-item rounded-3 py-3" for="listGroupCheckableRadios2">
                  Payment
                   <span class="d-block small opacity-50">pay</span>
                 </label>

                 <input class="list-group-item-check pe-none" type="radio" name="listGroupCheckableRadios" id="listGroupCheckableRadios3" value="">
                 <label class="list-group-item rounded-3 py-3" for="listGroupCheckableRadios3">
                   DEV
                   <span class="d-block small opacity-50">other test</span>
                 </label>
                </div>
            </div>
            
            <div class="col-12">
                <livewire:dev-controller /> 
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <script src="{{ asset('js/app.js') }}" defer></script>

  @livewireScripts


  {{-- CUSTOM DEV --}}
  <style type="text/css">
      
      .list-group {
        max-width: 460px;
        margin: 4rem auto;
      }

      .form-check-input:checked + .form-checked-content {
        opacity: .5;
      }

      .form-check-input-placeholder {
        border-style: dashed;
      }
      [contenteditable]:focus {
        outline: 0;
      }

      .list-group-checkable .list-group-item {
        cursor: pointer;
      }
      .list-group-item-check {
        position: absolute;
        clip: rect(0, 0, 0, 0);
      }
      .list-group-item-check:hover + .list-group-item {
        background-color: var(--bs-light);
      }
      .list-group-item-check:checked + .list-group-item {
        color: #fff;
        background-color: var(--bs-blue);
      }
      .list-group-item-check[disabled] + .list-group-item,
      .list-group-item-check:disabled + .list-group-item {
        pointer-events: none;
        filter: none;
        opacity: .5;
      }

      .list-group-radio .list-group-item {
        cursor: pointer;
        border-radius: .5rem;
      }
      .list-group-radio .form-check-input {
        z-index: 2;
        margin-top: -.5em;
      }
      .list-group-radio .list-group-item:hover,
      .list-group-radio .list-group-item:focus {
        background-color: var(--bs-light);
      }

      .list-group-radio .form-check-input:checked + .list-group-item {
        background-color: var(--bs-body);
        border-color: var(--bs-blue);
        box-shadow: 0 0 0 2px var(--bs-blue);
      }
      .list-group-radio .form-check-input[disabled] + .list-group-item,
      .list-group-radio .form-check-input:disabled + .list-group-item {
        pointer-events: none;
        filter: none;
        opacity: .5;
      }

  </style>

  </body>
</html>
