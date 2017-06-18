<div class="panel panel-default">
  <div class="panel-heading">
      <a href="#" title="{{ $reply->owner->name }}index.blade.php">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
  </div>
  <div class="panel-body">
      {{ $reply->body }}
  </div>
</div>
