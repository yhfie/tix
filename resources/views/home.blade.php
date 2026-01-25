<x-layouts.app>
    <div class="hero bg-blue-900 min-h-screen">
        <div class="hero-content text-center text-white">
            <div class="max-w-4xl">
                <h1 class="text-5xl font-bold">Hi, Amankan Tiketmu yuk.</h1>
                <p class="py-6">
                    BengTix: Beli tiket, auto asik.
                </p>
            </div>
        </div>
    </div>

    <section class="max-w-7xl mx-auto py-12 px-6">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black uppercase italic">Event</h2>
            <div class="flex gap-2">
                <a href="{{ route('home') }}">
                    <x-user.category-pill 
                        :label="'Semua'" 
                        :active="!request('category')" />
                </a>
                @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->id]) }}">
                    <x-user.category-pill 
                        :label="$category->name" 
                        :active="request('category') == $category->id" />
                </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($events as $event)
            <x-user.event-card 
                :title="$event->name" 
                :date="$event->date_time" 
                :location="$event->location"
                :price="$event->tickets_min_price"
                :image="$event->picture"
                :href="route('events.show', $event)" 
            />
            @endforeach
        </div>
    </section>
</x-layouts.app>