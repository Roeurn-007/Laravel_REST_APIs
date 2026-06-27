<div class="modal fade" id="product{{ $product->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="product{{ $product->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="product{{ $product->id }}Label">View Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-start">
                    <p class="mb-2"><strong>Name:</strong> {{ $product->name }}</p>
                    <p class="mb-2"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                    <p class="mb-2"><strong>Stock:</strong> {{ $product->stock }}</p>
                    <p class="mb-2"><strong>Category:</strong> {{ $product->category?->name ?? '—' }}</p>
                    <p class="mb-3"><strong>Status:</strong> {{ $product->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
                @if ($product->image_url)
                    <div class="text-center">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 300px;">
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
