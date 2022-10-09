<div>
  <div class="container">
    <div class="row gx-2-gy-2 h-100">
      <div class="col-9">
        @foreach($signs[$lang] as $keyLine => $lines)
          <div class="row mb-1 gx-2">
            @foreach($lines as $keySign => $sign)
              @if($keyLine == 3 AND $sign == 'translate')
                <div class="col d-grid"><button type="button" wire:click="switchLangs" class="btn btn-outline-light btn-lg"><i class="bi bi-translate"></i></button></div>
                @continue
              @endif

              <div class="col d-grid"><input type="button" value="{{ $sign }}" onclick="display('{{ $sign }}')" class="btn btn-outline-light btn-lg"></div>

              @if($keyLine == 0 AND $sign == 0)
                <div class="col d-grid"><button type="button" onclick="clearDisplay()" class="btn btn-outline-light btn-lg"><i class="bi bi-backspace"></i></button></div>
              @endif
            @endforeach
          </div>
        @endforeach
      </div>
      <div class="col-3">
        <div class="row gx-2 gy-1">
          <div class="col-4 d-grid"><input type="button" value="7" onclick="display(9)" class="btn btn-outline-light btn-lg"></div>
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
