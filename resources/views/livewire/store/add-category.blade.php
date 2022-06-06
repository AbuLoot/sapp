<div>

  <!-- Modal Add Category -->
  <div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <form wire:submit.prevent="saveCategory">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Добавить категорию</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="title" class="form-label">Название категории</label>
              <input type="text" wire:model.defer="category.title" class="form-control @error('category.title') is-invalid @enderror" id="title" required>
              @error('category.title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="category_id">Категории</label>
              <select id="category_id" wire:model.defer="category_id" class="form-control">
                <option value="0">Выбор родительской категорий...</option>
                <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
                  <?php foreach ($nodes as $node) : ?>
                    <option value="{{ $node->id }}">{{ PHP_EOL.$prefix.' '.$node->title }}</option>
                    <?php $traverse($node->children, $prefix.'__'); ?>
                  <?php endforeach; ?>
                <?php }; ?>
                <?php $traverse($categories); ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" data-bs-dismiss="modal" class="btn btn-primary text-center"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
