<!-- Modal -->
<div class="modal fade" id="movie{{ $movie->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">View Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">{{ "Name: " }}{{ $movie->name }}</div>
                <div class="col-md-12">{{ "Date: " }}{{ $movie->date }}</div>
                <div class="col-md-12">{{ "Price: " }}{{ $movie->price }}</div>
                <div class="col-md-12">{{ "Author: " }}{{ $movie->author }}</div>
                <div class="col-md-12">{{ "Description: " }}{{ $movie->description }}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
