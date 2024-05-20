<x-app-layout>
    <div class="container mt-4">
        @include('layouts.flash')
        <div class="row">
            @if (count($books))
                @foreach ($books as $book)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{$book->name}} - <span class="fw-normal">{{$book->category->name}}</span></h5>
                            <p class="card-text">{{$book->description}}</p>
                            <p class="card-text"><strong>Author: </strong>{{$book->author}}</p>
                            <p class="card-text"><strong>Price: </strong>{{$book->price}}</p>

                            <div class="row">
                                @if ($cart && array_key_exists($book->id, $cart))
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-secondary remove-from-cart book-btn-{{$book->id}}" data-book-id="{{$book->id}}" data-action-url="{{route('cart.remove')}}">Remove from cart</button>
                                    </div>
                                    <div class="col-md-6 quantity-div-{{$book->id}}">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger btn-number quantity-change" data-book-id="{{$book->id}}" data-increase="false">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                            </span>
                                            <input type="number" class="form-control quantity-{{$book->id}}" value="{{$cart[$book->id]}}" min="1" placeholder="Quantity" data-book-id="{{$book->id}}" disabled />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success btn-number quantity-change" data-book-id="{{$book->id}}" data-increase="true">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary add-to-cart book-btn-{{$book->id}}" data-book-id="{{$book->id}}" data-action-url="{{route('cart.add')}}">Add to cart</button>
                                    </div>
                                    <div class="col-md-6 hidden quantity-div-{{$book->id}}">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger btn-number quantity-change" data-book-id="{{$book->id}}" data-increase="false">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                            </span>
                                            <input type="number" class="form-control quantity-{{$book->id}}" min="1" placeholder="Quantity" data-book-id="{{$book->id}}" disabled />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success btn-number quantity-change" data-book-id="{{$book->id}}" data-increase="true">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <h2>No books available...</h2>
                </div>
            @endif
        </div>
    </div>

    @section('page-script')
    <script>
        $(function() {
            @include('common.cart-js')
            
            $(document).on('click', '.add-to-cart, .remove-from-cart', function() {
                const id = $(this).data('book-id');
                const actionUrl = $(this).attr('data-action-url');
                
                $.ajax({
                    type: 'POST',
                    url: actionUrl,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function (res) {
                        toggleCartBtn(id);
                    },
                    error: function (err) {
                        alert('Something went wrong');
                    }
                });
            });

            function toggleCartBtn(id) {
                const bookBtn = $(`.book-btn-${id}`);

                if (bookBtn.hasClass('add-to-cart')) {
                    bookBtn.attr('data-action-url', "{{route('cart.remove')}}")
                        .removeClass('btn-primary add-to-cart')
                        .addClass('btn-secondary remove-from-cart')
                        .text('Remove from cart');

                    $(`.quantity-div-${id}`).show().val(1);
                } else {
                    bookBtn.attr('data-action-url', "{{route('cart.add')}}")
                        .addClass('btn-primary add-to-cart')
                        .removeClass('btn-secondary remove-from-cart')
                        .text('Add to cart');

                    $(`.quantity-div-${id}`).hide();
                }

                $(`.quantity-${id}`).val(1);
            }
        });
    </script>
    @endsection
</x-app-layout>