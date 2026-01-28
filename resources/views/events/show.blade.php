<x-layouts.app>
    <section class="max-w-7xl mx-auto py-12 px-6">
        <nav class="mb-6">
        <div class="breadcrumbs">
            <ul>
            <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
            <li><a href="{{ route('home') }}" class="link link-neutral">Event</a></li>
            <li>{{ $event->name }}</li>
            </ul>
        </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left / Main area -->
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow">
                    <figure>
                        <img src="{{ $event->picture
                ? asset('images/events/' . $event->picture)
                : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp'
            }}" alt="{{ $event->name }}" class="w-full h-96 object-cover" />
                    </figure>
                    <div class="card-body">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h1 class="text-3xl font-extrabold">{{ $event->name }}</h1>
                                <p class="text-sm text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($event->date_time)->locale('id')->translatedFormat('d F Y, H:i') }} • 📍
                                {{ $event->location }}
                                </p>

                                <div class="mt-3 flex gap-2 items-center">
                                    <span class="badge badge-primary">{{ $event->category?->name ?? 'Tanpa Kategori' }}</span>
                                    <span class="badge">{{ $event->user?->name ?? 'Penyelenggara' }}</span>
                                </div>
                            </div>

                        </div>

                    <p class="mt-4 text-gray-700 leading-relaxed">{{ $event->description }}</p>

                    <div class="divider"></div>

                    <h3 class="text-xl font-bold">Pilih Tiket</h3>

                    <div class="mt-4 space-y-4">
                        @forelse($event->tickets as $ticket)
                        <div class="card card-side shadow-sm p-4 items-center">
                            <div class="flex-1">
                                <h4 class="font-bold">{{ $ticket->type }}</h4>
                                <p class="text-sm text-gray-500">
                                    Stok: <span id="stock-{{ $ticket->id }}">{{ $ticket->stock }}</span>
                                </p>
                            </div>

                            <div class="w-44 text-right">
                                @if ($ticket->stock != 0)
                                    <div class="text-lg font-bold">
                                        {{ $ticket->price == 0 ? 'Gratis' : 'Rp ' . number_format($ticket->price, 0, ',', '.') }}
                                        {{-- {{ $ticket->price ? 'Rp ' . number_format($ticket->price, 0, ',', '.') : 'Gratis' }} --}}
                                    </div>

                                    <div class="mt-3 flex items-center justify-end gap-2">
                                        <button type="button" class="btn btn-sm btn-outline" data-action="dec" data-id="{{ $ticket->id }}"
                                        aria-label="Kurangi satu">−</button>
                                        <input id="qty-{{ $ticket->id }}" type="number" min="0" max="{{ $ticket->stock }}" value="0"
                                        class="input input-bordered w-16 text-center" data-id="{{ $ticket->id }}" />
                                        <button type="button" class="btn btn-sm btn-outline" data-action="inc" data-id="{{ $ticket->id }}"
                                        aria-label="Tambah satu">+</button>
                                    </div>

                                    <div class="text-sm text-gray-500 mt-2">
                                        Subtotal: <span id="subtotal-{{ $ticket->id }}">Rp 0</span>
                                    </div>
                                @else
                                    <p class="text-gray-700">Stok habis!</p>
                                @endif
                            </div>
                        </div>
                        @empty
                            <div class="alert alert-info">Tiket belum tersedia untuk acara ini.</div>
                        @endforelse
                    </div>

                    </div>
                </div>
            </div>

            <!-- Right / Summary -->
            <aside class="lg:col-span-1">
                <div class="card sticky top-24 p-4 bg-base-100 shadow">
                <h4 class="font-bold text-lg">Ringkasan Pembelian</h4>

                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Item</span>
                        <span id="summaryItems">0</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold mt-1">
                        <span>Total</span>
                        <span id="summaryTotal">Rp 0</span>
                    </div>
                </div>

                <div class="divider"></div>

                <div id="selectedList" class="space-y-2 text-sm text-gray-700">
                    <p class="text-gray-500">Belum ada tiket dipilih</p>
                </div>

                <!-- Jenis Pembayaran -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Jenis Pembayaran</span>
                    </label>
                    <select 
                        id="payment_type_id"
                        name="payment_type_id"
                        class="select select-bordered w-full" 
                        required
                    >
                        <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                        @foreach ($payment_types as $pt)
                            <option value="{{ $pt->id }}">
                                {{ $pt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @auth
                    <button 
                        id="checkoutButton"
                        class="btn btn-primary !bg-blue-900 text-white btn-block mt-6"
                        onclick="openCheckout()"
                        disabled>Checkout</button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-block mt-6 text-white">Login untuk Checkout</a>
                @endauth

                </div>
            </aside>
        </div>

        <!-- Checkout Modal -->
        <dialog id="checkout_modal" class="modal">
            <form method="dialog" class="modal-box">
                <h3 class="font-bold text-lg">Konfirmasi Pembelian</h3>
                <div class="mt-4 space-y-2 text-sm">
                    <div id="modalItems">
                        <p class="text-gray-500">Belum ada item.</p>
                    </div>

                    <div class="divider"></div>
                    <div class="flex justify-between items-center">
                        <span class="font-bold">Total</span>
                        <span class="font-bold text-lg" id="modalTotal">Rp 0</span>
                    </div>
                </div>

                <div class="text-right mt-2">
                    <span class="">via</span>
                    <span class="font-bold text-lg" id="modalPayment"></span>
                </div>

                <div class="modal-action">
                <button class="btn">Tutup</button>
                <button type="button" class="btn btn-primary px-4 !bg-blue-900 text-white" id="confirmCheckout">Konfirmasi</button>
                </div>
            </form>
        </dialog>

    </section>

  <script>
    (function () {
      // Helper to format Indonesian currency
      const formatRupiah = (value) => {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
      }

      window.tickets = {
        @foreach($event->tickets as $ticket)
              {{ $ticket->id }}: {
            id: {{ $ticket->id }},
            price: {{ $ticket->price ?? 0 }},
            stock: {{ $ticket->stock }},
            type: "{{ e($ticket->type) }}"
          },
        @endforeach
      };

    const summaryItemsEl = document.getElementById('summaryItems');
    const summaryTotalEl = document.getElementById('summaryTotal');
    const selectedListEl = document.getElementById('selectedList');
    const checkoutButton = document.getElementById('checkoutButton');

    function updateSummary() {
        let totalQty = 0;
        let totalPrice = 0;
        let selectedHtml = '';

        Object.values(tickets).forEach(t => {
            const qtyInput = document.getElementById('qty-' + t.id);
            if (!qtyInput) return;
            const qty = Number(qtyInput.value || 0);
            if (qty > 0) {
            totalQty += qty;
            totalPrice += qty * t.price;
            selectedHtml += `<div class="flex justify-between"><span>${t.type} x ${qty}</span><span>${formatRupiah(qty * t.price)}</span></div>`;
            }
        });

        summaryItemsEl.textContent = totalQty;
        summaryTotalEl.textContent = formatRupiah(totalPrice);
        selectedListEl.innerHTML = selectedHtml || '<p class="text-gray-500">Belum ada tiket dipilih</p>';
        checkoutButton.disabled = totalQty === 0;
    }

    // Wire up plus/minus buttons and manual input
    document.querySelectorAll('[data-action="inc"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.currentTarget.dataset.id;
            const input = document.getElementById('qty-' + id)
            const info = tickets[id];
            if (!input || !info) return;
            let val = Number(input.value || 0);
            if (val < info.stock) val++;
            input.value = val;
            updateTicketSubtotal(id);
            updateSummary();
        });
    });

    document.querySelectorAll('[data-action="dec"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.currentTarget.dataset.id;
            const input = document.getElementById('qty-' + id);
            if (!input) return;
            let val = Number(input.value || 0);
            if (val > 0) val--;
            input.value = val;
            updateTicketSubtotal(id);
            updateSummary();
        });
    });

    document.querySelectorAll('input[id^="qty-"]').forEach(input => {
        input.addEventListener('change', (e) => {
        const el = e.currentTarget;
        const id = el.dataset.id;
        const info = tickets[id];
        let val = Number(el.value || 0);
        if (val < 0) val = 0;
        if (val > info.stock) val = info.stock;
        el.value = val;
        updateTicketSubtotal(id);
        updateSummary();
        });
    });

    function updateTicketSubtotal(id) {
        const t = tickets[id];
        const qty = Number(document.getElementById('qty-' + id).value || 0);
        const subtotalEl = document.getElementById('subtotal-' + id);
        if (subtotalEl) subtotalEl.textContent = formatRupiah(qty * t.price);
    }

    // Checkout modal
    window.openCheckout = function () {
        const modal = document.getElementById('checkout_modal');
        // populate modal items
        const modalItems = document.getElementById('modalItems');
        const modalTotal = document.getElementById('modalTotal');

        let itemsHtml = '';
        let total = 0;
        Object.values(tickets).forEach(t => {
            const qty = Number(document.getElementById('qty-' + t.id).value || 0);
            if (qty > 0) {
            itemsHtml += `<div class="flex justify-between"><span>${t.type} x ${qty}</span><span>${formatRupiah(qty * t.price)}</span></div>`;
            total += qty * t.price;
            }
        });

        const select = document.getElementById('payment_type_id');
        const paymentTypeName = select.options[select.selectedIndex].text;

        modalPayment.textContent = paymentTypeName;

        modalItems.innerHTML = itemsHtml || '<p class="text-gray-500">Belum ada item.</p>';
        modalTotal.textContent = formatRupiah(total);

        if (typeof modal.showModal === 'function') {
            modal.showModal();
        } else {
            // fallback for older browsers
            modal.classList.add('modal-open');
        }
    }

    // init
    updateSummary();
    }) ();
    </script>

    <script>
    // code script lain

        document.getElementById('confirmCheckout').addEventListener('click', async () => {
            const btn = document.getElementById('confirmCheckout');
            btn.setAttribute('disabled', 'disabled');
            btn.textContent = 'Memproses...';

            // gather items
            const items = [];
            Object.values(tickets).forEach(t => {
                const qty = Number(document.getElementById('qty-' + t.id).value || 0);
                if (qty > 0) items.push({ ticket_id: t.id, quantity: qty});
            });

            const payment_type = document.getElementById('payment_type_id').value;

            if (items.length === 0) {
                alert('Tidak ada tiket dipilih');
                btn.removeAttribute('disabled');
                btn.textContent = 'Konfirmasi (placeholder)';
                return;
            }

            try {
                const res = await fetch("{{ route('orders.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ event_id: {{ $event->id }}, items, payment_type_id: payment_type })
                });

                if (!res.ok) {
                const text = await res.text();
                throw new Error(text || 'Gagal membuat pesanan');
                }

                const data = await res.json();
                // redirect to orders list
                window.location.href = data.redirect || '{{ route('orders.index') }}';
            } catch (err) {
                console.log(err);
                alert('Terjadi kesalahan saat memproses pesanan: ' + err.message);
                btn.removeAttribute('disabled');
                btn.textContent = 'Konfirmasi (placeholder)';
            }
        });
        
    </script>
</x-layouts.app>