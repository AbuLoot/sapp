@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Редактирование</h2>

  @include('components.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/admin/projects" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="{{ route('projects.update', [$lang, $project->id]) }}" method="post" enctype="multipart/form-data">
        <input name="_method" type="hidden" value="PUT">
        {!! csrf_field() !!}
        <div class="form-group">
          <label for="title">Название</label>
          <input type="text" class="form-control" id="title" name="title" minlength="2" maxlength="80" value="{{ (old('title')) ? old('title') : $project->title }}" required>
        </div>
        <div class="form-group">
          <label for="title_extra">Название дополнительное</label>
          <input type="text" class="form-control" id="title_extra" name="title_extra" minlength="2" maxlength="80" value="{{ (old('title_extra')) ? old('title_extra') : $project->title_extra }}">
        </div>
        <div class="form-group">
          <label for="slug">Slug</label>
          <input type="text" class="form-control" id="slug" name="slug" minlength="2" maxlength="80" value="{{ (old('slug')) ? old('slug') : $project->slug }}">
        </div>
        <div class="form-group">
          <label for="company_id">Компания</label>
          <select id="company_id" name="company_id" class="form-control">
            <option value=""></option>
            @foreach($companies as $company)
              @if ($company->id == $project->company_id)
                <option value="{{ $company->id }}" selected>{{ $company->title }}</option>
              @else
                <option value="{{ $company->id }}">{{ $company->title }}</option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="project_id">Проекты</label>
          <select id="project_id" name="project_id" class="form-control">
            <option value=""></option>
            <?php $traverse = function ($nodes, $prefix = null) use (&$traverse, $project) { ?>
              <?php foreach ($nodes as $node) : ?>
                <option value="{{ $node->id }}" <?= ($node->id == $project->parent_id) ? 'selected' : ''; ?>>{{ PHP_EOL.$prefix.' '.$node->title }}</option>
                <?php $traverse($node->children, $prefix.'___'); ?>s
              <?php endforeach; ?>
            <?php }; ?>
            <?php $traverse($projects); ?>
          </select>
        </div>
        <div class="form-group">
          <label for="image">Картинка</label>
          <input type="text" class="form-control" id="image" name="image" value="{{ (old('image')) ? old('image') : $project->image }}">
        </div>
        <div class="form-group">
          <label for="sort_id">Номер</label>
          <input type="text" class="form-control" id="sort_id" name="sort_id" maxlength="5" value="{{ (old('sort_id')) ? old('sort_id') : $project->sort_id }}">
        </div>
        <div class="form-group">
          <label for="meta_title">Мета заголовок</label>
          <input type="text" class="form-control" id="meta_title" name="meta_title" maxlength="255" value="{{ (old('meta_title')) ? old('meta_title') : $project->meta_title }}">
        </div>
        <div class="form-group">
          <label for="meta_description">Мета описание</label>
          <input type="text" class="form-control" id="meta_description" name="meta_description" maxlength="255" value="{{ (old('meta_description')) ? old('meta_description') : $project->meta_description }}">
        </div>
        <div class="form-group">
          <label for="lang">Язык</label>
          <select id="lang" name="lang" class="form-control" required>
            <option value=""></option>
            @foreach($languages as $language)
              @if ($project->lang == $language->slug)
                <option value="{{ $language->slug }}" selected>{{ $language->title }}</option>
              @else
                <option value="{{ $language->slug }}">{{ $language->title }}</option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="status">Статус</label>
          @foreach(trans('statuses.data') as $num => $status)
            <br>
            <label>
              <input type="radio" id="status" name="status" value="{{ $num }}" @if ($num == $project->status) checked @endif> {{ $status['title'] }}
            </label>
          @endforeach
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-success"><i class="material-icons">save</i></button>
        </div>
      </form>
    </div>
  </div>
@endsection
