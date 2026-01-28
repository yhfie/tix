<x-layouts.app>
    <section class="max-w-6xl mx-auto py-12 px-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Riwayat Pembelian</h1>
        </div>

        <div class="space-y-4">
        @forelse($orders as $order)
            <article class="card lg:card-side bg-base-100 shadow-md overflow-hidden">
            <figure class="lg:w-48">
                <img
                src="{{ $order->event?->picture ? asset('images/events/' . $order->event->picture) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                alt="{{ $order->event?->name ?? 'Event' }}" class="w-full h-full object-cover" />
            </figure>

            <div class="card-body flex justify-between ">
                <div>
                    <div class="font-bold">
                        Order #{{ $order->id }}
                    </div>
                    <div class="text-sm text-gray-500 mt-1">
                        {{ $order->order_date->translatedFormat('d F Y, H:i') }}
                    </div>
                    <div class="text-sm mt-2">
                        {{ $order->event?->name ?? 'Event' }}
                    </div>
                </div>

                <div class="text-right">
                    <div>
                        <p class="font-bold text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        <p>via {{ $order->paymentType->name }}</p>
                    </div>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary mt-3 text-white">Lihat Detail</a>
                    <a class="btn bg-white border-2 border-red-700 mt-3 text-red-700" onclick="openDeleteModal(this)" data-id="{{ $order->id }}">
                        Hapus
                    </a>
                </div>
            </div>
            </article>
        @empty
            <div class="alert alert-info">Anda belum memiliki pesanan.</div>
        @endforelse
        </div>
    </section>

    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <input type="hidden" name="order_id" id="delete_order_id">

            <h3 class="text-lg font-bold mb-4">Hapus Event</h3>
            <p>Apakah Anda yakin ingin menghapus riwayat ini?</p>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
            function openDeleteModal(button) {
                const id = button.dataset.id;
                const form = document.querySelector('#delete_modal form');
                document.getElementById("delete_order_id").value = id;

                // Set action dengan parameter ID
                form.action = "{{ route('orders.destroy', ':id') }}".replace(':id', id);

                delete_modal.showModal();
            }
    </script>
</x-layouts.app>