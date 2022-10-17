<div>
  <div wire:ignore.self class="modal fade" id="openCashModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light py-2">
        <div class="modal-body">
          <div class="text-center">
            <div class="display-1">
              <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <h4 class="mb-3">Смена закрыта</h4>
            <button wire:click="backToDashboard" type="button" class="btn btn-outline-dark btn-lg me-1 mb-2">Назад</button>
            <button wire:click="openTheCash" type="button" class="btn btn-success btn-lg me-1 mb-2" @cannot('opening-cash', Auth::user()) disabled @endcannot>Открыть смену</button>
            <br>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@section('scripts')
  <script type="text/javascript">
    const myModal = new bootstrap.Modal(document.getElementById('openCashModal'));
    myModal.show()
  </script>
@endsection