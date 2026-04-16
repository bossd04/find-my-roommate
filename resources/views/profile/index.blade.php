<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Complete Your Profile
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section with Background -->
            <div class="mb-8 rounded-lg p-8 text-white relative overflow-hidden" 
                 style="background: linear-gradient(135deg, #2563eb 0%, #9333ea 50%, #4f46e5 100%);">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute top-20 right-20 w-32 h-32 bg-blue-300 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-purple-300 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-10 right-10 w-24 h-24 bg-indigo-300 rounded-full blur-xl"></div>
                </div>
                
                <div class="relative z-10">
                    <h1 class="text-3xl font-bold mb-2">Complete Your Profile</h1>
                    <p class="text-blue-100 text-lg mb-6">Fill out all sections below to unlock all system features.</p>
                    
                    <!-- Progress Bar -->
                    <div class="max-w-md">
                        <div class="flex justify-between text-sm mb-2 text-blue-100">
                            <span class="font-medium">Profile Completion</span>
                            <span class="font-bold">{{ $completionPercentage }}%</span>
                        </div>
                        <div class="h-3 w-full bg-white/20 rounded-full overflow-hidden backdrop-blur-sm border border-white/30">
                            <div class="h-full bg-white rounded-full transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(255,255,255,0.7)]" 
                                 style="width: {{ $completionPercentage }}%"></div>
                        </div>
                        <p class="text-xs text-blue-100 mt-2 italic opacity-80">
                            @if($completionPercentage < 100)
                                You're almost there! Complete the remaining sections to reach 100%.
                            @else
                                Your profile is 100% complete! Great job!
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="px-4 py-5 sm:p-6 space-y-6">
                        <!-- Enhanced Profile Form with All Sections -->
                        @include('profile.partials.update-profile-details-form-enhanced')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

