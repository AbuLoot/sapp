<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Черновик прихода</h4>

    </div>
  </div>

  <!-- Content -->
  <div class="container">

    @if(session()->has('message'))
      <div class="toast-container position-fixed bottom-0 end-0 p-4">
        <div class="toast align-items-center text-bg-info border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body text-white">{{ session('message') }}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
    @endif

    <div class="table-responsive">
      <table class="table align-middle table-sm table-striped">
        <thead>
          <tr>
            <th scope="col">Номер</th>
            <th scope="col">Название</th>
            <th scope="col">Автор</th>
            <th scope="col">Количество позиции</th>
            <th scope="col">Дата и время</th>
            <th class="text-end" scope="col" colspan="2">Функции</th>
          </tr>
        </thead>
        <tbody>
          @forelse($drafts as $index => $draft)
            <tr>
              <td>{{ $draft->id }}</td>
              <?php $productsData = json_decode($draft->products_data, true) ?? []; ?>
              <td><a href="#" wire:click="openTheDraft({{ $draft->id }})">{{ $draft->title }}</a></td>
              <td>{{ $draft->user->name }}</td>
              <td>{{ $draft->count }}</td>
              <td>{{ $draft->created_at }}</td>
              <td class="text-end"><a href="#" wire:click="openTheDraft({{ $draft->id }})" class="btn btn-outline-primary btn-sm">Открыть</a></td>
              <td class="text-end"><a wire:click="removeFromDrafts({{ $draft->id }})" href="#" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
            </tr>
          @empty
            <tr>
              <td colspan="6">No data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $drafts->links() }}

  </div>
</div>
