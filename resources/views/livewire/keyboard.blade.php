<div>
  <div wire:ignore.self class="offcanvas offcanvas-bottom bg-dark" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel" style="z-index: 1065;">
    <div class="position-relative">
      <div class="position-absolute" style="top:-30px !important; right:30px !important;">
        <span class="badge bg-dark pt-2 pb-3">
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </span>
      </div>
    </div>
    <div class="offcanvas-body small">
      <div class="container">
        <div class="row h-100">
          <div class="col-9">
            <div class="row mb-1 gx-2">
              <div class="col d-grid"><input type="button" value="’" onclick="display('’')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="1" onclick="display('1')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="2" onclick="display('2')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="3" onclick="display('3')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="4" onclick="display('4')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="5" onclick="display('5')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="6" onclick="display('6')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="7" onclick="display('7')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="8" onclick="display('8')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="9" onclick="display('9')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><input type="button" value="0" onclick="display('0')" class="btn btn-outline-light btn-lg"></div>
              <div class="col d-grid"><button type="button" onclick="clearDisplay()" class="btn btn-outline-light btn-lg"><i class="bi bi-backspace"></i></button></div>
            </div>
            @foreach($signs[$lang] as $keyLine => $lines)
              <div class="row mb-1 gx-2">
                @foreach($lines as $keySign => $sign)
                  @if($sign == 'translate')
                    <div class="col d-grid"><button type="button" wire:click="switchLangs" class="btn btn-outline-light btn-lg"><i class="bi bi-translate"></i></button></div>
                    @continue
                  @endif

                  @if($sign == 'space')
                    <div class="col-2 d-grid"><input type="button" value=" " onclick="display(' ')" class="btn btn-outline-light btn-lg"></div>
                    @continue
                  @endif

                  <div class="col d-grid"><input type="button" value="{{ $sign }}" onclick="display('{{ $sign }}')" class="btn btn-outline-light btn-lg"></div>
                @endforeach
              </div>
            @endforeach
          </div>
          <div class="col-3">
            <div class="row gx-2 gy-1">
              <div class="col-4 d-grid"><input type="button" value="7" onclick="display(7)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="8" onclick="display(8)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="9" onclick="display(9)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="4" onclick="display(4)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="5" onclick="display(5)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="6" onclick="display(6)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="1" onclick="display(1)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="2" onclick="display(2)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="3" onclick="display(3)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-8 d-grid"><input type="button" value="0" onclick="display(0)" class="btn btn-outline-light btn-lg"></div>
              <div class="col-4 d-grid"><input type="button" value="." onclick="display('.')" class="btn btn-outline-light btn-lg"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
