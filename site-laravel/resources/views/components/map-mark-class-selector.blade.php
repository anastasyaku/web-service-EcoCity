<div id="mark-selection-desktop" class="card mark-controls shadow-lg" style="display: none">
    <div id="mark-selection-body" class="card-body">
        @foreach ($markTypes as $markData)
            <label class="container_check">
                {{ $markData["label"] }}
                <input type="checkbox" id="checkbox-{{ $markData["type"] }}-{{ $markData["category"] }}" class="mark-class-checkbox">
                <span class="checkmark"></span>
            </label>
        @endforeach
    </div>
</div>
