<div class="d-flex justify-content-around">
    <a href="{{ route('leaves.edit', $row->id) }}" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('leaves.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>