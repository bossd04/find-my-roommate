@foreach($activities as $activity)
    <li class="relative group" data-activity-id="{{ $activity['id'] }}">
        @unless($loop->last)
            <span class="absolute top-10 left-6 h-full w-0.5 bg-gray-200 group-hover:bg-indigo-300"></span>
        @endunless

        <div class="relative flex items-start space-x-4 p-5 rounded-2xl hover:bg-white/80 hover:shadow-lg transition-all duration-200 border {{ !$activity['is_read'] ? 'border-indigo-100 bg-indigo-50' : 'border-transparent' }}">
            <!-- Avatar -->
            <div class="relative">
                <img src="{{ $activity['avatar'] }}"
                     class="h-14 w-14 rounded-xl shadow-md ring-2 ring-white object-cover" />

                <span class="absolute -bottom-1 -right-1 bg-{{ $activity['color'] }}-500 p-1.5 rounded-full shadow">
                    <svg class="h-3.5 w-3.5 text-white" fill="currentColor"
                         viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="{{ $activity['icon'] }}"
                              clip-rule="evenodd"/>
                    </svg>
                </span>
            </div>

            <!-- Content -->
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-gray-900 text-md">
                        {{ $activity['title'] }}
                    </h4>
                    <p class="text-xs text-gray-500">
                        {{ $activity['time']->diffForHumans() }}
                    </p>
                </div>

                <p class="mt-1 text-gray-700 text-sm">
                    {{ $activity['description'] }}
                </p>

                <div class="mt-3">
                    <a href="{{ $activity['url'] }}"
                       class="px-3 py-1.5 rounded-lg bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-700 text-xs font-medium hover:bg-{{ $activity['color'] }}-200 transition">
                        {{ $activity['action'] }}
                    </a>
                </div>
            </div>
        </div>
    </li>
@endforeach
