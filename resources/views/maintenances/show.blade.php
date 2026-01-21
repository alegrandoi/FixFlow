<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Maintenance Details') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('maintenances.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Asset</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('assets.show', $maintenance->asset) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $maintenance->asset->name }} ({{ $maintenance->asset->serial_number }})
                                </a>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type</label>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($maintenance->type->value === 'preventive') bg-blue-100 text-blue-800
                                    @else bg-orange-100 text-orange-800
                                    @endif">
                                    {{ $maintenance->type->label() }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date Performed</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $maintenance->performed_at->format('F d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cost</label>
                            <p class="mt-1 text-sm text-gray-900">${{ number_format($maintenance->cost, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Technician</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $maintenance->technician->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $maintenance->created_at->format('F d, Y H:i') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $maintenance->description }}</p>
                        </div>

                        @if($maintenance->notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">Notes</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $maintenance->notes }}</p>
                            </div>
                        @endif
                    </div>

                    @can('delete', $maintenance)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this maintenance record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Delete Maintenance Record
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
