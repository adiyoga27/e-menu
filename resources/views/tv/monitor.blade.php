<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean - TV Display</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        .glow { box-shadow: 0 0 20px rgba(249, 115, 22, 0.2); }
        .status-dot { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
    </style>
</head>
<body class="text-white">
    <div x-data="monitorApp()" x-init="init()" class="h-screen flex flex-col p-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-10 bg-slate-800/50 p-6 rounded-3xl border border-slate-700 shadow-2xl">
            <div class="flex items-center gap-4">
                <div class="bg-orange-500 p-3 rounded-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tighter">DISPLAY ANTREAN</h1>
                    <p class="text-slate-400 font-medium">Monitoring Real-time Pesanan Pelanggan</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-5xl font-black tabular-nums tracking-tight" x-text="currentTime"></div>
                <div class="text-slate-400 font-bold uppercase tracking-widest mt-1" x-text="currentDate"></div>
            </div>
        </div>

        <div class="flex-grow grid grid-cols-12 gap-8 overflow-hidden">
            <!-- Left: Stats -->
            <div class="col-span-3 flex flex-col gap-6">
                <div class="bg-slate-800/40 border border-slate-700 p-8 rounded-[2rem] flex-1 flex flex-col justify-center items-center">
                    <div class="text-orange-500 font-black text-8xl mb-2" x-text="processingOrders.length"></div>
                    <div class="text-slate-400 font-bold uppercase tracking-widest text-center">Sedang Diolah</div>
                </div>
                <div class="bg-slate-800/40 border border-slate-700 p-8 rounded-[2rem] flex-1 flex flex-col justify-center items-center">
                    <div class="text-blue-500 font-black text-8xl mb-2" x-text="pendingOrders.length"></div>
                    <div class="text-slate-400 font-bold uppercase tracking-widest text-center">Belum Proses</div>
                </div>
            </div>

            <!-- Right: Queue List -->
            <div class="col-span-9 bg-slate-800/40 border border-slate-700 rounded-[2.5rem] p-8 overflow-hidden flex flex-col shadow-inner">
                <div class="grid grid-cols-12 mb-6 px-8 text-slate-400 font-bold uppercase tracking-widest text-sm">
                    <div class="col-span-2">No. Antrean</div>
                    <div class="col-span-4">Nama Pelanggan</div>
                    <div class="col-span-3 text-center">Status Pesanan</div>
                    <div class="col-span-3 text-right">Pembayaran</div>
                </div>

                <div class="flex-grow overflow-y-auto pr-4 space-y-4 custom-scrollbar">
                    <template x-if="orders.length === 0">
                        <div class="h-full flex flex-col items-center justify-center opacity-30">
                            <svg class="w-32 h-32 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <div class="text-2xl font-bold">Belum Ada Antrean</div>
                        </div>
                    </template>

                    <template x-for="order in orders" :key="order.order_number">
                        <div class="grid grid-cols-12 items-center bg-slate-900 border border-slate-700 p-6 rounded-3xl transition-all duration-500 animate-fade-in"
                             :class="order.status === 'processing' ? 'border-l-8 border-l-orange-500' : 'border-l-8 border-l-blue-500 opacity-80'">
                            
                            <div class="col-span-2 text-3xl font-black text-white" x-text="'#' + order.order_number"></div>
                            
                            <div class="col-span-4">
                                <div class="text-3xl font-bold truncate pr-4 text-slate-100" x-text="order.customer_name"></div>
                                <div class="text-slate-500 text-sm font-medium mt-1 uppercase tracking-wide" x-text="order.created_at"></div>
                            </div>
                            
                            <div class="col-span-3 flex justify-center">
                                <template x-if="order.status === 'processing'">
                                    <div class="flex items-center gap-3 bg-orange-500/10 text-orange-500 px-6 py-2 rounded-full border border-orange-500/30">
                                        <div class="w-3 h-3 bg-orange-500 rounded-full status-dot"></div>
                                        <span class="font-black text-xl uppercase tracking-tighter">Proses</span>
                                    </div>
                                </template>
                                <template x-if="order.status === 'pending'">
                                    <div class="flex items-center gap-3 bg-blue-500/10 text-blue-500 px-6 py-2 rounded-full border border-blue-500/30">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <span class="font-black text-xl uppercase tracking-tighter opacity-70">Menunggu</span>
                                    </div>
                                </template>
                            </div>

                            <div class="col-span-3 flex justify-end">
                                <template x-if="order.payment_status === 'paid'">
                                    <div class="bg-emerald-500 text-white px-5 py-2 rounded-2xl font-black text-xl flex items-center gap-2 shadow-lg shadow-emerald-500/20">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        LUNAS
                                    </div>
                                </template>
                                <template x-if="order.payment_status !== 'paid'">
                                    <div class="bg-red-500/10 text-red-500 px-5 py-2 rounded-2xl font-black text-xl border border-red-500/30">
                                        BELUM BAYAR
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 flex justify-between items-center text-slate-500 font-bold uppercase tracking-[0.3em] text-xs">
            <div>&copy; 2024 E-MENU DIGITAL SYSTEM</div>
            <div class="flex items-center gap-2 text-emerald-500 animate-pulse">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                SYSTEM LIVE & CONNECTED
            </div>
        </div>
    </div>

    <script>
        function monitorApp() {
            return {
                orders: [],
                currentTime: '',
                currentDate: '',
                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    
                    this.fetchQueue();
                    setInterval(() => this.fetchQueue(), 5000); // Polling every 5s
                },
                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                },
                fetchQueue() {
                    fetch('/tv/queue-data')
                        .then(res => res.json())
                        .then(data => {
                            this.orders = data;
                        });
                },
                get processingOrders() {
                    return this.orders.filter(o => o.status === 'processing');
                },
                get pendingOrders() {
                    return this.orders.filter(o => o.status === 'pending');
                }
            }
        }
    </script>
</body>
</html>
