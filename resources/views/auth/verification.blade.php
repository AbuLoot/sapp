<x-app-layout>
  <?php $uri = explode('/', Request::path()); ?>
  <x-slot name="title">
    {{ ucfirst($uri[1]) }}
  </x-slot>

  <div class="row">
    <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">

      <x-auth-validation-errors :errors="$errors" />

      <form method="POST" action="/{{ Request::path() }}" class="p-4 p-md-5 bg-light border rounded-3 shadow- bg-light">
        @csrf

        <h2 class="fw-bold mb-0">Верификация</h2><br>

        <div class="form-floating mb-3">
          <input type="password" name="code" class="form-control rounded-3" id="code" placeholder="Введите код" required>
          <label for="code">Введите код</label>
        </div>

        <div class="row gx-2 gy-2 h-100">
          <div class="col-4 d-grid"><input type="button" value="7" onclick="display(9)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><input type="button" value="8" onclick="display(8)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><input type="button" value="9" onclick="display(9)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><input type="button" value="4" onclick="display(4)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><input type="button" value="5" onclick="display(5)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><input type="button" value="6" onclick="display(6)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><input type="button" value="1" onclick="display(1)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><input type="button" value="2" onclick="display(2)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><input type="button" value="3" onclick="display(3)" class="btn btn-primary btn-lg fs-2"></div>

          <div class="col-4 d-grid"><button type="button" value="clear" onclick="clearDisplay()" class="btn btn-primary btn-lg fs-2"><i class="bi bi-backspace"></i></button></div>
          <div class="col-4 d-grid"><input type="button" value="0" onclick="display(0)" class="btn btn-primary btn-lg fs-2"></div>
          <div class="col-4 d-grid"><button type="submit" class="btn btn-primary btn-lg fs-2"><i class="bi bi-arrow-return-left"></i></button></div>
        </div>
      </form>
    </div>
  </div>

  <script>
    let inputCode = document.getElementById('code');
    inputCode.focus();

    // Displaying values
    function display(val) {
      inputCode.value += val;
    }

    // Clearing the display
    function clearDisplay() {
      inputCode.value = inputCode.value.substr(0, inputCode.value.length - 1);
    }
  </script>

</x-app-layout>



