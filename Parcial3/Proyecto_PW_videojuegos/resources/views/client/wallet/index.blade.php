
@extends('layouts.launcher')

@section('content')

<div class="max-w-6xl mx-auto space-y-8">

    <!-- Encabezado -->
    <div>
        <p class="text-cyan-400 text-sm font-bold tracking-widest uppercase">
            MasterGames / Cuenta
        </p>
        <h1 class="font-gaming text-4xl font-bold text-white mt-2">
            MONEDERO NL COINS
        </h1>
        <p class="text-slate-400 mt-2">
            Administra tu saldo y consulta tus movimientos.
        </p>
    </div>

    <!-- Saldo -->
    <div class="bg-gradient-to-r from-[#142b3d] to-[#19213b] border border-cyan-500/30 rounded-2xl p-8 shadow-xl shadow-cyan-950/20">
        <p class="text-slate-400 text-sm uppercase tracking-widest font-semibold">
            Saldo disponible
        </p>

        <div class="flex items-baseline gap-3 mt-3">
            <h2 class="font-gaming text-5xl font-bold text-emerald-400">
                {{ number_format($user->wallet_balance, 2) }}
            </h2>
            <span class="text-cyan-300 font-bold text-lg">
                NL Coins
            </span>
        </div>

        <p class="text-slate-400 text-sm mt-3">
            Utiliza tus NL Coins para adquirir videojuegos en MasterGames.
        </p>
    </div>

    <!-- Recargar saldo -->
    <div class="bg-[#111622] border border-slate-800 rounded-2xl p-6">
        <h2 class="font-gaming text-2xl font-bold text-white">
            CANJEAR CLAVE
        </h2>

        <p class="text-slate-400 text-sm mt-1 mb-6">
            Introduce tu clave de recarga para recibir 100 NL Coins.
        </p>

        <form action="{{ route('client.wallet.redeem') }}" method="POST"
              class="flex flex-col sm:flex-row gap-3">
            @csrf

            <input
                type="text"
                name="key"
                value="{{ old('key') }}"
                placeholder="Introduce tu clave..."
                required
                maxlength="255"
                autocomplete="off"
                class="flex-1 bg-[#0b0e14] border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition"
            >

            <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold px-8 py-3 rounded-xl transition shadow-lg shadow-cyan-900/30">
                CANJEAR
            </button>
        </form>

        @error('key')
            <p class="text-rose-400 text-sm mt-3">{{ $message }}</p>
        @enderror

        <div class="mt-5 p-4 rounded-xl bg-cyan-950/30 border border-cyan-800/40">
            <p class="text-cyan-300 text-sm">
                Cada canje válido agrega 100 NL Coins a tu cuenta.
            </p>
        </div>
    </div>

    <!-- Historial -->
    <div class="bg-[#111622] border border-slate-800 rounded-2xl overflow-hidden">

        <div class="p-6 border-b border-slate-800">
            <h2 class="font-gaming text-2xl font-bold text-white">
                HISTORIAL DE MOVIMIENTOS
            </h2>
            <p class="text-slate-400 text-sm mt-1">
                Consulta tus recargas y compras anteriores.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#182030] text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">Movimiento</th>
                        <th class="px-6 py-4">Descripción</th>
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4 text-right">Cantidad</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-6 py-4">
                                @if ($transaction->type === 'recharge')
                                    <span class="text-emerald-400 font-semibold">
                                        Recarga
                                    </span>
                                @else
                                    <span class="text-rose-400 font-semibold">
                                        Compra
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-slate-300 text-sm">
                                {{ $transaction->description ?? 'Sin descripción' }}
                            </td>

                            <td class="px-6 py-4 text-slate-400 text-sm whitespace-nowrap">
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-right font-bold whitespace-nowrap
                                {{ $transaction->type === 'recharge' ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $transaction->type === 'recharge' ? '+' : '-' }}{{ number_format(abs((float) $transaction->amount), 2) }}
                                NL
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="text-slate-400">
                                    Todavía no tienes movimientos registrados.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-slate-800">
            {{ $transactions->links() }}
        </div>
    </div>

</div>

@endsection
