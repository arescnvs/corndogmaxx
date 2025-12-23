<div class="min-h-screen w-full flex items-center justify-center bg-gray-100 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md text-center p-8">
        <h1 class="text-2xl font-extrabold text-gray-800 mb-6">
            Pilih Metode Pemesanan
        </h1>

        <div class="space-y-4">
            <button wire:click="pilihMetode('ditempat')"
                class="w-full py-3 rounded-lg border border-gray-300 text-lg font-semibold 
                    {{ $selectedMethod === 'ditempat' 
                        ? 'bg-blue-600 text-white shadow-md' 
                        : 'bg-gray-50 text-gray-800 hover:bg-gray-100' }}">
                Di Tempat
            </button>

            <button wire:click="pilihMetode('kirim')"
                class="w-full py-3 rounded-lg border border-gray-300 text-lg font-semibold 
                    {{ $selectedMethod === 'kirim' 
                        ? 'bg-blue-600 text-white shadow-md' 
                        : 'bg-gray-50 text-gray-800 hover:bg-gray-100' }}">
                Kirim ke Alamat
            </button>
        </div>

        <button wire:click="lanjutDariMetode"
            class="mt-8 w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-150">
            Lanjutkan
        </button>
    </div>
</div>
